<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreationCompteRequest extends FormRequest
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
            'imageProfil' => [
                'required',
                'mimes:jpg,jpeg,png,bmp,webp,svg',
            ],
            'pays' => [
                'required',
                'not_in:Choisir'
            ],
            'genre' => [
                'required', 'in:Homme,Femme,Prefere ne pas dire',
            ],
            'dateNaissance' => [
                'required',
                'date',
            ],
            'password' => [
                'required',
                'min:8',
                'confirmed',
            ]
        ];
    }
    public function messages()
    {
        return
            [
                'email.required' => 'L\'email est requis',
                'email.email' => 'L\'email doit être valide',
                'prenom.required' => 'Le prénom est requis',
                'nom.required' => 'Le nom est requis',
                'imageProfil.required' => 'L\'image de profil est requise',
                'imageProfil.mimes' => 'L\'image de profil doit être un fichier image',
                'pays.not_in' => 'Le pays est requis',
                'pays.required' => 'Le pays est requis',
                'genre.required' => 'Le genre est requis',
                'genre.in' => 'Le genre sélectionné n\'est pas valide',
                'dateNaissance.required' => 'La date de naissance est requise',
                'dateNaissance.date' => 'La date de naissance doit être une date',
                'password.required' => 'Le mot de passe est requis',
                'password.length' => 'Le mot de passe doit contenir au moins 8 caractères',
                'password.confirmed' => 'La confirmation du mot de passe ne correspond pas'
            ];
    }
}
