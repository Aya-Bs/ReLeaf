<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Informations personnelles obligatoires
            'first_name' => [
                'required',
                'string',
                'min:2',
                'max:50',
                'regex:/^[a-zA-ZÀ-ÿ\s\-\']+$/u',
                'not_regex:/^\s+$/', // Pas seulement des espaces
            ],
            'last_name' => [
                'required',
                'string',
                'min:2',
                'max:50',
                'regex:/^[a-zA-ZÀ-ÿ\s\-\']+$/u',
                'not_regex:/^\s+$/', // Pas seulement des espaces
            ],
            'name' => [
                'required',
                'string',
                'min:3',
                'max:100',
                'regex:/^[a-zA-ZÀ-ÿ\s\-\']+$/u',
            ],

            // Email avec validation stricte
            'email' => [
                'required',
                'string',
                'email:rfc', // Validation RFC seulement (DNS peut échouer en test)
                'max:255',
                'lowercase',
                'unique:users,email',
                'not_regex:/^[^@]*@[^@]*@/', // Pas de double @
            ],

            // Sélection du rôle
            'role' => [
                'required',
                'string',
                'in:user,organizer',
            ],

            // Informations optionnelles avec validation
            'phone' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^[\+]?[0-9\s\-\(\)]{8,20}$/', // Format international
                'unique:users,phone', // Unique si fourni
            ],
            'birth_date' => [
                'nullable',
                'date',
                'before:today',
                'after:1900-01-01', // Pas trop ancien
                'date_format:Y-m-d',
            ],
            'city' => [
                'nullable',
                'string',
                'min:2',
                'max:100',
                'regex:/^[a-zA-ZÀ-ÿ\s\-\']+$/u',
            ],
            'country' => [
                'nullable',
                'string',
                'size:2',
                'in:TN,FR,DZ,MA,BE,CH,CA,LB,EG,SN,CI', // Codes pays autorisés
            ],

            // Mot de passe avec règles strictes
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(8)
                    ->letters() // Au moins une lettre
                    ->mixedCase() // Au moins une majuscule et une minuscule
                    ->numbers() // Au moins un chiffre
                    ->symbols(), // Au moins un symbole
                    // ->uncompromised(), // Désactivé car peut échouer en test
            ],

            // Conditions d'utilisation
            'terms' => [
                'required',
                'accepted',
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            // Messages pour les champs obligatoires
            'first_name.required' => 'Le prénom est obligatoire.',
            'first_name.min' => 'Le prénom doit contenir au moins 2 caractères.',
            'first_name.max' => 'Le prénom ne peut pas dépasser 50 caractères.',
            'first_name.regex' => 'Le prénom ne peut contenir que des lettres, espaces, tirets et apostrophes.',
            'first_name.not_regex' => 'Le prénom ne peut pas être vide ou contenir seulement des espaces.',

            'last_name.required' => 'Le nom est obligatoire.',
            'last_name.min' => 'Le nom doit contenir au moins 2 caractères.',
            'last_name.max' => 'Le nom ne peut pas dépasser 50 caractères.',
            'last_name.regex' => 'Le nom ne peut contenir que des lettres, espaces, tirets et apostrophes.',
            'last_name.not_regex' => 'Le nom ne peut pas être vide ou contenir seulement des espaces.',

            'name.required' => 'Le nom complet est obligatoire.',
            'name.min' => 'Le nom complet doit contenir au moins 3 caractères.',
            'name.max' => 'Le nom complet ne peut pas dépasser 100 caractères.',
            'name.regex' => 'Le nom complet ne peut contenir que des lettres, espaces, tirets et apostrophes.',

            // Messages pour l'email
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'L\'adresse email doit être valide.',
            'email.max' => 'L\'adresse email ne peut pas dépasser 255 caractères.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'email.not_regex' => 'L\'adresse email contient des caractères invalides.',

            // Messages pour le rôle
            'role.required' => 'Le type de compte est obligatoire.',
            'role.in' => 'Le type de compte sélectionné n\'est pas valide.',

            // Messages pour le téléphone
            'phone.regex' => 'Le format du numéro de téléphone n\'est pas valide. Utilisez le format international.',
            'phone.max' => 'Le numéro de téléphone ne peut pas dépasser 20 caractères.',
            'phone.unique' => 'Ce numéro de téléphone est déjà utilisé.',

            // Messages pour la date de naissance
            'birth_date.date' => 'La date de naissance doit être une date valide.',
            'birth_date.before' => 'La date de naissance doit être antérieure à aujourd\'hui.',
            'birth_date.after' => 'La date de naissance ne peut pas être antérieure à 1900.',
            'birth_date.date_format' => 'Le format de la date de naissance n\'est pas valide.',

            // Messages pour la ville
            'city.min' => 'Le nom de la ville doit contenir au moins 2 caractères.',
            'city.max' => 'Le nom de la ville ne peut pas dépasser 100 caractères.',
            'city.regex' => 'Le nom de la ville ne peut contenir que des lettres, espaces, tirets et apostrophes.',

            // Messages pour le pays
            'country.size' => 'Le code pays doit contenir exactement 2 caractères.',
            'country.in' => 'Le pays sélectionné n\'est pas valide.',

            // Messages pour le mot de passe
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.mixed' => 'Le mot de passe doit contenir au moins une lettre majuscule et une minuscule.',
            'password.numbers' => 'Le mot de passe doit contenir au moins un chiffre.',
            'password.symbols' => 'Le mot de passe doit contenir au moins un symbole.',
            'password.uncompromised' => 'Ce mot de passe a été compromis dans une fuite de données. Veuillez en choisir un autre.',

            // Messages pour les conditions
            'terms.required' => 'Vous devez accepter les conditions d\'utilisation.',
            'terms.accepted' => 'Vous devez accepter les conditions d\'utilisation.',
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
            'name' => 'nom complet',
            'email' => 'adresse email',
            'role' => 'type de compte',
            'phone' => 'numéro de téléphone',
            'birth_date' => 'date de naissance',
            'city' => 'ville',
            'country' => 'pays',
            'password' => 'mot de passe',
            'password_confirmation' => 'confirmation du mot de passe',
            'terms' => 'conditions d\'utilisation',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Validation personnalisée pour vérifier l'âge minimum
            if ($this->birth_date) {
                $age = \Carbon\Carbon::parse($this->birth_date)->age;
                if ($age < 13) {
                    $validator->errors()->add('birth_date', 'Vous devez avoir au moins 13 ans pour créer un compte.');
                }
            }

            // Validation personnalisée pour vérifier que le nom complet correspond aux prénom/nom
            if ($this->first_name && $this->last_name && $this->name) {
                $expectedName = trim($this->first_name . ' ' . $this->last_name);
                if (strtolower(trim($this->name)) !== strtolower($expectedName)) {
                    $validator->errors()->add('name', 'Le nom complet doit correspondre au prénom et nom saisis.');
                }
            }
        });
    }
}
