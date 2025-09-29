<?php

use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\UserController as BackendUserController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ProfileController as FrontendProfileController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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
});

require __DIR__.'/auth.php';
require __DIR__.'/2fa.php';
require __DIR__.'/reservations.php';

// Routes AJAX pour le blocage temporaire des places
Route::middleware(['auth'])->prefix('ajax')->group(function () {
    Route::post('/seat/lock', [App\Http\Controllers\ReservationController::class, 'lockSeat'])->name('ajax.seat.lock');
    Route::post('/seat/release', [App\Http\Controllers\ReservationController::class, 'releaseSeat'])->name('ajax.seat.release');
    Route::get('/event/{event}/seats-status', [App\Http\Controllers\ReservationController::class, 'getSeatsStatus'])->name('ajax.seats.status');
});

// Routes publiques pour les événements
Route::get('/events', [App\Http\Controllers\EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [App\Http\Controllers\EventController::class, 'show'])->name('events.show');

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
