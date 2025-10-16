<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Afficher le tableau de bord de l'utilisateur.
     */
    public function index(): View
    {
        $user = auth()->user();

        return view('dashboard', [
            'user' => $user,
        ]);
    }
}
