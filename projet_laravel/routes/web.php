<?php

use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\UserController as BackendUserController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ProfileController as FrontendProfileController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\CampaignController;
use App\Http\Controllers\Backend\ResourceController;
use App\Http\Controllers\Backend\EventController; 

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

/*
|--------------------------------------------------------------------------
| Frontend Routes (Utilisateurs connectés)
|--------------------------------------------------------------------------
*/
// Routes 2FA
require __DIR__.'/2fa.php';

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
    // Page de bienvenue admin
    Route::get('/', [AdminController::class, 'welcome'])->name('welcome');

    // Dashboard admin
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Gestion des utilisateurs
    Route::resource('users', BackendUserController::class);
    Route::post('users/{user}/toggle-eco-ambassador', [BackendUserController::class, 'toggleEcoAmbassador'])
        ->name('users.toggle-eco-ambassador');

    Route::get('/events', function () {
        $events = \App\Models\Event::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('backend.events.index', compact('events'));
    })->name('events.index');

    Route::get('/events/{event}', function (\App\Models\Event $event) {
        $event->load('user'); // Charger la relation user
        return view('backend.events.show', compact('event'));
    })->name('events.show');
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
});



/*
|--------------------------------------------------------------------------
| ROUTES EVENTS & LOCATIONS
|--------------------------------------------------------------------------
*/Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('events', EventController::class);
    Route::post('/events/{event}/submit', [EventController::class, 'submitForApproval'])->name('events.submit');
    Route::post('/events/{event}/cancel', [EventController::class, 'cancel'])->name('events.cancel');
    Route::post('/events/{event}/remove-image', [EventController::class, 'removeImage'])->name('events.remove-image');
});

require __DIR__.'/auth.php';
require __DIR__.'/2fa.php';
