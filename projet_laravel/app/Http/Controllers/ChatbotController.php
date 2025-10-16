<?php

namespace App\Http\Controllers;

use App\Models\Chatbot;
use App\Models\Event;
use App\Services\GeminiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    private GeminiService $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    /**
     * Afficher l'interface du chatbot
     */
    public function index()
    {
        $chatbot = Chatbot::getOrCreateSession();
        $recentMessages = $chatbot->getRecentMessages();
        $isGeminiEnabled = $this->geminiService->isConfigured();

        return view('chatbot.index', compact('chatbot', 'recentMessages', 'isGeminiEnabled'));
    }

    /**
     * Traiter un message utilisateur et retourner une réponse IA
     */
    public function processMessage(Request $request): JsonResponse
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'language' => 'sometimes|string|in:fr,en,ar',
        ]);

        try {
            $chatbot = Chatbot::getOrCreateSession();

            // Mettre à jour la langue si fournie
            if ($request->has('language')) {
                $chatbot->update(['language' => $request->language]);
            }

            $userMessage = $request->message;

            // Ajouter le message utilisateur à l'historique
            $chatbot->addMessage('user', $userMessage);

            // Analyser l'intention et générer une réponse
            $response = $this->generateAIResponse($userMessage, $chatbot);

            // Ajouter la réponse du chatbot à l'historique
            $chatbot->addMessage('assistant', $response['content'], $response['metadata']);

            return response()->json([
                'success' => true,
                'response' => $response['content'],
                'metadata' => $response['metadata'],
                'suggestions' => $response['suggestions'] ?? [],
                'timestamp' => now()->toISOString(),
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur chatbot: '.$e->getMessage());

            // Fallback vers le système de règles en cas d'erreur
            try {
                $fallbackResponse = $this->generateFallbackResponse($userMessage ?? 'bonjour', $chatbot ?? null);

                return response()->json([
                    'success' => true,
                    'response' => $fallbackResponse['content'],
                    'metadata' => $fallbackResponse['metadata'],
                    'suggestions' => $fallbackResponse['suggestions'] ?? [],
                    'timestamp' => now()->toISOString(),
                ]);
            } catch (\Exception $fallbackError) {
                Log::error('Erreur fallback chatbot: '.$fallbackError->getMessage());

                return response()->json([
                    'success' => false,
                    'response' => 'Désolé, une erreur technique est survenue. Veuillez réessayer.',
                    'error' => 'Une erreur est survenue. Veuillez réessayer.',
                ], 500);
            }
        }
    }

    /**
     * Effacer définitivement la conversation
     */
    public function clearConversation(Request $request)
    {
        try {
            $chatbot = Chatbot::getOrCreateSession();

            // Effacer l'historique de conversation
            $chatbot->update([
                'conversation_history' => [],
                'last_intent' => null,
                'user_preferences' => [],
                'last_activity' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Conversation effacée avec succès',
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'effacement de la conversation: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'effacement de la conversation',
            ], 500);
        }
    }

    /**
     * Générer une réponse IA basée sur le message utilisateur
     */
    private function generateAIResponse(string $message, Chatbot $chatbot): array
    {
        $language = isset($chatbot->language) ? $chatbot->language : 'fr';

        // Si Gemini est configuré, l'utiliser en priorité
        if ($this->geminiService->isConfigured()) {
            try {
                $context = $this->buildContext($chatbot);
                $aiResponse = $this->geminiService->generateResponse($message, $language, $context);

                // Vérifier si la réponse n'est pas le message de fallback
                if (! str_contains($aiResponse, 'temporairement indisponible') && $aiResponse !== 'FALLBACK_TO_RULES') {
                    return [
                        'content' => $aiResponse,
                        'metadata' => [
                            'intent' => 'ai_generated',
                            'source' => 'gemini',
                            'language' => $language,
                        ],
                        'suggestions' => $this->geminiService->getSmartSuggestions($language, $context),
                    ];
                }
            } catch (\Exception $e) {
                Log::warning('Gemini service error, falling back to rules: '.$e->getMessage());
            }
        }

        // Fallback vers le système de règles
        return $this->generateFallbackResponse($message, $chatbot);
    }

    /**
     * Construire le contexte pour Gemini
     */
    private function buildContext(Chatbot $chatbot): array
    {
        $context = [
            'language' => isset($chatbot->language) ? $chatbot->language : 'fr',
            'user_authenticated' => $chatbot->user_id ? true : false,
        ];

        if ($chatbot->user_id) {
            $user = $chatbot->user;
            $context['user_name'] = $user->name;
            $context['user_email'] = $user->email;
            $context['reservations_count'] = $user->reservations()->confirmed()->count();
            $context['certificates_count'] = $user->certifications()->count();
        }

        // Ajouter les événements récents
        $recentEvents = Event::published()->upcoming()->take(3)->get();
        $context['recent_events'] = $recentEvents->map(function ($event) {
            return [
                'title' => $event->title,
                'date' => $event->date->format('d/m/Y à H:i'),
                'location' => $event->location,
                'available_seats' => $event->max_participants - $event->reservations()->confirmed()->count(),
            ];
        })->toArray();

        return $context;
    }

    /**
     * Générer une réponse de fallback avec le système de règles
     */
    private function generateFallbackResponse(string $message, ?Chatbot $chatbot = null): array
    {
        $language = 'fr';
        if ($chatbot && isset($chatbot->language)) {
            $language = $chatbot->language;
        }

        $intent = $this->detectIntent($message, $language);

        return match ($intent) {
            'greeting' => $this->handleGreeting($language),
            'thanks' => $this->handleThanks($language),
            'events_list' => $this->handleEventsList($language),
            'event_search' => $this->handleEventSearch($message, $language),
            'reservation_help' => $this->handleReservationHelp($language),
            'certificate_info' => $this->handleCertificateInfo($language),
            'contact_info' => $this->handleContactInfo($language),
            'language_change' => $this->handleLanguageChange($message, $chatbot ?? new Chatbot),
            'user_profile' => $this->handleUserProfile($chatbot ?? new Chatbot, $language),
            'event_details' => $this->handleEventDetails($message, $language),
            'waiting_list' => $this->handleWaitingList($chatbot ?? new Chatbot, $language),
            'points_system' => $this->handlePointsSystem($chatbot ?? new Chatbot, $language),
            'general_info' => $this->handleGeneralInfo($message, $language),
            'default' => $this->handleDefault($message, $language)
        };
    }

    /**
     * Détecter l'intention du message utilisateur
     */
    private function detectIntent(string $message, string $language): string
    {
        $message = strtolower(trim($message));

        // Patterns de détection d'intention
        $patterns = [
            'greeting' => [
                'fr' => ['bonjour', 'salut', 'hello', 'bonsoir', 'coucou', 'hey'],
                'en' => ['hello', 'hi', 'hey', 'good morning', 'good afternoon'],
                'ar' => ['مرحبا', 'أهلا', 'سلام', 'صباح الخير'],
            ],
            'thanks' => [
                'fr' => ['merci', 'merci beaucoup', 'merci bien', 'je vous remercie', 'remerciements', 'gratitude'],
                'en' => ['thanks', 'thank you', 'thank you very much', 'grateful', 'appreciate'],
                'ar' => ['شكرا', 'شكرا لك', 'شكرا جزيلا', 'أشكرك', 'امتنان'],
            ],
            'events_list' => [
                'fr' => ['événements', 'évènements', 'liste', 'programme', 'agenda', 'calendrier', 'quels événements', 'quels evenements', 'montrer les événements', 'montrer les evenements', 'voir les événements', 'voir les evenements', 'événements disponibles', 'evenements disponibles', 'savoir les événements', 'savoir les evenements', 'quels sont les événements', 'quels sont les evenements', 'disponibles', 'disponible'],
                'en' => ['events', 'list', 'schedule', 'calendar', 'program', 'show events', 'what events', 'view events', 'upcoming events', 'available events', 'know events', 'what are the events', 'available'],
                'ar' => ['أحداث', 'قائمة', 'برنامج', 'جدول', 'ما هي الأحداث', 'عرض الأحداث', 'الأحداث القادمة', 'الأحداث المتاحة', 'معرفة الأحداث', 'متاح'],
            ],
            'event_search' => [
                'fr' => ['rechercher', 'chercher', 'trouver', 'où', 'quand', 'quel événement', 'participer', 'événement', 'demain', 'recyclage', 'environnement', 'écologie'],
                'en' => ['search', 'find', 'where', 'when', 'what event', 'participate', 'event', 'tomorrow', 'recycling', 'environment', 'ecology'],
                'ar' => ['بحث', 'أبحث', 'أين', 'متى', 'أي حدث', 'مشاركة', 'حدث', 'غداً', 'إعادة تدوير', 'بيئة', 'بيئية'],
            ],
            'reservation_help' => [
                'fr' => ['réservation', 'réserver', 'inscription', 's\'inscrire', 'participer', 'comment réserver', 'comment reserver', 'réserver un événement', 'reserver un evenement', 'comment je peux réserver', 'comment je peux reserver'],
                'en' => ['reservation', 'book', 'register', 'sign up', 'participate', 'how to book', 'how to reserve', 'book an event', 'how can i book'],
                'ar' => ['حجز', 'تسجيل', 'اشتراك', 'مشاركة', 'كيف أحجز', 'حجز حدث'],
            ],
            'certificate_info' => [
                'fr' => ['certificat', 'certification', 'diplôme', 'attestation'],
                'en' => ['certificate', 'certification', 'diploma', 'attestation'],
                'ar' => ['شهادة', 'شهادات', 'دبلوم', 'إثبات'],
            ],
            'contact_info' => [
                'fr' => ['contact', 'aide', 'support', 'problème', 'question'],
                'en' => ['contact', 'help', 'support', 'problem', 'question'],
                'ar' => ['اتصال', 'مساعدة', 'دعم', 'مشكلة', 'سؤال'],
            ],
            'language_change' => [
                'fr' => ['changer langue', 'français', 'anglais', 'arabe'],
                'en' => ['change language', 'french', 'english', 'arabic'],
                'ar' => ['تغيير اللغة', 'فرنسي', 'إنجليزي', 'عربي'],
            ],
            'user_profile' => [
                'fr' => ['profil', 'mon compte', 'mes réservations', 'mes certificats'],
                'en' => ['profile', 'my account', 'my reservations', 'my certificates'],
                'ar' => ['الملف الشخصي', 'حسابي', 'حجوزاتي', 'شهاداتي'],
            ],
            'event_details' => [
                'fr' => ['détails événement', 'details evenement', 'informations événement', 'informations evenement', 'description événement', 'description evenement', 'lieu événement', 'lieu evenement', 'date événement', 'date evenement', 'heure événement', 'heure evenement', 'prix événement', 'prix evenement', 'places disponibles', 'places disponibles', 'nombre participants', 'nombre participants'],
                'en' => ['event details', 'event information', 'event description', 'event location', 'event date', 'event time', 'event price', 'available seats', 'number of participants'],
                'ar' => ['تفاصيل الحدث', 'معلومات الحدث', 'وصف الحدث', 'مكان الحدث', 'تاريخ الحدث', 'وقت الحدث', 'سعر الحدث', 'المقاعد المتاحة', 'عدد المشاركين'],
            ],
            'waiting_list' => [
                'fr' => ['liste d\'attente', 'liste d attente', 'liste attente', 'inscription liste attente', 'rejoindre liste attente', 'position liste attente', 'quand serai-je promu', 'quand serai je promu', 'promotion liste attente', 'promotion liste attente'],
                'en' => ['waiting list', 'join waiting list', 'waiting list position', 'when will i be promoted', 'waiting list promotion'],
                'ar' => ['قائمة الانتظار', 'الانضمام لقائمة الانتظار', 'موضع قائمة الانتظار', 'متى سأتم ترقيتي', 'ترقية قائمة الانتظار'],
            ],
            'points_system' => [
                'fr' => ['points', 'système points', 'systeme points', 'gagner points', 'gagner des points', 'mes points', 'mes points', 'total points', 'total des points', 'accumuler points', 'accumuler des points', 'récompenses', 'recompenses', 'badges', 'badges'],
                'en' => ['points', 'point system', 'earn points', 'my points', 'total points', 'accumulate points', 'rewards', 'badges'],
                'ar' => ['نقاط', 'نظام النقاط', 'كسب النقاط', 'نقاطي', 'إجمالي النقاط', 'تراكم النقاط', 'مكافآت', 'شارات'],
            ],
            'general_info' => [
                'fr' => ['qu\'est-ce que', 'qu est ce que', 'c\'est quoi', 'c est quoi', 'expliquer', 'expliquer', 'définition', 'definition', 'signification', 'signification', 'à propos de', 'a propos de', 'informations générales', 'informations generales', 'présentation', 'presentation', 'mission', 'mission', 'objectif', 'objectif'],
                'en' => ['what is', 'explain', 'definition', 'meaning', 'about', 'general information', 'presentation', 'mission', 'objective'],
                'ar' => ['ما هو', 'اشرح', 'تعريف', 'معنى', 'حول', 'معلومات عامة', 'عرض', 'مهمة', 'هدف'],
            ],
        ];

        foreach ($patterns as $intent => $languages) {
            if (isset($languages[$language])) {
                foreach ($languages[$language] as $pattern) {
                    if (str_contains($message, $pattern)) {
                        return $intent;
                    }
                }
            }
        }

        return 'default';
    }

    /**
     * Gestionnaires d'intentions
     */
    private function handleGreeting(string $language): array
    {
        $responses = [
            'fr' => "Bonjour ! 👋 Je suis l'assistant virtuel d'EcoEvents. Comment puis-je vous aider aujourd'hui ?",
            'en' => "Hello! 👋 I'm EcoEvents virtual assistant. How can I help you today?",
            'ar' => 'مرحبا! 👋 أنا المساعد الافتراضي لـ EcoEvents. كيف يمكنني مساعدتك اليوم؟',
        ];

        $suggestions = [
            'fr' => ['Voir les événements', 'Aide réservation', 'Mes certificats', 'Changer de langue'],
            'en' => ['View events', 'Reservation help', 'My certificates', 'Change language'],
            'ar' => ['عرض الأحداث', 'مساعدة الحجز', 'شهاداتي', 'تغيير اللغة'],
        ];

        return [
            'content' => $responses[$language],
            'metadata' => ['intent' => 'greeting'],
            'suggestions' => $suggestions[$language],
        ];
    }

    private function handleEventsList(string $language): array
    {
        $events = Event::published()->upcoming()->take(5)->get();

        $responses = [
            'fr' => "Voici les prochains événements disponibles :\n\n",
            'en' => "Here are the upcoming available events:\n\n",
            'ar' => "إليك الأحداث القادمة المتاحة:\n\n",
        ];

        $content = $responses[$language];

        if ($events->count() > 0) {
            foreach ($events as $event) {
                $content .= "🌱 **{$event->title}**\n";
                $content .= '📅 '.$event->date->format('d/m/Y à H:i')."\n";
                $content .= "📍 {$event->location}\n";
                $content .= '👥 Places disponibles: '.($event->max_participants - $event->reservations()->confirmed()->count())."\n\n";
            }
        } else {
            $noEvents = [
                'fr' => 'Aucun événement à venir pour le moment.',
                'en' => 'No upcoming events at the moment.',
                'ar' => 'لا توجد أحداث قادمة في الوقت الحالي.',
            ];
            $content .= $noEvents[$language];
        }

        return [
            'content' => $content,
            'metadata' => ['intent' => 'events_list', 'events_count' => $events->count()],
            'suggestions' => [
                'fr' => ['Comment réserver ?', 'Détails des événements', 'Mes réservations'],
                'en' => ['How to book?', 'Event details', 'My reservations'],
                'ar' => ['كيف أحجز؟', 'تفاصيل الأحداث', 'حجوزاتي'],
            ][$language] ?? ['Comment réserver ?', 'Détails des événements', 'Mes réservations'],
        ];
    }

    private function handleEventSearch(string $message, string $language): array
    {
        $messageLower = strtolower($message);

        // Recherche intelligente avec contexte
        $keywords = explode(' ', $messageLower);
        $events = Event::published()->upcoming()
            ->where(function ($query) use ($keywords) {
                foreach ($keywords as $keyword) {
                    if (strlen($keyword) > 2) {
                        $query->orWhere('title', 'like', "%{$keyword}%")
                            ->orWhere('description', 'like', "%{$keyword}%")
                            ->orWhere('location', 'like', "%{$keyword}%");
                    }
                }
            })
            ->take(5)
            ->get();

        // Messages contextuels selon la demande
        if (str_contains($messageLower, 'demain') || str_contains($messageLower, 'tomorrow')) {
            $responses = [
                'fr' => "Je comprends que vous cherchez un événement pour demain ! 📅\n\n",
                'en' => "I understand you're looking for an event tomorrow! 📅\n\n",
                'ar' => "أفهم أنك تبحث عن حدث غداً! 📅\n\n",
            ];
        } elseif (str_contains($messageLower, 'recyclage') || str_contains($messageLower, 'recycling')) {
            $responses = [
                'fr' => "Excellente idée de participer à un événement sur le recyclage ! ♻️\n\n",
                'en' => "Great idea to participate in a recycling event! ♻️\n\n",
                'ar' => "فكرة ممتازة للمشاركة في حدث حول إعادة التدوير! ♻️\n\n",
            ];
        } else {
            $responses = [
                'fr' => "Résultats de recherche pour '{$message}' :\n\n",
                'en' => "Search results for '{$message}':\n\n",
                'ar' => "نتائج البحث عن '{$message}':\n\n",
            ];
        }

        $content = $responses[$language];

        if ($events->count() > 0) {
            $content .= "Voici les événements correspondants :\n\n";
            foreach ($events as $event) {
                $availableSeats = $event->max_participants - $event->reservations()->confirmed()->count();
                $content .= "🌱 **{$event->title}**\n";
                $content .= '📅 '.$event->date->format('d/m/Y à H:i')."\n";
                $content .= "📍 {$event->location}\n";
                $content .= "👥 Places disponibles: {$availableSeats}\n\n";
            }

            $content .= "💡 **Conseil** : Cliquez sur 'Explorer les événements' pour voir tous les événements disponibles et réserver votre place !";
        } else {
            $noResults = [
                'fr' => "Malheureusement, je ne trouve pas d'événement correspondant à votre recherche. 😔\n\n**Suggestions :**\n• Consultez tous les événements disponibles\n• Essayez avec d'autres mots-clés\n• Contactez-nous pour des événements spécifiques\n\n💡 **Astuce** : Vous pouvez aussi créer votre propre événement sur le recyclage !",
                'en' => "Unfortunately, I can't find an event matching your search. 😔\n\n**Suggestions:**\n• Browse all available events\n• Try different keywords\n• Contact us for specific events\n\n💡 **Tip**: You can also create your own recycling event!",
                'ar' => "للأسف، لا أستطيع العثور على حدث يطابق بحثك. 😔\n\n**اقتراحات:**\n• تصفح جميع الأحداث المتاحة\n• جرب كلمات مفتاحية مختلفة\n• اتصل بنا لأحداث محددة\n\n💡 **نصيحة**: يمكنك أيضاً إنشاء حدثك الخاص حول إعادة التدوير!",
            ];
            $content .= $noResults[$language];
        }

        return [
            'content' => $content,
            'metadata' => ['intent' => 'event_search', 'search_term' => $message, 'events_found' => $events->count()],
        ];
    }

    private function handleReservationHelp(string $language): array
    {
        $user = auth()->user();
        $userReservations = $user ? $user->reservations()->confirmed()->count() : 0;

        $responses = [
            'fr' => [
                'content' => "🎫 **Guide Complet de Réservation EcoEvents**\n\n".
                           "**📋 Étapes de Réservation :**\n".
                           "1️⃣ **Consultez les événements** : Parcourez notre liste d'événements disponibles\n".
                           "2️⃣ **Choisissez votre événement** : Cliquez sur l'événement qui vous intéresse\n".
                           "3️⃣ **Sélectionnez votre place** : Choisissez votre siège sur le plan interactif\n".
                           "4️⃣ **Confirmez votre réservation** : Validez vos informations et confirmez\n".
                           "5️⃣ **Recevez votre confirmation** : Email de confirmation automatique\n\n".
                           "**✨ Fonctionnalités Avancées :**\n".
                           "• 🔒 **Verrouillage temporaire** : Votre place est réservée pendant 10 minutes\n".
                           "• ⏳ **Liste d'attente** : Rejoignez automatiquement si complet\n".
                           "• 📧 **Notifications** : Alertes par email pour les mises à jour\n".
                           "• 🎯 **Réservation rapide** : Processus optimisé en 2 minutes\n\n".
                           "**📊 Vos réservations actuelles :** {$userReservations} événement(s)\n\n".
                           "**💡 Conseils Pro :**\n".
                           "• Réservez tôt pour garantir votre place\n".
                           "• Vérifiez vos emails régulièrement\n".
                           "• Annulez 24h avant si nécessaire\n\n".
                           "**❓ Besoin d'aide ?** Contactez notre support !",
                'metadata' => ['intent' => 'reservation_help', 'source' => 'rules'],
                'suggestions' => ['Voir les événements', 'Mes réservations', 'Liste d\'attente', 'Support'],
            ],
            'en' => [
                'content' => "🎫 **Complete EcoEvents Booking Guide**\n\n".
                           "**📋 Booking Steps:**\n".
                           "1️⃣ **Browse events** : Check our list of available events\n".
                           "2️⃣ **Choose your event** : Click on the event that interests you\n".
                           "3️⃣ **Select your seat** : Choose your seat on the interactive map\n".
                           "4️⃣ **Confirm your booking** : Validate your information and confirm\n".
                           "5️⃣ **Receive confirmation** : Automatic confirmation email\n\n".
                           "**✨ Advanced Features:**\n".
                           "• 🔒 **Temporary lock** : Your seat is reserved for 10 minutes\n".
                           "• ⏳ **Waiting list** : Automatically join if full\n".
                           "• 📧 **Notifications** : Email alerts for updates\n".
                           "• 🎯 **Quick booking** : Optimized process in 2 minutes\n\n".
                           "**📊 Your current bookings:** {$userReservations} event(s)\n\n".
                           "**💡 Pro Tips:**\n".
                           "• Book early to guarantee your spot\n".
                           "• Check your emails regularly\n".
                           "• Cancel 24h before if needed\n\n".
                           '**❓ Need help?** Contact our support!',
                'metadata' => ['intent' => 'reservation_help', 'source' => 'rules'],
                'suggestions' => ['View events', 'My reservations', 'Waiting list', 'Support'],
            ],
            'ar' => [
                'content' => "🎫 **دليل الحجز الشامل لـ EcoEvents**\n\n".
                           "**📋 خطوات الحجز:**\n".
                           "1️⃣ **تصفح الأحداث** : راجع قائمة الأحداث المتاحة\n".
                           "2️⃣ **اختر الحدث** : انقر على الحدث الذي يهمك\n".
                           "3️⃣ **اختر مقعدك** : اختر مقعدك على الخريطة التفاعلية\n".
                           "4️⃣ **أكد حجزك** : تحقق من معلوماتك وأكد\n".
                           "5️⃣ **استلم التأكيد** : بريد إلكتروني تأكيدي تلقائي\n\n".
                           "**✨ ميزات متقدمة:**\n".
                           "• 🔒 **قفل مؤقت** : مقعدك محجوز لمدة 10 دقائق\n".
                           "• ⏳ **قائمة الانتظار** : انضم تلقائياً إذا كان ممتلئ\n".
                           "• 📧 **إشعارات** : تنبيهات بريد إلكتروني للتحديثات\n".
                           "• 🎯 **حجز سريع** : عملية محسنة في دقيقتين\n\n".
                           "**📊 حجوزاتك الحالية:** {$userReservations} حدث\n\n".
                           "**💡 نصائح محترفة:**\n".
                           "• احجز مبكراً لضمان مكانك\n".
                           "• تحقق من بريدك الإلكتروني بانتظام\n".
                           "• ألغِ قبل 24 ساعة إذا لزم الأمر\n\n".
                           '**❓ تحتاج مساعدة؟** اتصل بدعمنا!',
                'metadata' => ['intent' => 'reservation_help', 'source' => 'rules'],
                'suggestions' => ['عرض الأحداث', 'حجوزاتي', 'قائمة الانتظار', 'الدعم'],
            ],
        ];

        return $responses[$language] ?? $responses['fr'];
    }

    private function handleCertificateInfo(string $language): array
    {
        $user = auth()->user();
        $userCertificates = $user ? $user->certifications()->count() : 0;
        $userPoints = $user ? $user->certifications()->sum('points_earned') : 0;

        $responses = [
            'fr' => [
                'content' => "📜 **Système de Certificats EcoEvents**\n\n".
                           "**🎓 Comment Obtenir un Certificat :**\n".
                           "1️⃣ **Participez à un événement** : Assistez à l'événement complet\n".
                           "2️⃣ **Attendez la validation** : L'admin valide votre participation\n".
                           "3️⃣ **Recevez votre certificat** : Email automatique avec votre certificat\n".
                           "4️⃣ **Téléchargez le PDF** : Certificat professionnel au format PDF\n\n".
                           "**✨ Fonctionnalités des Certificats :**\n".
                           "• 🔍 **Code de vérification unique** : Chaque certificat a un code unique\n".
                           "• 📄 **Format PDF professionnel** : Design élégant et moderne\n".
                           "• 🏆 **Points gagnés** : 10 points par événement + 5 bonus certificat\n".
                           "• 📧 **Email automatique** : Notification immédiate de disponibilité\n".
                           "• 🔒 **Sécurité** : Vérification en ligne possible\n\n".
                           "**📊 Vos Certificats :**\n".
                           "• Nombre de certificats : {$userCertificates}\n".
                           "• Points totaux : {$userPoints} points\n\n".
                           "**💡 Utilisation des Certificats :**\n".
                           "• Ajoutez-les à votre CV/LinkedIn\n".
                           "• Partagez-les sur les réseaux sociaux\n".
                           "• Utilisez-les pour vos formations\n".
                           "• Vérifiez leur authenticité en ligne\n\n".
                           '**❓ Problème avec un certificat ?** Contactez notre support !',
                'metadata' => ['intent' => 'certificate_info', 'source' => 'rules'],
                'suggestions' => ['Mes certificats', 'Vérifier certificat', 'Système de points', 'Mon profil'],
            ],
            'en' => [
                'content' => "📜 **EcoEvents Certificate System**\n\n".
                           "**🎓 How to Get a Certificate:**\n".
                           "1️⃣ **Attend an event** : Complete participation in the event\n".
                           "2️⃣ **Wait for validation** : Admin validates your participation\n".
                           "3️⃣ **Receive your certificate** : Automatic email with your certificate\n".
                           "4️⃣ **Download the PDF** : Professional certificate in PDF format\n\n".
                           "**✨ Certificate Features:**\n".
                           "• 🔍 **Unique verification code** : Each certificate has a unique code\n".
                           "• 📄 **Professional PDF format** : Elegant and modern design\n".
                           "• 🏆 **Points earned** : 10 points per event + 5 bonus certificate\n".
                           "• 📧 **Automatic email** : Immediate availability notification\n".
                           "• 🔒 **Security** : Online verification possible\n\n".
                           "**📊 Your Certificates:**\n".
                           "• Number of certificates : {$userCertificates}\n".
                           "• Total points : {$userPoints} points\n\n".
                           "**💡 Using Certificates:**\n".
                           "• Add them to your CV/LinkedIn\n".
                           "• Share them on social networks\n".
                           "• Use them for your training\n".
                           "• Verify their authenticity online\n\n".
                           '**❓ Problem with a certificate?** Contact our support!',
                'metadata' => ['intent' => 'certificate_info', 'source' => 'rules'],
                'suggestions' => ['My certificates', 'Verify certificate', 'Points system', 'My profile'],
            ],
            'ar' => [
                'content' => "📜 **نظام شهادات EcoEvents**\n\n".
                           "**🎓 كيفية الحصول على شهادة:**\n".
                           "1️⃣ **شارك في حدث** : مشاركة كاملة في الحدث\n".
                           "2️⃣ **انتظر التحقق** : المدير يتحقق من مشاركتك\n".
                           "3️⃣ **استلم شهادتك** : بريد إلكتروني تلقائي مع شهادتك\n".
                           "4️⃣ **حمل ملف PDF** : شهادة مهنية بصيغة PDF\n\n".
                           "**✨ ميزات الشهادات:**\n".
                           "• 🔍 **رمز تحقق فريد** : كل شهادة لها رمز فريد\n".
                           "• 📄 **صيغة PDF مهنية** : تصميم أنيق وحديث\n".
                           "• 🏆 **نقاط مكتسبة** : 10 نقاط لكل حدث + 5 نقاط إضافية للشهادة\n".
                           "• 📧 **بريد إلكتروني تلقائي** : إشعار فوري بالتوفر\n".
                           "• 🔒 **أمان** : إمكانية التحقق عبر الإنترنت\n\n".
                           "**📊 شهاداتك:**\n".
                           "• عدد الشهادات : {$userCertificates}\n".
                           "• إجمالي النقاط : {$userPoints} نقطة\n\n".
                           "**💡 استخدام الشهادات:**\n".
                           "• أضفها إلى سيرتك الذاتية/LinkedIn\n".
                           "• شاركها على الشبكات الاجتماعية\n".
                           "• استخدمها لتدريبك\n".
                           "• تحقق من صحتها عبر الإنترنت\n\n".
                           '**❓ مشكلة مع شهادة؟** اتصل بدعمنا!',
                'metadata' => ['intent' => 'certificate_info', 'source' => 'rules'],
                'suggestions' => ['شهاداتي', 'تحقق من الشهادة', 'نظام النقاط', 'ملفي الشخصي'],
            ],
        ];

        return $responses[$language] ?? $responses['fr'];
    }

    private function handleContactInfo(string $language): array
    {
        $responses = [
            'fr' => "📞 **Contact EcoEvents**\n\n📧 Email: contact@ecoevents.tn\n🌐 Site web: www.ecoevents.tn\n📱 Réseaux sociaux: @EcoEventsTN\n\n⏰ Support disponible 24/7 via ce chatbot !",
            'en' => "📞 **Contact EcoEvents**\n\n📧 Email: contact@ecoevents.tn\n🌐 Website: www.ecoevents.tn\n📱 Social media: @EcoEventsTN\n\n⏰ Support available 24/7 via this chatbot!",
            'ar' => "📞 **اتصال EcoEvents**\n\n📧 البريد الإلكتروني: contact@ecoevents.tn\n🌐 الموقع: www.ecoevents.tn\n📱 وسائل التواصل: @EcoEventsTN\n\n⏰ الدعم متاح 24/7 عبر هذا المساعد!",
        ];

        return [
            'content' => $responses[$language],
            'metadata' => ['intent' => 'contact_info'],
        ];
    }

    private function handleLanguageChange(string $message, Chatbot $chatbot): array
    {
        $message = strtolower($message);

        if (str_contains($message, 'anglais') || str_contains($message, 'english')) {
            $chatbot->update(['language' => 'en']);

            return [
                'content' => 'Language changed to English! 🇺🇸 How can I help you?',
                'metadata' => ['intent' => 'language_change', 'new_language' => 'en'],
            ];
        } elseif (str_contains($message, 'arabe') || str_contains($message, 'arabic')) {
            $chatbot->update(['language' => 'ar']);

            return [
                'content' => 'تم تغيير اللغة إلى العربية! 🇹🇳 كيف يمكنني مساعدتك؟',
                'metadata' => ['intent' => 'language_change', 'new_language' => 'ar'],
            ];
        } else {
            $chatbot->update(['language' => 'fr']);

            return [
                'content' => 'Langue changée en français ! 🇫🇷 Comment puis-je vous aider ?',
                'metadata' => ['intent' => 'language_change', 'new_language' => 'fr'],
            ];
        }
    }

    private function handleUserProfile(Chatbot $chatbot, string $language): array
    {
        if (! $chatbot->user_id) {
            $responses = [
                'fr' => "Pour accéder à votre profil, veuillez vous connecter d'abord.",
                'en' => 'To access your profile, please log in first.',
                'ar' => 'للوصول إلى ملفك الشخصي، يرجى تسجيل الدخول أولاً.',
            ];

            return [
                'content' => $responses[$language],
                'metadata' => ['intent' => 'user_profile', 'requires_auth' => true],
            ];
        }

        $user = $chatbot->user;
        $reservationsCount = $user->reservations()->confirmed()->count();
        $certificatesCount = $user->certifications()->count();

        $responses = [
            'fr' => "👤 **Votre Profil EcoEvents**\n\n📝 Nom: {$user->name}\n📧 Email: {$user->email}\n🎫 Réservations: {$reservationsCount}\n🏆 Certificats: {$certificatesCount}\n\n💡 Consultez votre profil complet pour plus de détails !",
            'en' => "👤 **Your EcoEvents Profile**\n\n📝 Name: {$user->name}\n📧 Email: {$user->email}\n🎫 Reservations: {$reservationsCount}\n🏆 Certificates: {$certificatesCount}\n\n💡 Check your complete profile for more details!",
            'ar' => "👤 **ملفك الشخصي EcoEvents**\n\n📝 الاسم: {$user->name}\n📧 البريد الإلكتروني: {$user->email}\n🎫 الحجوزات: {$reservationsCount}\n🏆 الشهادات: {$certificatesCount}\n\n💡 تحقق من ملفك الشخصي الكامل للمزيد من التفاصيل!",
        ];

        return [
            'content' => $responses[$language],
            'metadata' => ['intent' => 'user_profile', 'user_id' => $user->id],
        ];
    }

    private function handleDefault(string $message, string $language): array
    {
        $messageLower = strtolower($message);

        // Détection contextuelle améliorée
        if (str_contains($messageLower, 'participer') || str_contains($messageLower, 'participate')) {
            $responses = [
                'fr' => "Je comprends que vous voulez participer à un événement ! 🎯\n\n**Voici comment procéder :**\n• Consultez la liste des événements disponibles\n• Choisissez celui qui vous intéresse\n• Cliquez sur 'Réserver' pour sélectionner votre place\n• Confirmez votre participation\n\n💡 **Astuce** : Les réservations sont confirmées automatiquement !",
                'en' => "I understand you want to participate in an event! 🎯\n\n**Here's how to proceed:**\n• Browse the list of available events\n• Choose the one that interests you\n• Click 'Reserve' to select your seat\n• Confirm your participation\n\n💡 **Tip**: Reservations are automatically confirmed!",
                'ar' => "أفهم أنك تريد المشاركة في حدث! 🎯\n\n**إليك كيفية المتابعة:**\n• تصفح قائمة الأحداث المتاحة\n• اختر الحدث الذي يهمك\n• انقر على 'حجز' لاختيار مقعدك\n• أكد مشاركتك\n\n💡 **نصيحة**: الحجوزات مؤكدة تلقائياً!",
            ];
        } elseif (str_contains($messageLower, 'demain') || str_contains($messageLower, 'tomorrow')) {
            $responses = [
                'fr' => "Vous cherchez un événement pour demain ? 📅\n\n**Malheureusement**, je ne vois pas d'événement programmé pour demain dans notre calendrier actuel.\n\n**Suggestions :**\n• Consultez tous les événements à venir\n• Inscrivez-vous à notre newsletter pour être notifié des nouveaux événements\n• Contactez-nous si vous souhaitez organiser un événement\n\n💡 **Astuce** : Vous pouvez créer votre propre événement !",
                'en' => "Looking for an event tomorrow? 📅\n\n**Unfortunately**, I don't see any event scheduled for tomorrow in our current calendar.\n\n**Suggestions:**\n• Browse all upcoming events\n• Subscribe to our newsletter to be notified of new events\n• Contact us if you'd like to organize an event\n\n💡 **Tip**: You can create your own event!",
                'ar' => "تبحث عن حدث غداً؟ 📅\n\n**للأسف**، لا أرى حدثاً مجدولاً لغد في تقويمنا الحالي.\n\n**اقتراحات:**\n• تصفح جميع الأحداث القادمة\n• اشترك في نشرتنا الإخبارية للإشعار بالأحداث الجديدة\n• اتصل بنا إذا كنت تريد تنظيم حدث\n\n💡 **نصيحة**: يمكنك إنشاء حدثك الخاص!",
            ];
        } else {
            $responses = [
                'fr' => "Je ne suis pas sûr de comprendre votre demande. 🤔\n\n**Voici ce que je peux vous aider :**\n• Voir les événements disponibles\n• Aide pour les réservations\n• Informations sur les certificats\n• Changer de langue\n• Support général\n\n**Pouvez-vous reformuler votre question ?** Ou utilisez les suggestions ci-dessous !",
                'en' => "I'm not sure I understand your request. 🤔\n\n**Here's what I can help you with:**\n• View available events\n• Reservation help\n• Certificate information\n• Change language\n• General support\n\n**Can you rephrase your question?** Or use the suggestions below!",
                'ar' => "لست متأكداً من فهم طلبك. 🤔\n\n**إليك ما يمكنني مساعدتك فيه:**\n• عرض الأحداث المتاحة\n• مساعدة الحجز\n• معلومات الشهادات\n• تغيير اللغة\n• دعم عام\n\n**هل يمكنك إعادة صياغة سؤالك؟** أو استخدم الاقتراحات أدناه!",
            ];
        }

        $suggestions = [
            'fr' => ['Voir les événements', 'Aide réservation', 'Mes certificats'],
            'en' => ['View events', 'Reservation help', 'My certificates'],
            'ar' => ['عرض الأحداث', 'مساعدة الحجز', 'شهاداتي'],
        ];

        return [
            'content' => $responses[$language],
            'metadata' => ['intent' => 'default', 'unrecognized_message' => $message],
            'suggestions' => $suggestions[$language],
        ];
    }

    private function getErrorMessage(string $language): string
    {
        $messages = [
            'fr' => 'Désolé, une erreur technique est survenue. Veuillez réessayer dans quelques instants.',
            'en' => 'Sorry, a technical error occurred. Please try again in a few moments.',
            'ar' => 'عذراً، حدث خطأ تقني. يرجى المحاولة مرة أخرى بعد قليل.',
        ];

        return $messages[$language] ?? $messages['fr'];
    }

    /**
     * Obtenir les suggestions de conversation
     */
    public function getSuggestions(Request $request): JsonResponse
    {
        $language = $request->get('language', 'fr');

        $suggestions = [
            'fr' => [
                'Voir les événements disponibles',
                'Comment réserver un événement ?',
                'Informations sur les certificats',
                'Détails des événements',
                'Système de points',
                'Liste d\'attente',
                'À propos d\'EcoEvents',
                'Changer la langue en anglais',
                'Mon profil utilisateur',
            ],
            'en' => [
                'View available events',
                'How to book an event?',
                'Certificate information',
                'Event details',
                'Points system',
                'Waiting list',
                'About EcoEvents',
                'Change language to French',
                'My user profile',
            ],
            'ar' => [
                'عرض الأحداث المتاحة',
                'كيفية حجز حدث؟',
                'معلومات الشهادات',
                'تفاصيل الأحداث',
                'نظام النقاط',
                'قائمة الانتظار',
                'حول EcoEvents',
                'تغيير اللغة إلى الفرنسية',
                'ملفي الشخصي',
            ],
        ];

        return response()->json([
            'suggestions' => $suggestions[$language] ?? $suggestions['fr'],
        ]);
    }

    /**
     * Gérer les remerciements
     */
    private function handleThanks(string $language): array
    {
        $responses = [
            'fr' => [
                'content' => "De rien ! 😊 C'est un plaisir de vous aider !\n\n".
                           "N'hésitez pas si vous avez d'autres questions. Je suis là pour vous accompagner dans votre parcours écologique ! 🌱",
                'metadata' => ['intent' => 'thanks', 'source' => 'rules'],
                'suggestions' => ['Voir les événements', 'Comment réserver ?', 'Mes certificats'],
            ],
            'en' => [
                'content' => "You're welcome! 😊 It's a pleasure to help you!\n\n".
                           "Don't hesitate if you have other questions. I'm here to accompany you in your ecological journey! 🌱",
                'metadata' => ['intent' => 'thanks', 'source' => 'rules'],
                'suggestions' => ['View events', 'How to book?', 'My certificates'],
            ],
            'ar' => [
                'content' => "عفواً! 😊 إنه لمن دواعي سروري مساعدتك!\n\n".
                           'لا تتردد إذا كان لديك أسئلة أخرى. أنا هنا لمرافقتك في رحلتك البيئية! 🌱',
                'metadata' => ['intent' => 'thanks', 'source' => 'rules'],
                'suggestions' => ['عرض الأحداث', 'كيف أحجز؟', 'شهاداتي'],
            ],
        ];

        return $responses[$language] ?? $responses['fr'];
    }

    /**
     * Gérer les questions sur les détails d'événements
     */
    private function handleEventDetails(string $message, string $language): array
    {
        $responses = [
            'fr' => [
                'content' => "📋 **Détails des Événements EcoEvents**\n\n".
                           "Chaque événement comprend :\n".
                           "• **Titre** : Nom de l'événement\n".
                           "• **Date & Heure** : Quand se déroule l'événement\n".
                           "• **Lieu** : Où se passe l'événement\n".
                           "• **Description** : Contenu et objectifs\n".
                           "• **Places disponibles** : Nombre de participants\n".
                           "• **Points gagnés** : Récompenses pour participation\n\n".
                           '💡 **Conseil** : Cliquez sur un événement pour voir tous les détails !',
                'metadata' => ['intent' => 'event_details', 'source' => 'rules'],
                'suggestions' => ['Voir les événements', 'Comment réserver ?', 'Mes certificats'],
            ],
            'en' => [
                'content' => "📋 **EcoEvents Event Details**\n\n".
                           "Each event includes:\n".
                           "• **Title** : Event name\n".
                           "• **Date & Time** : When the event takes place\n".
                           "• **Location** : Where the event happens\n".
                           "• **Description** : Content and objectives\n".
                           "• **Available seats** : Number of participants\n".
                           "• **Points earned** : Rewards for participation\n\n".
                           '💡 **Tip** : Click on an event to see all details!',
                'metadata' => ['intent' => 'event_details', 'source' => 'rules'],
                'suggestions' => ['View events', 'How to book?', 'My certificates'],
            ],
            'ar' => [
                'content' => "📋 **تفاصيل أحداث EcoEvents**\n\n".
                           "كل حدث يتضمن:\n".
                           "• **العنوان** : اسم الحدث\n".
                           "• **التاريخ والوقت** : متى يقام الحدث\n".
                           "• **المكان** : أين يقام الحدث\n".
                           "• **الوصف** : المحتوى والأهداف\n".
                           "• **المقاعد المتاحة** : عدد المشاركين\n".
                           "• **النقاط المكتسبة** : مكافآت المشاركة\n\n".
                           '💡 **نصيحة** : انقر على حدث لرؤية جميع التفاصيل!',
                'metadata' => ['intent' => 'event_details', 'source' => 'rules'],
                'suggestions' => ['عرض الأحداث', 'كيف أحجز؟', 'شهاداتي'],
            ],
        ];

        return $responses[$language] ?? $responses['fr'];
    }

    /**
     * Gérer les questions sur la liste d'attente
     */
    private function handleWaitingList(Chatbot $chatbot, string $language): array
    {
        $user = auth()->user();
        $waitingListInfo = '';

        if ($user) {
            $waitingLists = $user->waitingLists()->with('event')->get();
            if ($waitingLists->count() > 0) {
                $waitingListInfo = "\n\n📋 **Vos listes d'attente :**\n";
                foreach ($waitingLists as $waiting) {
                    $position = $waiting->getUserPosition();
                    $waitingListInfo .= "• {$waiting->event->title} - Position #{$position}\n";
                }
            }
        }

        $responses = [
            'fr' => [
                'content' => "⏳ **Système de Liste d'Attente EcoEvents**\n\n".
                           "Quand un événement est complet :\n".
                           "• Vous pouvez rejoindre la liste d'attente\n".
                           "• Vous serez notifié si une place se libère\n".
                           "• Promotion automatique selon l'ordre d'inscription\n".
                           "• Vous avez 24h pour confirmer votre place\n\n".
                           "💡 **Avantage** : Ne ratez aucune opportunité !{$waitingListInfo}",
                'metadata' => ['intent' => 'waiting_list', 'source' => 'rules'],
                'suggestions' => ['Voir les événements', 'Mes réservations', 'Comment réserver ?'],
            ],
            'en' => [
                'content' => "⏳ **EcoEvents Waiting List System**\n\n".
                           "When an event is full:\n".
                           "• You can join the waiting list\n".
                           "• You'll be notified if a spot opens\n".
                           "• Automatic promotion by registration order\n".
                           "• You have 24h to confirm your spot\n\n".
                           "💡 **Advantage** : Don't miss any opportunity!{$waitingListInfo}",
                'metadata' => ['intent' => 'waiting_list', 'source' => 'rules'],
                'suggestions' => ['View events', 'My reservations', 'How to book?'],
            ],
            'ar' => [
                'content' => "⏳ **نظام قائمة الانتظار EcoEvents**\n\n".
                           "عندما يكون الحدث ممتلئ:\n".
                           "• يمكنك الانضمام لقائمة الانتظار\n".
                           "• ستتم إشعارك إذا توفر مكان\n".
                           "• ترقية تلقائية حسب ترتيب التسجيل\n".
                           "• لديك 24 ساعة لتأكيد مكانك\n\n".
                           "💡 **ميزة** : لا تفوت أي فرصة!{$waitingListInfo}",
                'metadata' => ['intent' => 'waiting_list', 'source' => 'rules'],
                'suggestions' => ['عرض الأحداث', 'حجوزاتي', 'كيف أحجز؟'],
            ],
        ];

        return $responses[$language] ?? $responses['fr'];
    }

    /**
     * Gérer les questions sur le système de points
     */
    private function handlePointsSystem(Chatbot $chatbot, string $language): array
    {
        $user = auth()->user();
        $userPoints = $user ? $user->certifications()->sum('points_earned') : 0;

        $responses = [
            'fr' => [
                'content' => "⭐ **Système de Points EcoEvents**\n\n".
                           "**Comment gagner des points :**\n".
                           "• Participation à un événement : 10 points\n".
                           "• Certificat obtenu : 5 points bonus\n".
                           "• Invitation d'amis : 2 points par ami\n\n".
                           "**Vos points actuels :** {$userPoints} points\n\n".
                           "**Récompenses disponibles :**\n".
                           "• 50 points : Badge Bronze 🌉\n".
                           "• 100 points : Badge Argent 🥈\n".
                           "• 200 points : Badge Or 🥇\n\n".
                           '💡 **Continuez à participer pour débloquer plus de récompenses !**',
                'metadata' => ['intent' => 'points_system', 'source' => 'rules'],
                'suggestions' => ['Mes certificats', 'Voir les événements', 'Mon profil'],
            ],
            'en' => [
                'content' => "⭐ **EcoEvents Points System**\n\n".
                           "**How to earn points:**\n".
                           "• Event participation : 10 points\n".
                           "• Certificate obtained : 5 bonus points\n".
                           "• Inviting friends : 2 points per friend\n\n".
                           "**Your current points:** {$userPoints} points\n\n".
                           "**Available rewards:**\n".
                           "• 50 points : Bronze Badge 🌉\n".
                           "• 100 points : Silver Badge 🥈\n".
                           "• 200 points : Gold Badge 🥇\n\n".
                           '💡 **Keep participating to unlock more rewards!**',
                'metadata' => ['intent' => 'points_system', 'source' => 'rules'],
                'suggestions' => ['My certificates', 'View events', 'My profile'],
            ],
            'ar' => [
                'content' => "⭐ **نظام النقاط EcoEvents**\n\n".
                           "**كيفية كسب النقاط:**\n".
                           "• المشاركة في حدث : 10 نقاط\n".
                           "• الحصول على شهادة : 5 نقاط إضافية\n".
                           "• دعوة الأصدقاء : نقطتان لكل صديق\n\n".
                           "**نقاطك الحالية:** {$userPoints} نقطة\n\n".
                           "**المكافآت المتاحة:**\n".
                           "• 50 نقطة : شارة برونزية 🌉\n".
                           "• 100 نقطة : شارة فضية 🥈\n".
                           "• 200 نقطة : شارة ذهبية 🥇\n\n".
                           '💡 **استمر في المشاركة لفتح المزيد من المكافآت!**',
                'metadata' => ['intent' => 'points_system', 'source' => 'rules'],
                'suggestions' => ['شهاداتي', 'عرض الأحداث', 'ملفي الشخصي'],
            ],
        ];

        return $responses[$language] ?? $responses['fr'];
    }

    /**
     * Gérer les questions générales sur EcoEvents
     */
    private function handleGeneralInfo(string $message, string $language): array
    {
        $responses = [
            'fr' => [
                'content' => "🌱 **À propos d'EcoEvents**\n\n".
                           "**Notre Mission :**\n".
                           "Promouvoir l'écologie et le développement durable à travers des événements éducatifs et engageants.\n\n".
                           "**Nos Valeurs :**\n".
                           "• 🌍 Protection de l'environnement\n".
                           "• 📚 Éducation écologique\n".
                           "• 🤝 Communauté engagée\n".
                           "• ⭐ Reconnaissance des efforts\n\n".
                           "**Nos Objectifs :**\n".
                           "• Sensibiliser aux enjeux environnementaux\n".
                           "• Créer une communauté éco-responsable\n".
                           "• Récompenser l'engagement écologique\n\n".
                           '💡 **Rejoignez-nous pour un avenir plus vert !**',
                'metadata' => ['intent' => 'general_info', 'source' => 'rules'],
                'suggestions' => ['Voir les événements', 'Comment participer ?', 'Notre mission'],
            ],
            'en' => [
                'content' => "🌱 **About EcoEvents**\n\n".
                           "**Our Mission:**\n".
                           "Promote ecology and sustainable development through educational and engaging events.\n\n".
                           "**Our Values:**\n".
                           "• 🌍 Environmental protection\n".
                           "• 📚 Ecological education\n".
                           "• 🤝 Engaged community\n".
                           "• ⭐ Effort recognition\n\n".
                           "**Our Goals:**\n".
                           "• Raise awareness about environmental issues\n".
                           "• Create an eco-responsible community\n".
                           "• Reward ecological engagement\n\n".
                           '💡 **Join us for a greener future!**',
                'metadata' => ['intent' => 'general_info', 'source' => 'rules'],
                'suggestions' => ['View events', 'How to participate?', 'Our mission'],
            ],
            'ar' => [
                'content' => "🌱 **حول EcoEvents**\n\n".
                           "**مهمتنا:**\n".
                           "تعزيز البيئة والتنمية المستدامة من خلال الأحداث التعليمية والمشاركة.\n\n".
                           "**قيمنا:**\n".
                           "• 🌍 حماية البيئة\n".
                           "• 📚 التعليم البيئي\n".
                           "• 🤝 مجتمع ملتزم\n".
                           "• ⭐ الاعتراف بالجهود\n\n".
                           "**أهدافنا:**\n".
                           "• رفع الوعي بالقضايا البيئية\n".
                           "• إنشاء مجتمع مسؤول بيئياً\n".
                           "• مكافأة الالتزام البيئي\n\n".
                           '💡 **انضم إلينا من أجل مستقبل أكثر خضرة!**',
                'metadata' => ['intent' => 'general_info', 'source' => 'rules'],
                'suggestions' => ['عرض الأحداث', 'كيف أشارك؟', 'مهمتنا'],
            ],
        ];

        return $responses[$language] ?? $responses['fr'];
    }
}
