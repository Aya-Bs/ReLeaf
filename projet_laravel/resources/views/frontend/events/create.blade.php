@extends('layouts.frontend')
@extends('layouts.frontend')

@section('title', 'Créer un Événement')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div>
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-4">
                        <i class="fas fa-calendar-plus me-2" style="color: #2d5a27;"></i><strong>Créer un nouvel événement</strong>
                    </h4>
                    <!-- Breadcrumb path on top right -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-4" style="background: transparent; padding: 0; margin: 0;">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: #2d5a27;">Accueil</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('events.my-events') }}" style="color: #2d5a27;">Événements</a></li>
                            <li class="breadcrumb-item active" aria-current="page" style="color: #2d5a27;"><strong>Créer</strong></li>
                        </ol>
                    </nav>
                    <!-- Breadcrumb path on top right -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-4" style="background: transparent; padding: 0; margin: 0;">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: #2d5a27;">Accueil</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('events.my-events') }}" style="color: #2d5a27;">Événements</a></li>
                            <li class="breadcrumb-item active" aria-current="page" style="color: #2d5a27;"><strong>Créer</strong></li>
                        </ol>
                    </nav>
                </div>
                <div class="card-body">
                    @if(session('error'))
                    <div class="alert alert-danger">
                        <strong>Erreur :</strong> {{ session('error') }}
                    </div>
                    @endif
                    @if(session('debug'))
                    <div class="alert alert-warning">
                        <strong>Debug :</strong> {{ session('debug') }}
                    </div>
                    @endif

                    <!-- VOICE RECOGNITION & DROPDOWN VIEWER SECTION -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="d-flex gap-3 align-items-center flex-wrap">
                                <!-- Voice Recognition Button -->
                                <button type="button" id="voiceControlBtn" class="btn btn-outline-eco">
                                    <i class="fas fa-microphone me-2"></i>
                                    <span id="voiceStatus">Activer la Reconnaissance Vocale</span>
                                </button>

                                <!-- Dropdown Viewer Button -->
                                <button type="button" id="dropdownViewerBtn" class="btn btn-outline-info">
                                    <i class="fas fa-list me-2"></i>
                                    <span id="dropdownViewerText">Voir les Options Disponibles</span>
                                </button>

                                <!-- Voice Feedback -->
                                <div id="voiceFeedback" class="alert alert-info d-none align-items-center mb-0" style="flex: 1;">
                                    <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                                    <span id="feedbackText">En attente de commande vocale...</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- VOICE OPTIONS LISTS (Hidden by default) -->
                    <div id="voiceOptionsContainer" class="row mb-4" style="display: none;">
                        <div class="col-md-4">
                            <div class="card voiceOptionsCardXYZ">
                                <div class="card-header voiceOptionsHeader789">
                                    <h6 class="mb-0"><i class="fas fa-clock me-2"></i>Durées Disponibles</h6>
                                </div>
                                <div class="card-body voiceOptionsBodyDEF">
                                    <ul class="list-group voiceOptionsList777">
                                        <li class="list-group-item voiceOptionsItem888">"1 heure"</li>
                                        <li class="list-group-item voiceOptionsItem888">"2 heures"</li>
                                        <li class="list-group-item voiceOptionsItem888">"3 heures"</li>
                                        <li class="list-group-item voiceOptionsItem888">"4 heures"</li>
                                        <li class="list-group-item voiceOptionsItem888">"Demi-journée"</li>
                                        <li class="list-group-item voiceOptionsItem888">"Journée entière"</li>
                                        <li class="list-group-item voiceOptionsItem888">"Week-end"</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card voiceOptionsCardXYZ">
                                <div class="card-header voiceOptionsHeader789">
                                    <h6 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Lieux Disponibles</h6>
                                </div>
                                <div class="card-body voiceOptionsBodyDEF">
                                    <ul class="list-group voiceOptionsList777">
                                        @php
                                        $locations = \App\Models\Location::where('in_repair', false)->get();
                                        @endphp
                                        @foreach($locations as $location)
                                        <li class="list-group-item voiceOptionsItem888">"{{ $location->name }} à {{ $location->city }}"</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card voiceOptionsCardXYZ">
                                <div class="card-header voiceOptionsHeader789">
                                    <h6 class="mb-0"><i class="fas fa-bullhorn me-2"></i>Campagnes Disponibles</h6>
                                </div>
                                <div class="card-body voiceOptionsBodyDEF">
                                    <ul class="list-group voiceOptionsList777">
                                        @foreach($campaigns as $campaign)
                                        <li class="list-group-item voiceOptionsItem888">"{{ $campaign->name }}"</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- VOICE COMMAND HELP -->
                    <div id="voiceCommandHelp" class="alert alert-info voiceCommandHelp555 mb-4" style="display: none;">
                        <strong>💡 Conseil:</strong> Dites par exemple: <br>
                        "<em>Titre: Nettoyage de la plage, Description: Événement écologique, Durée: 2 heures, Lieu: Salle communale à Paris</em>"
                    </div>

                    <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data" id="eventForm" novalidate>
                        @csrf

                        <div class="row">
                            <!-- Left Column - Form Fields -->
                            <div class="col-md-8">
                                <!-- Title -->
                                <div class="mb-3">
                                    <label for="title" class="form-label">
                                        <i class="fas fa-heading me-2" style="color: #2d5a27;"></i>Titre de l'événement <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                        class="form-control @error('title') is-invalid @enderror"
                                        id="title"
                                        name="title"
                                        value="{{ old('title') }}"
                                        required
                                        placeholder="Ex: Nettoyage de la plage">
                                    @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="voice-command-hint">Dites: "Titre: [votre titre]"</small>
                                </div>

                                <!-- Description -->
                                <div class="mb-3">
                                    <label for="description" class="form-label">
                                        <i class="fas fa-align-left me-2" style="color: #2d5a27;"></i>Description <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                        id="description"
                                        name="description"
                                        rows="4"
                                        required
                                        placeholder="Décrivez votre événement...">{{ old('description') }}</textarea>
                                    @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="voice-command-hint">Dites: "Description: [votre description]"</small>
                                </div>

                                <!-- Date and Time -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="date" class="form-label">
                                                <i class="fas fa-calendar-day me-2" style="color: #2d5a27;"></i>Date et heure <span class="text-danger">*</span>
                                            </label>
                                            <input type="datetime-local"
                                                class="form-control @error('date') is-invalid @enderror"
                                                id="date"
                                                name="date"
                                                value="{{ old('date') }}"
                                                required
                                                min="{{ now()->format('Y-m-d\TH:i') }}">
                                            @error('date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="voice-command-hint">Dites: "Demain" ou "Après-demain"</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="duration" class="form-label">
                                                <i class="fas fa-clock me-2" style="color: #2d5a27;"></i>Durée <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select @error('duration') is-invalid @enderror"
                                                id="duration"
                                                name="duration"
                                                required>
                                                <option value="">Sélectionnez une durée</option>
                                                <option value="1 heure" {{ old('duration') == '1 heure' ? 'selected' : '' }}>1 heure</option>
                                                <option value="2 heures" {{ old('duration') == '2 heures' ? 'selected' : '' }}>2 heures</option>
                                                <option value="3 heures" {{ old('duration') == '3 heures' ? 'selected' : '' }}>3 heures</option>
                                                <option value="4 heures" {{ old('duration') == '4 heures' ? 'selected' : '' }}>4 heures</option>
                                                <option value="Demi-journée" {{ old('duration') == 'Demi-journée' ? 'selected' : '' }}>Demi-journée</option>
                                                <option value="Journée entière" {{ old('duration') == 'Journée entière' ? 'selected' : '' }}>Journée entière</option>
                                                <option value="Week-end" {{ old('duration') == 'Week-end' ? 'selected' : '' }}>Week-end</option>
                                            </select>
                                            @error('duration')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="voice-command-hint">Dites: "Durée: 2 heures"</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <!-- Location Dropdown -->
                                    <div class="col-md-6">
                                        <label for="location_id" class="form-label">
                                            <i class="fas fa-map-marker-alt me-2" style="color: #2d5a27;"></i>Lieu <span class="text-danger">*</span>
                                        </label>
                                        @php
                                        $locations = \App\Models\Location::where('in_repair', false)->get();
                                        @endphp
                                        <select class="form-select @error('location_id') is-invalid @enderror" id="location_id" name="location_id" required>
                                            <option value="">Sélectionnez un lieu</option>
                                            @foreach($locations as $location)
                                            <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>{{ $location->name }} ({{ $location->city }})</option>
                                            @endforeach
                                        </select>
                                        @error('location_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="voice-command-hint">Dites: "Lieu: [nom du lieu]"</small>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="campaign_id" class="form-label @error('campaign_id') is-invalid @enderror" style="color: #2d5a27;">
                                            <i class="fas fa-bullhorn me-2" style="color: #2d5a27;"></i>Campagne <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select @error('campaign_id') is-invalid @enderror"
                                            id="campaign_id"
                                            name="campaign_id">
                                            <option value="">Sélectionnez une campagne</option>
                                            @php
                                            $campaigns = \App\Models\Campaign::all();
                                            @endphp
                                            @foreach($campaigns as $campaign)
                                            <option value="{{ $campaign->id }}" {{ old('campaign_id') == $campaign->id ? 'selected' : '' }}>{{ $campaign->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('campaign_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror

                                    </div>
                                    <!-- Sponsor combobox (single field: type to filter, empty shows all) -->
                                    
                                </div>
<div class="row mb-3">
                                        <div class="col-md-12">
                                            <label for="sponsor_input" class="form-label">
                                                <i class="fas fa-handshake me-2" style="color: #2d5a27;"></i>Sélectionner un sponsor (optionnel)
                                            </label>
                                            @php
                                            $sponsors = \App\Models\Sponsor::validated()->with('user')->get();
                                            $selectedSponsor = null;
                                            if (old('sponsor_id')) {
                                            $selectedSponsor = $sponsors->firstWhere('id', old('sponsor_id'));
                                            }
                                            $selectedDisplay = $selectedSponsor ? (($selectedSponsor->company_name ?? ($selectedSponsor->user->name ?? 'Sponsor')) . ($selectedSponsor->city ? ' - ' . $selectedSponsor->city : '')) : '';
                                            @endphp
                                            <div class="position-relative" id="sponsor-combobox">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                                    <input type="text" id="sponsor_input" class="form-control" placeholder="Tapez pour chercher un sponsor..." autocomplete="off" value="{{ $selectedDisplay }}">
                                                    <button class="btn btn-outline-secondary" type="button" id="sponsor_clear" title="Effacer">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                                <input type="hidden" name="sponsor_id" id="sponsor_id" value="{{ old('sponsor_id') }}">
                                                <div id="sponsor_dropdown" class="dropdown-menu w-100" style="max-height: 240px; overflow-y: auto;">
                                                    <button type="button" class="dropdown-item" data-id="" data-label="Aucun sponsor (désélectionner)">
                                                        Aucun sponsor
                                                    </button>
                                                    <div class="dropdown-divider"></div>
                                                    @foreach($sponsors as $s)
                                                    @php
                                                    $label = ($s->company_name ?? ($s->user->name ?? 'Sponsor')) . ($s->city ? ' - ' . $s->city : '');
                                                    $search = strtolower(($s->company_name ?? '') . ' ' . ($s->user->name ?? '') . ' ' . ($s->city ?? ''));
                                                    @endphp
                                                    <button type="button" class="dropdown-item sponsor-option" data-id="{{ $s->id }}" data-search="{{ $search }}" data-label="{{ $label }}">
                                                        <div class="d-flex align-items-center">
                                                            <i class="fas fa-building me-2 text-muted"></i>
                                                            <span>{{ $label }}</span>
                                                        </div>
                                                    </button>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <small class="text-muted">Le sponsor choisi recevra une demande pour parrainer cet événement et pourra l'accepter ou la refuser.</small>
                                        </div>
                                    </div>
                                

                                <div class="mb-3">
                                    <label for="max_participants" class="form-label">
                                        <i class="fas fa-users me-2" style="color: #2d5a27;"></i>Nombre maximum de participants
                                    </label>
                                    <input type="number"
                                        class="form-control @error('max_participants') is-invalid @enderror"
                                        id="max_participants"
                                        name="max_participants"
                                        value="{{ old('max_participants') }}"
                                        min="1"
                                        placeholder="Laissez vide pour illimité">
                                    @error('max_participants')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Optionnel - Laissez vide si pas de limite</small>
                                    <small class="voice-command-hint">Dites: "50 participants"</small>
                                </div>
                            </div>

                            <!-- Right Column - More Fields, Images and Actions -->
                            <div class="col-md-4 ">
                                <!-- Max Participants -->
                                <div class="alert alert-info mt-10 mb-4">
                                    <i class="fas fa-info-circle me-2 "></i>
                                    <strong>Important :</strong> Après la création, votre événement sera en statut "Brouillon".
                                    Vous pourrez le soumettre pour approbation depuis la liste de vos événements.
                                </div>

                                <!-- Images - Updated Drag & Drop Style -->
                                <div class="mb-4">
                                    <label class="form-label">
                                        <i class="fas fa-images me-2" style="color: #2d5a27;"></i>Images de l'événement
                                    </label>
                                    <div class="drag-drop-area @error('images.*') is-invalid @enderror" id="dragDropArea" style="border:2px dashed #2d5a27; border-radius:12px; background:#f4fbf4; padding:1.2rem 0.5rem; text-align:center; display:flex; flex-direction:column; align-items:center; justify-content:center; max-width:340px; margin:auto;">
                                        <div class="drag-drop-content" style="pointer-events:none;">
                                            <i class="fas fa-cloud-upload-alt" style="font-size:2.1rem; color:#2d5a27; margin-bottom:0.5rem;"></i>
                                            <div style="font-size:1.05rem; color:#2d5a27; font-weight:500;">Drag & Drop vos images</div>
                                            <div style="color:#2d5a27; margin:0.25rem 0; font-size:0.95rem;">ou</div>
                                            <div style="pointer-events:all;">
                                                <button type="button" class="btn btn-outline-success btn-sm" id="browseBtn" style="border-color:#2d5a27; color:#2d5a27;">Parcourir les fichiers</button>
                                                <input type="file" id="images" name="images[]" multiple accept="image/*" style="display:none;">
                                            </div>
                                        </div>
                                    </div>
                                    @error('images.*')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Vous pouvez sélectionner plusieurs images. Formats acceptés: JPG, PNG, GIF. Max: 2MB par image.
                                    </small>
                                    <!-- Image preview -->
                                    <div id="imagePreview" class="mt-3 row g-2"></div>
                                </div>

                                <!-- Buttons -->
                                <div class="d-flex justify-content-between mt-4 mb-30">
                                    <a href="{{ route('events.my-events') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-eco" style="background-color: #2d5a27; border-color: #2d5a27; color: white;">
                                        <i class="fas fa-save me-2"></i>Créer l'événement
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Voice Recognition Functionality
    class VoiceFormFiller {
        constructor() {
            this.recognition = null;
            this.isListening = false;
            this.areOptionsVisible = false;
            this.init();
        }

        init() {
            // Check browser support
            if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) {
                this.showError('La reconnaissance vocale n\'est pas supportée par votre navigateur.');
                document.getElementById('voiceControlBtn').disabled = true;
                return;
            }

            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            this.recognition = new SpeechRecognition();

            // Configure recognition - SINGLE UTTERANCE (stops automatically)
            this.recognition.continuous = false;
            this.recognition.interimResults = false;
            this.recognition.lang = 'fr-FR';

            // Event handlers
            this.recognition.onstart = () => this.onRecognitionStart();
            this.recognition.onresult = (event) => this.onRecognitionResult(event);
            this.recognition.onerror = (event) => this.onRecognitionError(event);
            this.recognition.onend = () => this.onRecognitionEnd();

            // Bind buttons
            document.getElementById('voiceControlBtn').addEventListener('click', () => this.toggleListening());
            document.getElementById('dropdownViewerBtn').addEventListener('click', () => this.toggleOptionsVisibility());
        }

        toggleListening() {
            if (this.isListening) {
                this.stopListening();
            } else {
                this.startListening();
            }
        }

        startListening() {
            try {
                this.recognition.start();
                this.isListening = true;
            } catch (error) {
                this.showError('Erreur lors du démarrage de la reconnaissance vocale.');
            }
        }

        stopListening() {
            if (this.isListening) {
                this.recognition.stop();
                this.isListening = false;
            }
        }

        onRecognitionStart() {
            const btn = document.getElementById('voiceControlBtn');
            const status = document.getElementById('voiceStatus');
            const feedback = document.getElementById('voiceFeedback');

            btn.classList.add('listening');
            status.textContent = '🎤 Écoute en cours... Parlez maintenant';
            feedback.classList.remove('d-none');
            this.updateFeedback('🎤 Je vous écoute... Dites votre commande puis attendez');
        }

        onRecognitionResult(event) {
            const transcript = event.results[0][0].transcript;
            const transcriptLower = transcript.toLowerCase();
            console.log('Raw transcript:', transcript);
            console.log('Lower transcript:', transcriptLower);

            this.updateFeedback(`✅ Reconnu: "${transcript}"`);

            // Process the command
            this.processVoiceCommand(transcript, transcriptLower);
        }

        onRecognitionError(event) {
            if (event.error !== 'no-speech') {
                this.showError(`Erreur de reconnaissance: ${event.error}`);
            }
            this.isListening = false;
            this.updateUIAfterStop();
        }

        onRecognitionEnd() {
            console.log('Recognition ended automatically');
            this.isListening = false;
            this.updateUIAfterStop();
        }

        updateUIAfterStop() {
            const btn = document.getElementById('voiceControlBtn');
            const status = document.getElementById('voiceStatus');

            btn.classList.remove('listening');
            status.textContent = 'Activer la Reconnaissance Vocale';

            setTimeout(() => {
                this.updateFeedback('✅ Prêt pour la prochaine commande. Cliquez pour parler à nouveau.');
            }, 1000);

            setTimeout(() => {
                if (!this.isListening) {
                    document.getElementById('voiceFeedback').classList.add('d-none');
                }
            }, 4000);
        }

        processVoiceCommand(transcript, transcriptLower) {
            console.log('Processing voice command:', transcriptLower);

            let foundAny = false;

            // FLEXIBLE TITLE DETECTION
            if (this.detectTitle(transcript, transcriptLower)) {
                foundAny = true;
            }

            // FLEXIBLE DESCRIPTION DETECTION
            if (this.detectDescription(transcript, transcriptLower)) {
                foundAny = true;
            }

            // FLEXIBLE DURATION DETECTION
            if (this.detectDuration(transcriptLower)) {
                foundAny = true;
            }

            // FLEXIBLE LOCATION DETECTION
            if (this.detectLocation(transcriptLower)) {
                foundAny = true;
            }

            // FLEXIBLE CAMPAIGN DETECTION
            if (this.detectCampaign(transcriptLower)) {
                foundAny = true;
            }

            // FLEXIBLE PARTICIPANTS DETECTION
            if (this.detectParticipants(transcriptLower)) {
                foundAny = true;
            }

            // FLEXIBLE DATE DETECTION
            if (this.detectDate(transcriptLower)) {
                foundAny = true;
            }

            // AUTO-STOP VOICE COMMANDS
            if (this.detectStopCommands(transcriptLower)) {
                this.stopListening();
                this.updateFeedback('🛑 Microphone arrêté par commande vocale');
                return;
            }

            if (!foundAny) {
                this.updateFeedback('❌ Aucune commande valide détectée. Essayez: "Titre: Mon événement"');
            }
        }

        // NEW: Toggle options visibility
        toggleOptionsVisibility() {
            this.areOptionsVisible = !this.areOptionsVisible;

            const optionsContainer = document.getElementById('voiceOptionsContainer');
            const helpSection = document.getElementById('voiceCommandHelp');
            const dropdownBtn = document.getElementById('dropdownViewerBtn');
            const dropdownText = document.getElementById('dropdownViewerText');

            if (this.areOptionsVisible) {
                optionsContainer.style.display = 'flex';
                helpSection.style.display = 'block';
                dropdownBtn.classList.add('btn-info');
                dropdownText.textContent = 'Masquer les Options';
                this.updateFeedback('📋 Options disponibles affichées');
            } else {
                optionsContainer.style.display = 'none';
                helpSection.style.display = 'none';
                dropdownBtn.classList.remove('btn-info');
                dropdownText.textContent = 'Voir les Options Disponibles';
                this.updateFeedback('📋 Options masquées');
            }
        }

        detectStopCommands(transcriptLower) {
            const stopCommands = [
                'stop', 'arrête', 'arrêter', 'stop écoute', 'arrête écoute',
                'stop microphone', 'arrête microphone', 'fin', 'terminé',
                'c\'est fini', 'c\'est tout', 'merci', 'ok', 'd\'accord',
                'ça suffit', 'silence'
            ];

            for (const command of stopCommands) {
                if (transcriptLower.includes(command)) {
                    return true;
                }
            }
            return false;
        }

        detectTitle(originalTranscript, transcriptLower) {
            const titlePatterns = [
                /titre\s*[:\-]?\s*(.+?)(?=,|$|description|date|durée|lieu|campagne|participants)/i,
                /titre\s*[:\-]?\s*(.+)/i,
                /intitulé\s*[:\-]?\s*(.+)/i,
                /nom\s*[:\-]?\s*(.+)/i
            ];

            for (const pattern of titlePatterns) {
                const match = originalTranscript.match(pattern);
                if (match && match[1]) {
                    const title = match[1].trim();
                    if (title && title.length > 0) {
                        document.getElementById('title').value = title;
                        this.updateFeedback(`📝 Titre défini: "${title}"`);
                        return true;
                    }
                }
            }
            return false;
        }

        detectDescription(originalTranscript, transcriptLower) {
            const descPatterns = [
                /description\s*[:\-]?\s*(.+?)(?=,|$|titre|date|durée|lieu|campagne|participants)/i,
                /description\s*[:\-]?\s*(.+)/i,
                /décris\s*[:\-]?\s*(.+)/i,
                /décrire\s*[:\-]?\s*(.+)/i
            ];

            for (const pattern of descPatterns) {
                const match = originalTranscript.match(pattern);
                if (match && match[1]) {
                    const description = match[1].trim();
                    if (description && description.length > 0) {
                        document.getElementById('description').value = description;
                        this.updateFeedback(`📄 Description définie: "${description.substring(0, 50)}..."`);
                        return true;
                    }
                }
            }
            return false;
        }

        detectDuration(transcriptLower) {
            const durationMap = {
                '1 heure': '1 heure',
                'une heure': '1 heure',
                '1h': '1 heure',
                '2 heures': '2 heures',
                'deux heures': '2 heures',
                '2h': '2 heures',
                '3 heures': '3 heures',
                'trois heures': '3 heures',
                '3h': '3 heures',
                '4 heures': '4 heures',
                'quatre heures': '4 heures',
                '4h': '4 heures',
                'demi-journée': 'Demi-journée',
                'demi journée': 'Demi-journée',
                'demi-journee': 'Demi-journée',
                'demi journee': 'Demi-journée',
                'journée entière': 'Journée entière',
                'journee entiere': 'Journée entière',
                'journée': 'Journée entière',
                'journee': 'Journée entière',
                'week-end': 'Week-end',
                'weekend': 'Week-end',
                'fin de semaine': 'Week-end'
            };

            for (const [voiceCommand, durationValue] of Object.entries(durationMap)) {
                if (transcriptLower.includes('durée') && transcriptLower.includes(voiceCommand)) {
                    document.getElementById('duration').value = durationValue;
                    this.updateFeedback(`⏱️ Durée définie: ${durationValue}`);
                    return true;
                }
            }
            return false;
        }


        detectLocation(transcriptLower) {
            const locationSelect = document.getElementById('location_id');
            const locations = JSON.parse(`{!! json_encode($locations->pluck('name', 'id')) !!}`);

            for (const [id, name] of Object.entries(locations)) {
                const locationName = name.toLowerCase();
                if (transcriptLower.includes(locationName)) {
                    locationSelect.value = id;
                    this.updateFeedback(`📍 Lieu sélectionné: ${name}`);
                    return true;
                }
            }
            return false;
        }

        detectCampaign(transcriptLower) {
            const campaignSelect = document.getElementById('campaign_id');
            const campaigns = JSON.parse(`{!! json_encode($campaigns->pluck('name', 'id')) !!}`);

            for (const [id, name] of Object.entries(campaigns)) {
                const campaignName = name.toLowerCase();
                if (transcriptLower.includes(campaignName)) {
                    campaignSelect.value = id;
                    this.updateFeedback(`📢 Campagne sélectionnée: ${name}`);
                    return true;
                }
            }
            return false;
        }

        detectParticipants(transcriptLower) {
            const participantMatch = transcriptLower.match(/(\d+)\s*(participants?|personnes?|people)/);
            if (participantMatch) {
                const count = participantMatch[1];
                document.getElementById('max_participants').value = count;
                this.updateFeedback(`👥 Participants définis: ${count}`);
                return true;
            }
            return false;
        }

        detectDate(transcriptLower) {
            if (transcriptLower.includes('demain')) {
                const tomorrow = new Date();
                tomorrow.setDate(tomorrow.getDate() + 1);
                this.setDateTimeField(tomorrow);
                this.updateFeedback('📅 Date définie: Demain');
                return true;
            } else if (transcriptLower.includes('après-demain') || transcriptLower.includes('après demain')) {
                const dayAfterTomorrow = new Date();
                dayAfterTomorrow.setDate(dayAfterTomorrow.getDate() + 2);
                this.setDateTimeField(dayAfterTomorrow);
                this.updateFeedback('📅 Date définie: Après-demain');
                return true;
            }
            return false;
        }

        setDateTimeField(date) {
            const formattedDate = date.toISOString().slice(0, 16);
            document.getElementById('date').value = formattedDate;
        }

        updateFeedback(message) {
            document.getElementById('feedbackText').textContent = message;
        }

        showError(message) {
            const feedback = document.getElementById('voiceFeedback');
            feedback.classList.remove('alert-info', 'd-none');
            feedback.classList.add('alert-danger');
            this.updateFeedback(message);

            setTimeout(() => {
                feedback.classList.add('d-none');
                feedback.classList.remove('alert-danger');
                feedback.classList.add('alert-info');
            }, 5000);
        }
    }

    // Set min datetime and sponsor combobox logic (moved outside class to fix syntax)
    document.addEventListener('DOMContentLoaded', function() {
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        const dateInput = document.getElementById('date');
        if (dateInput) {
            dateInput.min = now.toISOString().slice(0, 16);
        }

        // Sponsor combobox logic
        const cb = document.getElementById('sponsor-combobox');
        const input = document.getElementById('sponsor_input');
        const hiddenId = document.getElementById('sponsor_id');
        const dropdown = document.getElementById('sponsor_dropdown');
        const clearBtn = document.getElementById('sponsor_clear');

        if (cb && input && hiddenId && dropdown && clearBtn) {
            function openDropdown() {
                dropdown.classList.add('show');
                dropdown.style.display = 'block';
            }

            function closeDropdown() {
                dropdown.classList.remove('show');
                dropdown.style.display = 'none';
            }

            function filterOptions(term) {
                const t = (term || '').trim().toLowerCase();
                const opts = dropdown.querySelectorAll('.sponsor-option');
                let visibleCount = 0;
                opts.forEach(btn => {
                    const hay = (btn.getAttribute('data-search') || '').toLowerCase();
                    const show = !t || hay.includes(t);
                    btn.style.display = show ? '' : 'none';
                    if (show) visibleCount++;
                });
                // Show/hide divider depending on availability
                const divider = dropdown.querySelector('.dropdown-divider');
                if (divider) divider.style.display = visibleCount > 0 ? '' : 'none';
            }

            function setSelection(id, label) {
                hiddenId.value = id || '';
                input.value = label || '';
                closeDropdown();
            }

            // Show all on focus/empty
            input.addEventListener('focus', () => {
                filterOptions('');
                openDropdown();
            });
            input.addEventListener('input', () => {
                filterOptions(input.value);
                openDropdown();
            });

            // Click options
            dropdown.addEventListener('click', (e) => {
                const item = e.target.closest('.dropdown-item');
                if (!item) return;
                const id = item.getAttribute('data-id') || '';
                const label = item.getAttribute('data-label') || item.textContent.trim();
                setSelection(id, id ? label : '');
            });

            // Clear selection
            clearBtn.addEventListener('click', () => setSelection('', ''));

            // Close on outside click
            document.addEventListener('click', (e) => {
                if (!cb.contains(e.target)) {
                    closeDropdown();
                }
            });
        }
    });

    // Drag & Drop functionality
    const dragDropArea = document.getElementById('dragDropArea');
    const fileInput = document.getElementById('images');
    const browseBtn = document.getElementById('browseBtn');

    // Browse button click
    browseBtn.addEventListener('click', function() {
        fileInput.click();
    });

    // File input change
    fileInput.addEventListener('change', function(e) {
        handleFiles(e.target.files);
    });

    // Drag & drop events
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dragDropArea.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dragDropArea.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dragDropArea.addEventListener(eventName, unhighlight, false);
    });

    function highlight() {
        dragDropArea.classList.add('drag-over');
    }

    function unhighlight() {
        dragDropArea.classList.remove('drag-over');
    }

    dragDropArea.addEventListener('drop', function(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        handleFiles(files);
        fileInput.files = files;
    });

    // Handle selected files
    function handleFiles(files) {
        const preview = document.getElementById('imagePreview');
        preview.innerHTML = '';

        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    const col = document.createElement('div');
                    col.className = 'col-6 col-md-4 mb-2';
                    col.innerHTML = `
                <div class="card position-relative">
                    <img src="${e.target.result}" class="card-img-top" style="height: 100px; object-fit: cover;">
                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 remove-new-image" data-file-name="${file.name}" style="padding: 0.25rem 0.5rem;">
                        <i class="fas fa-times"></i>
                    </button>
                    <div class="card-body p-2">
                        <small class="text-muted d-block text-truncate">${file.name}</small>
                    </div>
                </div>
            `;
                    preview.appendChild(col);

                    // Add remove functionality for new images
                    col.querySelector('.remove-new-image').addEventListener('click', function() {
                        removeNewImage(this, file.name);
                    });
                };

                reader.readAsDataURL(file);
            }
        }
    }

    // Remove new image before upload
    function removeNewImage(button, fileName) {
        if (confirm('Supprimer cette image ?')) {
            const card = button.closest('.col-6');
            card.remove();

            // Remove file from input
            const dt = new DataTransfer();
            const files = fileInput.files;

            for (let i = 0; i < files.length; i++) {
                if (files[i].name !== fileName) {
                    dt.items.add(files[i]);
                }
            }

            fileInput.files = dt.files;
        }
    }

    // Initialize voice recognition when page loads
    document.addEventListener('DOMContentLoaded', function() {
        new VoiceFormFiller();

        // Set min datetime for date field
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        document.getElementById('date').min = now.toISOString().slice(0, 16);
    });
