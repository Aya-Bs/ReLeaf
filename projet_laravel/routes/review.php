<?php

use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::get('/reviews/{id}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
Route::get('/reviews/{blogId}', [ReviewController::class, 'index']);
Route::get('/reviews/{id}', [ReviewController::class, 'show']);
Route::post('/reviews/{blog}', [ReviewController::class, 'store'])->name('reviews.store');
Route::put('/reviews/{id}', [ReviewController::class, 'update'])->name('reviews.update');
Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
