<?php
use App\Http\Controllers\BlogController;

Route::middleware(['auth'])->group(function () {
    // Dashboard Auteur
    Route::get('/auteur/dashboard', function () {
        return view('backend.Auteurdashboard');
    })->name('auteur.dashboard');


    // AllBlogs (cards) - doit être avant /auteur/blogs/{blog}
    Route::get('/auteur/blogs/cards', [BlogController::class, 'cards'])->name('auteur.blogs.cards');

    // CRUD Blog
    Route::get('/auteur/blogs', [BlogController::class, 'index'])->name('auteur.blogs.index');
    Route::get('/auteur/blogs/create', [BlogController::class, 'create'])->name('auteur.blogs.create');
    Route::post('/auteur/blogs', [BlogController::class, 'store'])->name('auteur.blogs.store');
    Route::get('/auteur/blogs/{blog}', [BlogController::class, 'show'])->name('auteur.blogs.show');
    Route::get('/auteur/blogs/{blog}/edit', [BlogController::class, 'edit'])->name('auteur.blogs.edit');
    Route::put('/auteur/blogs/{blog}', [BlogController::class, 'update'])->name('auteur.blogs.update');
    Route::delete('/auteur/blogs/{blog}', [BlogController::class, 'destroy'])->name('auteur.blogs.destroy');
});
