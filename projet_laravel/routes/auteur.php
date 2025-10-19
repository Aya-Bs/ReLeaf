<?php
use App\Http\Controllers\BlogController;

Route::middleware(['auth'])->group(function () {

    // Tous les utilisateurs peuvent voir les blogs
    Route::get('/blogs', [BlogController::class, 'index'])->name('blogs.index');
    Route::get('/blogs/cards', [BlogController::class, 'cards'])->name('blogs.cards');

    // Organisateurs : créer et gérer leurs blogs
    Route::middleware(['can:organizer'])->group(function () {
        Route::get('/blogs/create', [BlogController::class, 'create'])->name('blogs.create');
        Route::post('/blogs', [BlogController::class, 'store'])->name('blogs.store');
        Route::get('/blogs/myblogs', [BlogController::class, 'myBlogs'])->name('blogs.myblogs');
    });
});