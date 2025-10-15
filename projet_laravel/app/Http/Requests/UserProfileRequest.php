<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UserProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10', 'max:20'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'birth_date' => ['nullable', 'date', 'before:today', 'after:1900-01-01'],
            'city' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'interests' => ['nullable', 'array'],
            'interests.*' => ['string', 'max:100'],
            'notification_preferences' => ['required', Rule::in(['email', 'sms', 'both', 'none'])],
            'is_eco_ambassador' => ['boolean'],
        ];

        // If user is sponsor allow sponsor company fields (optional edits)
        if (Auth::check() && Auth::user()->role === 'sponsor') {
            $rules = array_merge($rules, [
                'company_name' => ['sometimes', 'string', 'max:255'],
                'contact_email' => ['sometimes', 'email', 'max:255'],
                'contact_phone' => ['sometimes', 'nullable', 'string', 'max:50'],
                'website' => ['sometimes', 'nullable', 'url', 'max:255'],
                'address' => ['sometimes', 'nullable', 'string', 'max:255'],
                'motivation' => ['sometimes', 'nullable', 'string', 'max:500'],
                'additional_info' => ['sometimes', 'nullable', 'string', 'max:1000'],
            ]);
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'phone.regex' => 'Le format du numéro de téléphone n\'est pas valide.',
            'avatar.image' => 'Le fichier doit être une image.',
            'avatar.mimes' => 'L\'avatar doit être au format jpeg, png, jpg ou gif.',
            'avatar.max' => 'L\'avatar ne doit pas dépasser 2MB.',
            'birth_date.before' => 'La date de naissance doit être antérieure à aujourd\'hui.',
            'birth_date.after' => 'La date de naissance doit être postérieure à 1900.',
            'interests.*.max' => 'Chaque intérêt ne doit pas dépasser 100 caractères.',
            'notification_preferences.in' => 'Les préférences de notification doivent être : email, sms, both ou none.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'first_name' => 'prénom',
            'last_name' => 'nom',
            'phone' => 'téléphone',
            'bio' => 'biographie',
            'avatar' => 'avatar',
            'birth_date' => 'date de naissance',
            'city' => 'ville',
            'country' => 'pays',
            'interests' => 'centres d\'intérêt',
            'notification_preferences' => 'préférences de notification',
            'is_eco_ambassador' => 'ambassadeur écologique',
        ];
    }
}
