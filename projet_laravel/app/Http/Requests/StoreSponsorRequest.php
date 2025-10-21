<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSponsorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Anyone can request to become a sponsor
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'company_name' => 'bail|required|string|max:255',
            'contact_email' => 'bail|required|email:rfc,dns|unique:sponsors,contact_email',
            'contact_phone' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'motivation' => 'required|string|min:50|max:2000',
            'additional_info' => 'nullable|string|max:1000',
            'sponsorship_type' => 'required|in:argent,materiel,service',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'company_name.required' => 'Le nom de l\'entreprise est obligatoire.',
            'contact_email.required' => 'L\'adresse email de contact est obligatoire.',
            'contact_email.email' => 'L\'adresse email doit être valide.',
            'contact_email.unique' => 'Cette adresse email est déjà utilisée.',
            'motivation.required' => 'La motivation du partenariat est obligatoire.',
            'motivation.min' => 'La motivation doit contenir au moins 50 caractères.',
            'sponsorship_type.required' => 'Le type de sponsoring est obligatoire.',
            'sponsorship_type.in' => 'Le type de sponsoring doit être argent, matériel ou service.',
        ];
    }

    /**
     * Custom attributes for nicer field names.
     */
    public function attributes(): array
    {
        return [
            'company_name' => "nom de l'entreprise",
            'contact_email' => 'email de contact',
            'contact_phone' => 'téléphone',
            'website' => 'site web',
            'motivation' => 'motivation',
            'sponsorship_type' => 'type de sponsoring',
        ];
    }
}
