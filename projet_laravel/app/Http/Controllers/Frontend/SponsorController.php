<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSponsorRequest;
use App\Models\Sponsor;
use Illuminate\View\View;

class SponsorController extends Controller
{
    /**
     * Afficher la page de demande de sponsoring.
     */
    public function create(): View
    {
        return view('frontend.sponsors.create');
    }

    /**
     * Enregistrer une nouvelle demande de sponsoring.
     */
    public function store(StoreSponsorRequest $request)
    {
        Sponsor::create([
            'company_name' => $request->company_name,
            'contact_email' => $request->contact_email,
            'contact_phone' => $request->contact_phone,
            'website' => $request->website,
            'address' => $request->address,
            'city' => $request->city,
            'country' => $request->country,
            'motivation' => $request->motivation,
            'additional_info' => $request->additional_info,
            'sponsorship_type' => $request->sponsorship_type,
            'status' => 'pending',
        ]);

        return redirect()->route('sponsors.success')
            ->with('success', 'Votre demande de sponsoring a été envoyée avec succès. Vous recevrez une réponse par email dans les plus brefs délais.');
    }

    /**
     * Afficher la page de succès après demande.
     */
    public function success(): View
    {
        return view('frontend.sponsors.success');
    }

    /**
     * Afficher les sponsors validés (page publique).
     */
    public function index(): View
    {
        $sponsors = Sponsor::validated()
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('frontend.sponsors.index', compact('sponsors'));
    }

    /**
     * Afficher les détails d'un sponsor.
     */
    public function show(Sponsor $sponsor): View
    {
        if (! $sponsor->isValidated()) {
            abort(404);
        }

        return view('frontend.sponsors.show', compact('sponsor'));
    }
}
