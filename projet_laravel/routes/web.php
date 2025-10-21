<?php

// Route de test simple
Route::get('/test-simple', function() {
    return 'Test simple fonctionne !';
});

// Route spécifique pour les missions disponibles (AVANT les routes avec paramètres)
Route::get('/volunteers/available-missions', [\App\Http\Controllers\VolunteerMissionController::class, 'availableMissions'])
    ->name('volunteers.available-missions');

use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\SponsorController as BackendSponsorController;
use App\Http\Controllers\Backend\UserController as BackendUserController;
use App\Http\Controllers\CertificateVerificationController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ProfileController as FrontendProfileController;
use App\Http\Controllers\Frontend\SponsorController as FrontendSponsorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Sponsor\DashboardController as SponsorDashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\CampaignController;
use App\Http\Controllers\Backend\ResourceController;
use App\Http\Controllers\Backend\EventController; 
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\VolunteerController;
use App\Http\Controllers\AssignmentController; 
use App\Http\Controllers\SocialShareController; 

/*
|--------------------------------------------------------------------------
| Web Routes - EcoEvents
|--------------------------------------------------------------------------
*/

// Page d'accueil - Redirection vers login
Route::get('/', function () {
    return redirect()->route('login');
})->name('root');

// Page d'accueil pour les utilisateurs connectés
Route::get('/home', [HomeController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('home');

// Pages publiques (accessibles sans connexion)
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

// Routes publiques pour les sponsors
Route::get('/sponsors', [FrontendSponsorController::class, 'index'])->name('sponsors.index');
Route::get('/sponsors/create', [FrontendSponsorController::class, 'create'])->name('sponsors.create');
Route::get('/sponsors/success', [FrontendSponsorController::class, 'success'])->name('sponsors.success');
Route::get('/sponsors/{sponsor}', [FrontendSponsorController::class, 'show'])
    ->whereNumber('sponsor')
    ->name('sponsors.show');
Route::post('/sponsors', [FrontendSponsorController::class, 'store'])->name('sponsors.store');

/*
|--------------------------------------------------------------------------
| Frontend Routes (Utilisateurs connectés)
|--------------------------------------------------------------------------
*/
// Routes 2FA
require __DIR__ . '/2fa.php';

Route::middleware(['auth'])->group(function () {
    // Profil utilisateur (Breeze original)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Profil utilisateur étendu (EcoEvents)
    Route::prefix('my-profile')->name('profile.')->group(function () {
        Route::get('/', [FrontendProfileController::class, 'show'])->name('show');
        Route::get('/edit', [FrontendProfileController::class, 'edit'])->name('edit.extended');
        Route::put('/update', [FrontendProfileController::class, 'update'])->name('update.extended');
        Route::delete('/avatar', [FrontendProfileController::class, 'deleteAvatar'])->name('avatar.delete');
    });
});

/*
|--------------------------------------------------------------------------
| Backend Routes (Administration)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('backend.')->group(function () {

    // Event moderation (approve/reject)
    Route::post('/events/{event}/approve', [\App\Http\Controllers\Backend\EventModerationController::class, 'approve'])->name('events.approve');
    Route::post('/events/{event}/reject', [\App\Http\Controllers\Backend\EventModerationController::class, 'reject'])->name('events.reject');

    // Demandes d'événements (pending & rejected)
    Route::get('/events/requests/pending', function () {
    $events = \App\Models\Event::where('status', 'pending')->orderBy('created_at', 'desc')->paginate(3);
        return view('backend.events.requests.pending', compact('events'));
    })->name('events.requests.pending');

    Route::get('/events/requests/rejected', function () {
    $events = \App\Models\Event::where('status', 'rejected')->orderBy('created_at', 'desc')->paginate(5);
        return view('backend.events.requests.rejected', compact('events'));
    })->name('events.requests.rejected');
    // Backend Locations CRUD
    Route::resource('locations', App\Http\Controllers\Backend\LocationController::class);
    // Page de bienvenue admin
    Route::get('/', [AdminController::class, 'welcome'])->name('welcome');

    // Dashboard admin
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Gestion des utilisateurs
    Route::resource('users', BackendUserController::class);
    Route::post('users/{user}/toggle-eco-ambassador', [BackendUserController::class, 'toggleEcoAmbassador'])
        ->name('users.toggle-eco-ambassador');

    // Gestion des volontaires
    Route::resource('volunteers', \App\Http\Controllers\Backend\VolunteerController::class);
    Route::post('volunteers/{volunteer}/approve', [\App\Http\Controllers\Backend\VolunteerController::class, 'approve'])
        ->name('volunteers.approve');
    Route::post('volunteers/{volunteer}/reject', [\App\Http\Controllers\Backend\VolunteerController::class, 'reject'])
        ->name('volunteers.reject');
    Route::post('volunteers/{volunteer}/reset', [\App\Http\Controllers\Backend\VolunteerController::class, 'reset'])
        ->name('volunteers.reset');

    // Gestion des missions
    Route::resource('assignments', \App\Http\Controllers\Backend\AssignmentController::class);
    Route::post('assignments/{assignment}/approve', [\App\Http\Controllers\Backend\AssignmentController::class, 'approve'])
        ->name('assignments.approve');
    Route::post('assignments/{assignment}/reject', [\App\Http\Controllers\Backend\AssignmentController::class, 'reject'])
        ->name('assignments.reject');

    // Gestion des sponsors
    Route::get('sponsors/pending', [BackendSponsorController::class, 'pending'])->name('sponsors.pending');
    // Place deletion-requested BEFORE resource to prevent capture by /sponsors/{sponsor} show route
    Route::get('sponsors/deletion-requested', [BackendSponsorController::class, 'deletionRequested'])->name('sponsors.deletion-requested');
    Route::resource('sponsors', BackendSponsorController::class);
    Route::post('sponsors/{sponsor}/validate', [BackendSponsorController::class, 'validate'])->name('sponsors.validate');
    Route::post('sponsors/{sponsor}/reject', [BackendSponsorController::class, 'reject'])->name('sponsors.reject');
    Route::post('sponsors/{sponsor}/restore', [BackendSponsorController::class, 'restore'])->name('sponsors.restore');
    Route::get('sponsors/trashed', [BackendSponsorController::class, 'trashed'])->name('sponsors.trashed');
    Route::post('sponsors/{sponsor}/process-deletion', [BackendSponsorController::class, 'processDeletion'])->name('sponsors.process-deletion');

    // Gestion des dons
    Route::get('donations', [DonationController::class, 'adminIndex'])->name('donations.index');
    Route::post('donations/{donation}/confirm', [DonationController::class, 'confirm'])->name('donations.confirm');
    Route::post('donations/{donation}/cancel', [DonationController::class, 'cancel'])->name('donations.cancel');
    Route::get('/events', function () {
        $query = \App\Models\Event::with('user')
            ->where('status', '!=', 'draft');
        $search = request('search');
        if ($search) {
            $query->whereRaw('LOWER(title) LIKE ?', [strtolower($search) . '%']);
        }
        $query->orderBy('created_at', 'desc');
        $perPage = 5;
        $paginated = $query->paginate($perPage)->appends(request()->query());
        $allEvents = \App\Models\Event::with('user')
            ->where('status', '!=', 'draft')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('backend.events.index', ['events' => $paginated, 'allEvents' => $allEvents]);
    })->name('events.index');

    Route::get('/events/{event}', function (\App\Models\Event $event) {
        $event->load('user'); // Charger la relation user
        return view('backend.events.show', compact('event'));
    })->name('events.show');


    
    


    // ✅ AJOUTÉ : Gestion des campagnes (backend) - Même pattern que events
    Route::get('/campaigns', function () {
        $campaigns = \App\Models\Campaign::with('organizer')
            ->withCount(['resources', 'events'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('backend.campaigns.index', compact('campaigns'));
    })->name('campaigns.index');

    Route::get('/campaigns/{campaign}', function (\App\Models\Campaign $campaign) {
        $campaign->load('organizer');
        $campaign->loadCount(['resources', 'events']);
        return view('backend.campaigns.show', compact('campaign'));
    })->name('campaigns.show');

    // ✅ Gestion des demandes de suppression de campagnes
    Route::get('/campaigns/deletion-requests', [CampaignController::class, 'deletionRequests'])
        ->name('campaigns.deletion-requests');
    Route::post('/campaigns/deletion-requests/{deletionRequest}/process', [CampaignController::class, 'processDeletionRequest'])
        ->name('campaigns.process-deletion-request');

    // ✅ AJOUTÉ : Gestion des ressources (backend) - Même pattern
    Route::get('/resources', function () {
        $resources = \App\Models\Resource::with('campaign')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('backend.resources.index', compact('resources'));
    })->name('resources.index');

    Route::get('/resources/{resource}', function (\App\Models\Resource $resource) {
        $resource->load('campaign.organizer');
        return view('backend.resources.show', compact('resource'));
    })->name('resources.show');

   
});




/*
|--------------------------------------------------------------------------
| ROUTES EVENTS & LOCATIONS
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    // Routes pour les utilisateurs normaux (voient tous les événements)
    Route::get('/events', [\App\Http\Controllers\EventController::class, 'index'])->name('events.index');
    Route::get('/events/{event}', [\App\Http\Controllers\EventController::class, 'show'])->name('events.show');
    
    // Routes pour les organisateurs (gestion de leurs événements)
    Route::middleware(['role:organizer'])->group(function () {
        Route::get('/my-events', [\App\Http\Controllers\Backend\EventController::class, 'index'])->name('events.my-events');
        Route::get('/my-events/create', [\App\Http\Controllers\Backend\EventController::class, 'create'])->name('events.create');
        Route::post('/my-events', [\App\Http\Controllers\Backend\EventController::class, 'store'])->name('events.store');
        Route::get('/my-events/{event}/edit', [\App\Http\Controllers\Backend\EventController::class, 'edit'])->name('events.edit');
        Route::put('/my-events/{event}', [\App\Http\Controllers\Backend\EventController::class, 'update'])->name('events.update');
        Route::delete('/my-events/{event}', [\App\Http\Controllers\Backend\EventController::class, 'destroy'])->name('events.destroy');
        Route::post('/my-events/{event}/submit', [\App\Http\Controllers\Backend\EventController::class, 'submitForApproval'])->name('events.submit');
        Route::post('/my-events/{event}/cancel', [\App\Http\Controllers\Backend\EventController::class, 'cancel'])->name('events.cancel');
        Route::post('/my-events/{event}/remove-image', [\App\Http\Controllers\Backend\EventController::class, 'removeImage'])->name('events.remove-image');

        // Flyer actions
        Route::post('/my-events/{event}/flyer/generate', [\App\Http\Controllers\Backend\EventFlyerController::class, 'generate'])->name('events.flyer.generate');
        Route::get('/my-events/{event}/flyer/image', [\App\Http\Controllers\Backend\EventFlyerController::class, 'downloadImage'])->name('events.flyer.image');
        Route::get('/my-events/{event}/flyer/pdf', [\App\Http\Controllers\Backend\EventFlyerController::class, 'downloadPdf'])->name('events.flyer.pdf');
    });

     // Gestion des lieux (locations)
    Route::resource('locations', App\Http\Controllers\LocationController::class);
    
    // Routes pour les dons d'événements
    Route::get('/events/{event}/donations', [DonationController::class, 'eventDonations'])->name('events.donations');
    
});

// Routes pour les dons (accessibles à tous)
Route::get('/events/{event}/donate', [DonationController::class, 'create'])->name('donations.create');
Route::post('/events/{event}/donate', [DonationController::class, 'store'])->name('donations.store');
Route::get('/donations/{donation}/success', [DonationController::class, 'success'])->name('donations.success');
// Stripe webhook endpoint (public)
Route::post('/stripe/webhook', [\App\Http\Controllers\StripeWebhookController::class, 'handle'])->name('stripe.webhook');
// Authenticated user/sponsor donation management
Route::middleware(['auth'])->group(function () {
    Route::get('/mes-dons', [DonationController::class, 'index'])->name('donations.list');
    Route::get('/donations/{donation}/edit', [DonationController::class, 'edit'])->name('donations.edit');
    Route::put('/donations/{donation}', [DonationController::class, 'update'])->name('donations.update');
    Route::delete('/donations/{donation}', [DonationController::class, 'destroy'])->name('donations.destroy');
});

// Sponsor Dashboard
Route::middleware(['auth', 'role:sponsor', \App\Http\Middleware\EnsureSponsorProfile::class])->prefix('sponsors')->name('sponsor.')->group(function () {
    Route::get('/dashboard', [SponsorDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [\App\Http\Controllers\Sponsor\ProfileController::class, 'show'])->name('profile');
    Route::get('/profil', function () {
        return redirect()->route('profile.show');
    })->name('self.edit');
    Route::post('/demande-suppression', [BackendSponsorController::class, 'requestDeletion'])->name('self.requestDeletion');
    Route::post('/delete-now', [BackendSponsorController::class, 'selfDeleteNow'])->name('self.deleteNow');
});
/*
|--------------------------------------------------------------------------
| ROUTES Blog & Review - ACCESSIBLES À TOUS LES UTILISATEURS CONNECTÉS
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // Tous les blogs sous forme de cards (public pour utilisateur connecté)
    Route::get('/blogs/cards', [BlogController::class, 'cards'])->name('blogs.cards');

    Route::get('/blogs/myblogs', [BlogController::class, 'myBlogs'])->name('blogs.myblogs');

    // Routes pour créer, éditer et supprimer les blogs
    Route::get('/blogs/create', [BlogController::class, 'create'])->name('blogs.create');
    Route::post('/blogs', [BlogController::class, 'store'])->name('blogs.store');
    Route::get('/blogs/{blog}/edit', [BlogController::class, 'edit'])->name('blogs.edit');
    Route::put('/blogs/{blog}', [BlogController::class, 'update'])->name('blogs.update');
    Route::delete('/blogs/{blog}', [BlogController::class, 'destroy'])->name('blogs.destroy');
    Route::get('/blogs', [BlogController::class, 'index'])->name('blogs.index');


    // Détails d’un blog
    Route::get('/blogs/{blog}', [BlogController::class, 'show'])->name('blogs.show');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/blogs/{blog}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/blogs/{blog}/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/{review}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
    Route::put('/reviews/{id}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    // web.php
   Route::get('/blogs/filter', [BlogController::class, 'filter'])->name('blogs.filter');

});





/*
|--------------------------------------------------------------------------
| ROUTES EVENTS & LOCATIONS
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    // Routes pour les utilisateurs normaux (voient tous les événements)
    Route::get('/events', [\App\Http\Controllers\EventController::class, 'index'])->name('events.index');
    Route::get('/events/{event}', [\App\Http\Controllers\Backend\EventController::class, 'show'])->name('events.show');

    // Routes pour les organisateurs (gestion de leurs événements)
    Route::middleware(['role:organizer'])->group(function () {
        Route::get('/my-events', [\App\Http\Controllers\Backend\EventController::class, 'index'])->name('events.my-events');
        Route::get('/my-events/create', [\App\Http\Controllers\Backend\EventController::class, 'create'])->name('events.create');
        Route::post('/my-events', [\App\Http\Controllers\Backend\EventController::class, 'store'])->name('events.store');
        Route::get('/my-events/{event}/edit', [\App\Http\Controllers\Backend\EventController::class, 'edit'])->name('events.edit');
        Route::put('/my-events/{event}', [\App\Http\Controllers\Backend\EventController::class, 'update'])->name('events.update');
        Route::delete('/my-events/{event}', [\App\Http\Controllers\Backend\EventController::class, 'destroy'])->name('events.destroy');
        Route::post('/my-events/{event}/submit', [\App\Http\Controllers\Backend\EventController::class, 'submitForApproval'])->name('events.submit');
        Route::post('/my-events/{event}/cancel', [\App\Http\Controllers\Backend\EventController::class, 'cancel'])->name('events.cancel');
        Route::post('/my-events/{event}/remove-image', [\App\Http\Controllers\Backend\EventController::class, 'removeImage'])->name('events.remove-image');
    });

    // Gestion des lieux (locations)
    Route::resource('locations', App\Http\Controllers\LocationController::class);

    // Routes pour les dons d'événements
    Route::get('/events/{event}/donations', [DonationController::class, 'eventDonations'])->name('events.donations');
});

// Routes pour les dons (accessibles à tous)
Route::get('/events/{event}/donate', [DonationController::class, 'create'])->name('donations.create');
Route::post('/events/{event}/donate', [DonationController::class, 'store'])->name('donations.store');
Route::get('/donations/{donation}/success', [DonationController::class, 'success'])->name('donations.success');
// Authenticated user/sponsor donation management
Route::middleware(['auth'])->group(function () {
    Route::get('/mes-dons', [DonationController::class, 'index'])->name('donations.list');
    Route::get('/donations/{donation}/edit', [DonationController::class, 'edit'])->name('donations.edit');
    Route::put('/donations/{donation}', [DonationController::class, 'update'])->name('donations.update');
    Route::delete('/donations/{donation}', [DonationController::class, 'destroy'])->name('donations.destroy');
});

// Sponsor Dashboard
Route::middleware(['auth', 'role:sponsor', \App\Http\Middleware\EnsureSponsorProfile::class])->prefix('sponsors')->name('sponsor.')->group(function () {
    Route::get('/dashboard', [SponsorDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [\App\Http\Controllers\Sponsor\ProfileController::class, 'show'])->name('profile');
    Route::get('/profil', function () {
        return redirect()->route('profile.show');
    })->name('self.edit');
    Route::post('/demande-suppression', [BackendSponsorController::class, 'requestDeletion'])->name('self.requestDeletion');
    Route::post('/delete-now', [BackendSponsorController::class, 'selfDeleteNow'])->name('self.deleteNow');
    // Sponsorship requests
    Route::get('/requests', [\App\Http\Controllers\Sponsor\SponsorshipRequestController::class, 'index'])->name('requests.index');
    Route::post('/requests/{sponsorEvent}/accept', [\App\Http\Controllers\Sponsor\SponsorshipRequestController::class, 'accept'])->name('requests.accept');
    Route::post('/requests/{sponsorEvent}/decline', [\App\Http\Controllers\Sponsor\SponsorshipRequestController::class, 'decline'])->name('requests.decline');
});
/*
|--------------------------------------------------------------------------
| ROUTES Blog & Review - ACCESSIBLES À TOUS LES UTILISATEURS CONNECTÉS
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // Tous les blogs sous forme de cards (public pour utilisateur connecté)
    Route::get('/blogs/cards', [BlogController::class, 'cards'])->name('blogs.cards');

    Route::get('/blogs/myblogs', [BlogController::class, 'myBlogs'])->name('blogs.myblogs');

    // Routes pour créer, éditer et supprimer les blogs
    Route::get('/blogs/create', [BlogController::class, 'create'])->name('blogs.create');
    Route::post('/blogs', [BlogController::class, 'store'])->name('blogs.store');
    Route::get('/blogs/{blog}/edit', [BlogController::class, 'edit'])->name('blogs.edit');
    Route::put('/blogs/{blog}', [BlogController::class, 'update'])->name('blogs.update');
    Route::delete('/blogs/{blog}', [BlogController::class, 'destroy'])->name('blogs.destroy');
    Route::get('/blogs', [BlogController::class, 'index'])->name('blogs.index');


    // Détails d’un blog
    Route::get('/blogs/{blog}', [BlogController::class, 'show'])->name('blogs.show');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/blogs/{blog}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/blogs/{blog}/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/{review}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
    Route::put('/reviews/{id}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});



/*
|--------------------------------------------------------------------------
| ROUTES CAMPAIGNS & RESOURCES - ACCESSIBLES À TOUS LES UTILISATEURS CONNECTÉS
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // ✅ ROUTES CAMPAIGNS - Accessibles à tous les utilisateurs connectés
    Route::prefix('campaigns')->name('campaigns.')->group(function () {
        Route::get('/', [CampaignController::class, 'index'])->name('index');
        Route::get('/create', [CampaignController::class, 'create'])->name('create');
        Route::post('/', [CampaignController::class, 'store'])->name('store');
        Route::get('/{campaign}', [CampaignController::class, 'show'])->name('show');
        Route::get('/{campaign}/edit', [CampaignController::class, 'edit'])->name('edit');
        Route::put('/{campaign}', [CampaignController::class, 'update'])->name('update');
        Route::delete('/{campaign}', [CampaignController::class, 'destroy'])->name('destroy');
        Route::post('/{campaign}/request-deletion', [CampaignController::class, 'requestDeletion'])
             ->name('request-deletion');
        
        // Routes supplémentaires
        Route::post('/{campaign}/toggle-visibility', [CampaignController::class, 'toggleVisibility'])
             ->name('toggle-visibility');
        Route::get('/statistics', [CampaignController::class, 'statistics'])->name('statistics');
    });

    // ✅ ROUTES RESOURCES - Chemin personnalisé : /resources  
    Route::prefix('resources')->name('resources.')->group(function () {
        Route::get('/', [ResourceController::class, 'index'])->name('index');
        Route::get('/create', [ResourceController::class, 'create'])->name('create');
        Route::post('/', [ResourceController::class, 'store'])->name('store');
        Route::get('/{resource}', [ResourceController::class, 'show'])->name('show');
        Route::get('/{resource}/edit', [ResourceController::class, 'edit'])->name('edit');
        Route::put('/{resource}', [ResourceController::class, 'update'])->name('update');
        Route::delete('/{resource}', [ResourceController::class, 'destroy'])->name('destroy');
        
        // Routes supplémentaires
        Route::post('/{resource}/update-status', [ResourceController::class, 'updateStatus'])
             ->name('update-status');
        Route::post('/{resource}/pledge', [ResourceController::class, 'pledge'])
             ->name('pledge');
        Route::get('/high-priority', [ResourceController::class, 'highPriority'])
             ->name('high-priority');
    });

    // Redirections conviviales pour anciens liens
    Route::get('/home/volunteers', fn() => redirect()->route('volunteers.index'))->name('home.volunteers');
    Route::get('/volunteer', fn() => redirect()->route('volunteers.index'));
    Route::get('/assignement', fn() => redirect()->route('assignments.index'));

    // ROUTES VOLUNTEERS
    Route::prefix('volunteers')->name('volunteers.')->group(function () {
        Route::get('/', [VolunteerController::class, 'index'])->name('index');
        Route::get('/create', [VolunteerController::class, 'create'])->name('create');
        Route::post('/', [VolunteerController::class, 'store'])->name('store');
        Route::get('/{volunteer}', [VolunteerController::class, 'show'])->name('show');
        Route::get('/{volunteer}/edit', [VolunteerController::class, 'edit'])->name('edit');
        Route::put('/{volunteer}', [VolunteerController::class, 'update'])->name('update');
        Route::delete('/{volunteer}', [VolunteerController::class, 'destroy'])->name('destroy');

        // Actions spécifiques
        Route::get('/assignments/available', [VolunteerController::class, 'availableAssignments'])->name('assignments.available');
        Route::post('/apply', [VolunteerController::class, 'apply'])->name('apply');
    });

    // ROUTES ASSIGNMENTS
    Route::prefix('assignments')->name('assignments.')->group(function () {
        Route::get('/', [AssignmentController::class, 'index'])->name('index');
        Route::get('/create', [AssignmentController::class, 'create'])->name('create');
        Route::post('/', [AssignmentController::class, 'store'])->name('store');
        Route::get('/{assignment}', [AssignmentController::class, 'show'])->name('show');
        Route::get('/{assignment}/edit', [AssignmentController::class, 'edit'])->name('edit');
        Route::put('/{assignment}', [AssignmentController::class, 'update'])->name('update');
        Route::delete('/{assignment}', [AssignmentController::class, 'destroy'])->name('destroy');

        // Actions
        Route::post('/{assignment}/approve', [AssignmentController::class, 'approve'])->name('approve');
        Route::post('/{assignment}/reject', [AssignmentController::class, 'reject'])->name('reject');
        Route::post('/{assignment}/complete', [AssignmentController::class, 'complete'])->name('complete');
        Route::post('/{assignment}/cancel', [AssignmentController::class, 'cancel'])->name('cancel');
        Route::post('/{assignment}/update-hours', [AssignmentController::class, 'updateHours'])->name('update-hours');

        // Lister par entité assignable (Event/Campaign)
        Route::get('/{type}/{id}', [AssignmentController::class, 'forAssignable'])->name('for-assignable');
    });
    });

    // Redirections conviviales pour anciens liens
    Route::get('/home/volunteers', fn() => redirect()->route('volunteers.index'))->name('home.volunteers');
    Route::get('/volunteer', fn() => redirect()->route('volunteers.index'));
    Route::get('/assignement', fn() => redirect()->route('assignments.index'));

    // ROUTES VOLUNTEERS
    Route::prefix('volunteers')->name('volunteers.')->group(function () {
        Route::get('/', [VolunteerController::class, 'index'])->name('index');
        Route::get('/create', [VolunteerController::class, 'create'])->name('create');
        Route::post('/', [VolunteerController::class, 'store'])->name('store');
        Route::get('/{volunteer}', [VolunteerController::class, 'show'])->name('show');
        Route::get('/{volunteer}/edit', [VolunteerController::class, 'edit'])->name('edit');
        Route::put('/{volunteer}', [VolunteerController::class, 'update'])->name('update');
        Route::delete('/{volunteer}', [VolunteerController::class, 'destroy'])->name('destroy');

        // Actions spécifiques
        Route::get('/assignments/available', [VolunteerController::class, 'availableAssignments'])->name('assignments.available');
        Route::post('/apply', [VolunteerController::class, 'apply'])->name('apply');
    });

    // ROUTES ASSIGNMENTS
    Route::prefix('assignments')->name('assignments.')->group(function () {
        Route::get('/', [AssignmentController::class, 'index'])->name('index');
        Route::get('/create', [AssignmentController::class, 'create'])->name('create');
        Route::post('/', [AssignmentController::class, 'store'])->name('store');
        Route::get('/{assignment}', [AssignmentController::class, 'show'])->name('show');
        Route::get('/{assignment}/edit', [AssignmentController::class, 'edit'])->name('edit');
        Route::put('/{assignment}', [AssignmentController::class, 'update'])->name('update');
        Route::delete('/{assignment}', [AssignmentController::class, 'destroy'])->name('destroy');

        // Actions
        Route::post('/{assignment}/approve', [AssignmentController::class, 'approve'])->name('approve');
        Route::post('/{assignment}/reject', [AssignmentController::class, 'reject'])->name('reject');
        Route::post('/{assignment}/complete', [AssignmentController::class, 'complete'])->name('complete');
        Route::post('/{assignment}/cancel', [AssignmentController::class, 'cancel'])->name('cancel');
        Route::post('/{assignment}/update-hours', [AssignmentController::class, 'updateHours'])->name('update-hours');

        // Lister par entité assignable (Event/Campaign)
        Route::get('/{type}/{id}', [AssignmentController::class, 'forAssignable'])->name('for-assignable');
    });
    // Redirections conviviales pour anciens liens
    Route::get('/home/volunteers', fn() => redirect()->route('volunteers.index'))->name('home.volunteers');
    Route::get('/volunteer', fn() => redirect()->route('volunteers.index'));
    Route::get('/assignement', fn() => redirect()->route('assignments.index'));

    // ROUTES VOLUNTEERS
    Route::prefix('volunteers')->name('volunteers.')->group(function () {
        Route::get('/', [VolunteerController::class, 'index'])->name('index');
        Route::get('/create', [VolunteerController::class, 'create'])->name('create');
        Route::post('/', [VolunteerController::class, 'store'])->name('store');
        Route::get('/{volunteer}', [VolunteerController::class, 'show'])->name('show');
        Route::get('/{volunteer}/edit', [VolunteerController::class, 'edit'])->name('edit');
        Route::put('/{volunteer}', [VolunteerController::class, 'update'])->name('update');
        Route::delete('/{volunteer}', [VolunteerController::class, 'destroy'])->name('destroy');

        // Actions spécifiques
        Route::get('/assignments/available', [VolunteerController::class, 'availableAssignments'])->name('assignments.available');
        Route::post('/apply', [VolunteerController::class, 'apply'])->name('apply');
    });

    // ROUTES ASSIGNMENTS
    Route::prefix('assignments')->name('assignments.')->group(function () {
        Route::get('/', [AssignmentController::class, 'index'])->name('index');
        Route::get('/create', [AssignmentController::class, 'create'])->name('create');
        Route::post('/', [AssignmentController::class, 'store'])->name('store');
        Route::get('/{assignment}', [AssignmentController::class, 'show'])->name('show');
        Route::get('/{assignment}/edit', [AssignmentController::class, 'edit'])->name('edit');
        Route::put('/{assignment}', [AssignmentController::class, 'update'])->name('update');
        Route::delete('/{assignment}', [AssignmentController::class, 'destroy'])->name('destroy');

        // Actions
        Route::post('/{assignment}/approve', [AssignmentController::class, 'approve'])->name('approve');
        Route::post('/{assignment}/reject', [AssignmentController::class, 'reject'])->name('reject');
        Route::post('/{assignment}/complete', [AssignmentController::class, 'complete'])->name('complete');
        Route::post('/{assignment}/cancel', [AssignmentController::class, 'cancel'])->name('cancel');
        Route::post('/{assignment}/update-hours', [AssignmentController::class, 'updateHours'])->name('update-hours');

        // Lister par entité assignable (Event/Campaign)
        Route::get('/{type}/{id}', [AssignmentController::class, 'forAssignable'])->name('for-assignable');
    });

    Route::middleware(['auth'])->prefix('ajax')->group(function () {
    Route::post('/seat/lock', [App\Http\Controllers\ReservationController::class, 'lockSeat'])->name('ajax.seat.lock');
    Route::post('/seat/release', [App\Http\Controllers\ReservationController::class, 'releaseSeat'])->name('ajax.seat.release');
    Route::get('/event/{event}/seats-status', [App\Http\Controllers\ReservationController::class, 'getSeatsStatus'])->name('ajax.seats.status');
});
// Routes pour les listes d'attente
Route::middleware(['auth'])->group(function () {
    Route::post('/events/{event}/waiting-list/join', [App\Http\Controllers\WaitingListController::class, 'join'])->name('waiting-list.join');
    Route::post('/events/{event}/waiting-list/leave', [App\Http\Controllers\WaitingListController::class, 'leave'])->name('waiting-list.leave');
});

// Routes admin pour les listes d'attente
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/waiting-lists', [App\Http\Controllers\WaitingListController::class, 'adminIndex'])->name('waiting-lists.index');
    Route::post('/waiting-lists/{waitingList}/promote', [App\Http\Controllers\WaitingListController::class, 'promote'])->name('waiting-lists.promote');
});

// Routes pour les certifications utilisateur
Route::middleware(['auth'])->prefix('my-certificates')->name('user.certificates.')->group(function () {
    Route::get('/', [App\Http\Controllers\CertificateController::class, 'index'])->name('index');
    Route::get('/{code}', [App\Http\Controllers\CertificateController::class, 'show'])->name('show');
    Route::get('/{code}/download', [App\Http\Controllers\CertificateController::class, 'download'])->name('download');
    Route::get('/{code}/view', [App\Http\Controllers\CertificateController::class, 'view'])->name('view');
});

// Route publique pour la vérification des certificats
Route::get('/verify-certificate', [App\Http\Controllers\CertificateController::class, 'verify'])->name('certificates.verify');
Route::get('/verify-certificate/{code}', [App\Http\Controllers\CertificateController::class, 'verify'])->name('certificates.verify.code');

// Routes du Chatbot IA
Route::get('/chatbot', [App\Http\Controllers\ChatbotController::class, 'index'])->name('chatbot.index');
Route::post('/chatbot/message', [App\Http\Controllers\ChatbotController::class, 'processMessage'])->name('chatbot.message');
Route::delete('/chatbot/clear', [App\Http\Controllers\ChatbotController::class, 'clearConversation'])->name('chatbot.clear');
Route::get('/chatbot/suggestions', [App\Http\Controllers\ChatbotController::class, 'getSuggestions'])->name('chatbot.suggestions');

// Routes admin pour les certifications
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/certificates', [App\Http\Controllers\CertificateController::class, 'adminIndex'])->name('certificates.index');
    Route::post('/certificates/grant/{reservation}', [App\Http\Controllers\CertificateController::class, 'grantCertificate'])->name('certificates.grant');
});

// Admin routes for reservations & waiting reservations management (expected by views: admin.reservations.*)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/reservations', [ReservationController::class, 'adminIndex'])->name('reservations.index');
    Route::post('/reservations/{reservation}/confirm', [ReservationController::class, 'confirm'])->name('reservations.confirm');
    Route::post('/reservations/{reservation}/reject', [ReservationController::class, 'reject'])->name('reservations.reject');
    // Destroy: allow DELETE (preferred) while keeping POST fallback if Blade form doesn't spoof method
    Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');
    Route::post('/reservations/{reservation}/delete', [ReservationController::class, 'destroy'])->name('reservations.destroy.fallback');
    Route::middleware(['auth'])->prefix('ajax')->group(function () {
    Route::post('/seat/lock', [App\Http\Controllers\ReservationController::class, 'lockSeat'])->name('ajax.seat.lock');
    Route::post('/seat/release', [App\Http\Controllers\ReservationController::class, 'releaseSeat'])->name('ajax.seat.release');
    Route::get('/event/{event}/seats-status', [App\Http\Controllers\ReservationController::class, 'getSeatsStatus'])->name('ajax.seats.status');
});
// Routes pour les listes d'attente
Route::middleware(['auth'])->group(function () {
    Route::post('/events/{event}/waiting-list/join', [App\Http\Controllers\WaitingListController::class, 'join'])->name('waiting-list.join');
    Route::post('/events/{event}/waiting-list/leave', [App\Http\Controllers\WaitingListController::class, 'leave'])->name('waiting-list.leave');
});

    // Routes admin pour les listes d'attente
    Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/waiting-lists', [App\Http\Controllers\WaitingListController::class, 'adminIndex'])->name('waiting-lists.index');
        Route::post('/waiting-lists/{waitingList}/promote', [App\Http\Controllers\WaitingListController::class, 'promote'])->name('waiting-lists.promote');
    });

    // Routes pour les certifications utilisateur
    Route::middleware(['auth'])->prefix('my-certificates')->name('user.certificates.')->group(function () {
        Route::get('/', [App\Http\Controllers\CertificateController::class, 'index'])->name('index');
        Route::get('/{code}', [App\Http\Controllers\CertificateController::class, 'show'])->name('show');
        Route::get('/{code}/download', [App\Http\Controllers\CertificateController::class, 'download'])->name('download');
        Route::get('/{code}/view', [App\Http\Controllers\CertificateController::class, 'view'])->name('view');
    });

    // Route publique pour la vérification des certificats
    Route::get('/verify-certificate', [App\Http\Controllers\CertificateController::class, 'verify'])->name('certificates.verify');
    Route::get('/verify-certificate/{code}', [App\Http\Controllers\CertificateController::class, 'verify'])->name('certificates.verify.code');

    // Routes du Chatbot IA
    Route::get('/chatbot', [App\Http\Controllers\ChatbotController::class, 'index'])->name('chatbot.index');
    Route::post('/chatbot/message', [App\Http\Controllers\ChatbotController::class, 'processMessage'])->name('chatbot.message');
    Route::delete('/chatbot/clear', [App\Http\Controllers\ChatbotController::class, 'clearConversation'])->name('chatbot.clear');
    Route::get('/chatbot/suggestions', [App\Http\Controllers\ChatbotController::class, 'getSuggestions'])->name('chatbot.suggestions');

    // Routes admin pour les certifications
    Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/certificates', [App\Http\Controllers\CertificateController::class, 'adminIndex'])->name('certificates.index');
        Route::post('/certificates/grant/{reservation}', [App\Http\Controllers\CertificateController::class, 'grantCertificate'])->name('certificates.grant');
    });

// Admin routes for reservations & waiting reservations management (expected by views: admin.reservations.*)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/reservations', [ReservationController::class, 'adminIndex'])->name('reservations.index');
    Route::post('/reservations/{reservation}/confirm', [ReservationController::class, 'confirm'])->name('reservations.confirm');
    Route::post('/reservations/{reservation}/reject', [ReservationController::class, 'reject'])->name('reservations.reject');
    // Destroy: allow DELETE (preferred) while keeping POST fallback if Blade form doesn't spoof method
    Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');
    Route::post('/reservations/{reservation}/delete', [ReservationController::class, 'destroy'])->name('reservations.destroy.fallback');
});
});


Route::middleware(['auth'])->group(function () {
    Route::post('/volunteers/apply-mission', 'App\Http\Controllers\VolunteerMissionController@applyForMission')
        ->name('volunteers.apply-mission');
    Route::get('/volunteers/mission-details', 'App\Http\Controllers\VolunteerMissionController@showMissionDetails')
        ->name('volunteers.mission-details');
});




// Routes pour les APIs carbone
Route::get('/carbon/apis/test', [CampaignController::class, 'testApis'])
    ->name('carbon.apis.test');
    
Route::get('/campaigns/{campaign}/carbon-report', [CampaignController::class, 'carbonReport'])
    ->name('campaigns.carbon-report');
    
Route::get('/resources/{resource}/carbon-footprint', function($resourceId) {
    $resource = \App\Models\Resource::findOrFail($resourceId);
    $calculator = new \App\Services\CarbonCalculatorService();
    
    $footprint = $calculator->calculateResourceFootprint($resource);
    $method = config('services.carbon_interface.api_key') ? 'carbon_interface' : 'ademe';
    
    return response()->json([
        'resource_id' => $resource->id,
        'resource_name' => $resource->name,
        'carbon_footprint_kg' => $footprint,
        'calculation_method' => $method,
        'calculated_at' => now()->toISOString()
    ]);
})->name('resources.carbon-footprint');








// Route de test
Route::get('/test', 'App\Http\Controllers\TestController@test');

// 🤖 Route de test pour les recommandations IA
Route::get('/test-ai-recommendations', function() {
    if (!auth()->check()) {
        return redirect()->route('login')->with('error', 'Vous devez être connecté pour tester les recommandations IA.');
    }
    
    $recommendationService = app(\App\Services\EventRecommendationService::class);
    $result = $recommendationService->testRecommendations(auth()->user());
    
    return response()->json($result, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
})->name('test.ai.recommendations');

// Routes de vérification des certificats (publiques)
Route::get('/certificates/verify/{token}', [CertificateVerificationController::class, 'verify'])
    ->name('certificates.verify');
Route::post('/api/certificates/verify', [CertificateVerificationController::class, 'apiVerify'])
    ->name('certificates.api.verify');




        Route::get('/events/{event}/share/test', [SocialShareController::class, 'testShare'])
    ->name('events.social-share.test')
    ->middleware('auth');
    
// Social sharing routes
Route::middleware(['auth'])->group(function () {
    Route::get('/events/{event}/share', [SocialShareController::class, 'create'])->name('events.social-share.create');
    Route::post('/events/{event}/share', [SocialShareController::class, 'store'])->name('events.social-share.store');
    Route::get('/events/{event}/share/statistics', [SocialShareController::class, 'statistics'])->name('events.social-share.statistics');


});

// Routes pour les badges volontaires
Route::middleware(['auth'])->group(function () {
    Route::get('/volunteers/{volunteer}/badge', [App\Http\Controllers\VolunteerBadgeController::class, 'showBadge'])->name('volunteers.badge.show');
    Route::get('/volunteers/{volunteer}/badge/download', [App\Http\Controllers\VolunteerBadgeController::class, 'generateBadge'])->name('volunteers.badge.download');
});

require __DIR__ . '/auth.php';
require __DIR__ . '/2fa.php';
// Routes auteur (blogs)
require __DIR__ . '/auteur.php';
require __DIR__ . '/review.php';
// Reservations & seating routes
require __DIR__ . '/reservations.php';