</script>
@endpush

@push('styles')
<style>
    /* ===== UNIQUE VOICE OPTIONS STYLES ===== */
    .voiceOptionsCardXYZ {
        border: 2px solid #e8f5e8 !important;
        border-radius: 12px !important;
        box-shadow: 0 4px 15px rgba(45, 90, 39, 0.1) !important;
        transition: all 0.3s ease !important;
        margin-bottom: 1rem !important;
        overflow: hidden !important;
    }

    .voiceOptionsCardXYZ:hover {
        transform: translateY(-5px) !important;
        box-shadow: 0 8px 25px rgba(45, 90, 39, 0.2) !important;
    }

    .voiceOptionsHeader789 {
        background: linear-gradient(135deg, #2d5a27 0%, #3a7a32 100%) !important;
        color: white !important;
        border-bottom: 3px solid #4caF50 !important;
        padding: 1rem 1.25rem !important;
        font-weight: 600 !important;
    }

    .voiceOptionsBodyDEF {
        padding: 1rem !important;
        background: linear-gradient(135deg, #f8fff8 0%, #ffffff 100%) !important;
        max-height: 300px !important;
        overflow-y: auto !important;
    }

    .voiceOptionsList777 {
        border: none !important;
        border-radius: 8px !important;
        overflow: hidden !important;
    }

    .voiceOptionsItem888 {
        background-color: white !important;
        border: 1px solid #e8f5e8 !important;
        padding: 0.75rem 1rem !important;
        font-weight: 500 !important;
        color: #2d5a27 !important;
        transition: all 0.3s ease !important;
        margin: 0 !important;
        border-radius: 0 !important;
    }

    .voiceOptionsItem888:nth-child(even) {
        background-color: #f8fff8 !important;
    }

    .voiceOptionsItem888:hover {
        background-color: #2d5a27 !important;
        color: white !important;
        transform: translateX(5px) !important;
        box-shadow: 0 2px 8px rgba(45, 90, 39, 0.3) !important;
    }

    .voiceCommandHelp555 {
        background: linear-gradient(135deg, #e8f4f8 0%, #d4edda 100%) !important;
        border: 2px solid #b3e0f0 !important;
        border-radius: 10px !important;
        color: #055160 !important;
        font-size: 0.95rem !important;
        box-shadow: 0 2px 15px rgba(0, 123, 255, 0.1) !important;
    }

    .voiceCommandHelp555 strong {
        color: #2d5a27 !important;
    }

    .voiceCommandHelp555 em {
        color: #155724 !important;
        font-style: italic !important;
    }

    /* Animation for options container */
    @keyframes voiceOptionsEntranceABC {
        0% {
            opacity: 0;
            transform: translateY(-20px);
        }

        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    #voiceOptionsContainer {
        animation: voiceOptionsEntranceABC 0.5s ease-out !important;
    }

    /* Voice Recognition Styles */
    .btn-outline-eco {
        border-color: #2d5a27;
        color: #2d5a27;
    }

    .btn-outline-eco:hover,
    .btn-outline-eco.listening {
        background-color: #2d5a27;
        color: white;
    }

    .btn-outline-eco.listening {
        animation: pulse 1.5s infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.05);
        }

        100% {
            transform: scale(1);
        }
    }

    .voice-command-hint {
        font-size: 0.85rem;
        color: #6c757d;
        margin-top: 0.25rem;
    }

    .bg-eco {
        background-color: #2d5a27 !important;
    }

    .text-eco {
        color: #2d5a27 !important;
    }

    /* Existing drag & drop styles */
    .drag-drop-area {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 40px 20px;
        text-align: center;
        background-color: #f8f9fa;
        transition: all 0.3s ease;
        cursor: pointer;
        margin-bottom: 10px;
    }

    .drag-drop-area:hover {
        border-color: #2d5a27;
        background-color: #f0f9f0;
    }

    .drag-drop-area.drag-over {
        border-color: #2d5a27;
        background-color: #e8f5e8;
    }

    .drag-drop-content {
        pointer-events: none;
    }

    #browseBtn {
        pointer-events: all;
        background-color: white;
        border: 1px solid #2d5a27;
        color: #2d5a27;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    #browseBtn:hover {
        background-color: #2d5a27;
        color: white;
    }

    .card-img-top {
        object-fit: cover;
    }

.remove-new-image {
    opacity: 0.8;
    transition: opacity 0.3s ease;
}

.remove-new-image:hover {
    opacity: 1;
}

.alert-info {
    background-color: #e8f4f8;
    border-color: #b3e0f0;
    color: #055160;
}

.alert-info i {
    color: #055160;
}

    /* Sponsor combobox */
    #sponsor-combobox .dropdown-menu {
        display: none;
        max-height: 240px;
        overflow-y: auto;
        z-index: 1050;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .col-md-6 {
            width: 100%;
        }

        .drag-drop-area {
            padding: 30px 15px;
        }

        #voiceOptionsContainer {
            flex-direction: column;
        }

        .voiceOptionsCardXYZ {
            margin-bottom: 1rem;
        }
    }
</style>
@endpush
