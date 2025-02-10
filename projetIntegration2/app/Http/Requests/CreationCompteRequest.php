<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreationCompteRequest extends FormRequest
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
                
            ],
            'pays' => [
                'required',
            ],
            'genre' => [
                'required', 'in:Homme,Femme,Prefere ne pas dire',
            ],
            'dateNaissance' => [
                'required',
            ],
            'password' => [
                'required',
                'length:8',
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
                'pays.required' => 'Le pays est requis',
                'genre.required' => 'Le genre est requis',
                'genre.in' => 'Le genre doit être Homme, Femme ou Prefere ne pas dire',
                'dateNaissance.required' => 'La date de naissance est requise',
                'password.required' => 'Le mot de passe est requis',
                'password.length' => 'Le mot de passe doit contenir au moins 8 caractères',
                'password.confirmed' => 'La confirmation du mot de passe ne correspond pas'
            ];
    }
}
