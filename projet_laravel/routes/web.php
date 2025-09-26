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
