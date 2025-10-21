<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreDonationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Anyone can make a donation
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'donor_name' => 'bail|required|string|max:255',
            'donor_email' => 'bail|required|email:rfc,dns|max:255',
            'amount' => 'bail|required|numeric|min:1|max:100000',
            'currency' => 'bail|required|string|in:EUR,USD,TND',
            'type' => 'bail|required|in:sponsor,individual',
            'payment_method' => 'bail|required|string|in:card,paypal,bank_transfer,check',
            'notes' => 'nullable|string|max:500',
        ];

        // If donation type is sponsor:
        //  - Logged in users with role 'sponsor' never need to type a sponsor_name (even if relation missing now)
        //  - Everyone else must provide sponsor_name
        if ($this->input('type') === 'sponsor') {
            $requireSponsorName = true;
            if (Auth::check() && Auth::user()->role === 'sponsor') {
                $requireSponsorName = false;
            }
            if ($requireSponsorName) {
                $rules['sponsor_name'] = 'required|string|max:255';
            }
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'donor_name.required' => 'Votre nom est obligatoire.',
            'donor_email.required' => 'Votre email est obligatoire.',
            'donor_email.email' => 'Votre email doit être valide.',
            'amount.required' => 'Le montant du don est obligatoire.',
            'amount.numeric' => 'Le montant doit être un nombre.',
            'amount.min' => 'Le montant minimum est de 1.',
            'amount.max' => 'Le montant maximum est de 100,000.',
            'currency.required' => 'La devise est obligatoire.',
            'currency.in' => 'La devise doit être EUR, USD, ou TND.',
            'type.required' => 'Le type de don est obligatoire.',
            'type.in' => 'Le type doit être sponsor ou individual.',
            'sponsor_name.required' => 'Le nom du sponsor est requis (ou votre compte sponsor doit être associé).',
            'payment_method.required' => 'La méthode de paiement est obligatoire.',
            'payment_method.in' => 'La méthode de paiement doit être Carte bancaire, PayPal, Virement ou Chèque.',
        ];
    }

    /**
     * Custom attributes for nicer field names in errors.
     */
    public function attributes(): array
    {
        return [
            'donor_name' => 'nom',
            'donor_email' => 'email',
            'amount' => 'montant',
            'currency' => 'devise',
            'payment_method' => 'méthode de paiement',
            'sponsor_name' => 'nom du sponsor',
        ];
    }
}
