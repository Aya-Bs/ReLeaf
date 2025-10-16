<?php

use App\Http\Controllers\Auth\TwoFactorAuthController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    // Routes pour la configuration 2FA
    Route::get('/2fa/setup', [TwoFactorAuthController::class, 'show'])
        ->name('2fa.setup');

    Route::post('/2fa/enable', [TwoFactorAuthController::class, 'enable'])
        ->name('2fa.enable');

    Route::post('/2fa/disable', [TwoFactorAuthController::class, 'disable'])
        ->name('2fa.disable');

    // Routes pour la vérification 2FA
    Route::get('/2fa/verify', [TwoFactorAuthController::class, 'showVerification'])
        ->name('2fa.verify');

    Route::post('/2fa/verify', [TwoFactorAuthController::class, 'verify']);

    Route::post('/2fa/recovery', [TwoFactorAuthController::class, 'verifyRecovery'])
        ->name('2fa.recovery');
});
