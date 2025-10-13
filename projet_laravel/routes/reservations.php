<?php

use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;

// Routes pour les utilisateurs
Route::middleware(['auth'])->group(function () {
    Route::get('/events/{event}/seats', [ReservationController::class, 'showSeats'])->name('events.seats');
    Route::post('/events/{event}/reserve', [ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/reservations/{reservation}/confirmation', [ReservationController::class, 'confirmation'])->name('reservations.confirmation');
    Route::post('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');
});

// Routes pour les admins
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/reservations', [ReservationController::class, 'adminIndex'])->name('reservations.index');
    Route::post('/reservations/{reservation}/confirm', [ReservationController::class, 'confirm'])->name('reservations.confirm');
    Route::post('/reservations/{reservation}/reject', [ReservationController::class, 'reject'])->name('reservations.reject');
    Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');
});
