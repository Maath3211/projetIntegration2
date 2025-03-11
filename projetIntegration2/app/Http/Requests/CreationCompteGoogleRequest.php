<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class CreationCompteGoogleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
            ],
            'prenom' => [
                'required',
            ],
            'nom' => [
                'required',
            ],
            'pays' => [
                'required',
                'not_in:Choisir'
            ],
            'genre' => [
                'required',
                'in:Homme,Femme,Prefere ne pas dire'
            ],
            'dateNaissance' => [
                'required',
                'date',
                'before:today',
                'after:1900-01-01'
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[a-z]/',     // at least one lowercase letter
                'regex:/[A-Z]/',     // at least one uppercase letter
                'regex:/[0-9]/',     // at least one number
            ],
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'L\'email est requis',
            'email.email' => 'L\'email doit être valide',
            'prenom.required' => 'Le prénom est requis',
            'nom.required' => 'Le nom est requis',
            'pays.required' => 'Le pays est requis',
            'pays.not_in' => 'Le pays est requis',
            'genre.required' => 'Le genre est requis',
            'genre.in' => 'Le genre sélectionné n\'est pas valide',
            'dateNaissance.required' => 'La date de naissance est requise',
            'dateNaissance.date' => 'La date de naissance n\'est pas valide',
            'dateNaissance.before' => 'La date de naissance doit être avant aujourd\'hui',
            'dateNaissance.after' => 'La date de naissance doit être après 1900-01-01',
            'password.required' => 'Le mot de passe est requis',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères',
            'password.confirmed' => 'Les mots de passe ne correspondent pas',
            'password.regex' => 'Le mot de passe doit contenir au moins une lettre minuscule, une lettre majuscule et un chiffre',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        Log::error('Validation failed', [
            'errors' => $validator->errors()->all()
        ]);

        parent::failedValidation($validator);
    }
}
