<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ModificationRequest extends FormRequest
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
                'image',
                'max:2048',
                'mimes:jpeg,png,jpg,gif,svg,bmp,webp',
            ],
            'pays' => [
                'required',
                'not_in:Choisir'
            ],
            'genre' => [
                'required',
                'in:Homme,Femme,Prefere ne pas dire',
            ],
            'dateNaissance' => [
                'required',
                'date',
                'before:today',
                'after:1900-01-01'
            ],
            'aPropos' => [
                'nullable',
                'string',
                'max:128',
            ],
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
                'imageProfil.max' => 'L\'image de profil doit être un fichier image de moins de 2 Mo',
                'imageProfil.mimes' => 'L\'image de profil doit être un fichier image',
                'pays.not_in' => 'Le pays est requis',
                'pays.required' => 'Le pays est requis',
                'genre.required' => 'Le genre est requis',
                'genre.in' => 'Le genre sélectionné n\'est pas valide',
                'dateNaissance.required' => 'La date de naissance est requise',
                'dateNaissance.date' => 'La date de naissance doit être une date',
                'dateNaissance.before' => 'La date de naissance doit être avant aujourd\'hui',
                'dateNaissance.after' => 'La date de naissance doit être après 1900-01-01',
                'aPropos.max' => 'La description doit être de 128 caractères maximum',
            ];
    }
}
