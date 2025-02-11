<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConnexionRequest;
use App\Http\Requests\CreationCompteRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;



class ProfilController extends Controller
{
    public function index()
    {
        return View('profil.connexion');
    }

    public function connexion(ConnexionRequest $request)
    {
        $reussi = Auth::guard()->attempt(['email' => $request->email, 'password' => $request->password]);
        if ($reussi) {
            return redirect()->route('profil.profil');
        } else {
            return redirect()->back()->withErrors(['Informations invalides']);
        }
    }

    public function creerCompte()
    {
        $countries = Cache::remember('countries_list_french', now()->addDay(), function () {
            return $this->listePays();
        });
        return View('profil.creerCompte', compact('countries'));
    }

    public function storeCreerCompte(CreationCompteRequest $request)
    {
        $utilisateur = new User();
        $utilisateur->email = $request->email;
        $utilisateur->prenom = $request->prenom;
        $utilisateur->nom = $request->nom;
        $utilisateur->imageProfil = $request->imageProfil;
        $utilisateur->pays = $request->pays;
        $utilisateur->genre = $request->genre;
        $utilisateur->dateNaissance = $request->dateNaissance;
        $utilisateur->password = bcrypt($request->password);

        if ($request->hasFile('imageProfil')) {
            if ($request->imageProfil) {
                $uploadedFile  =  $request->file('imageProfil');
                $nomFichierUnique  =  '/images/users/' . str_replace('  ',  '_',  $utilisateur->id)  .  '-'  .  uniqid()  .  '.'  .  $uploadedFile->extension();
                try {
                    $request->image->move(public_path('img/Utilisateurs'),  $nomFichierUnique);

                } catch (\Symfony\Component\HttpFoundation\File\Exception\FileException  $e) {
                    Log::error("Erreur lors du téléversement du fichier.", [$e]);
                }
                $utilisateur->image  =  $nomFichierUnique;
            }
        } else {
            return redirect()->route('fournisseur.importation')->withErrors(['error' => 'Aucune image à importer.']);
        }

        $utilisateur->save();
        return redirect()->route('profil.connexion')->with('message', 'Votre compte a été créé avec succès');
    }


    public function profil()
    {
        return View('profil.profil');
    }

    public function deconnexion()
    {
        Auth::guard()->logout();
        return redirect()->route('profil.pageConnexion');
    }

    public function modification()
    {
        // Cache the countries for 1 day
        $countries = Cache::remember('countries_list_french', now()->addDay(), function () {
            return $this->listePays();
        });
        return View('profil.modification', compact('countries'));
    }




    public function listePays()
    {
        $response = Http::get('https://restcountries.com/v3.1/all');

        if ($response->successful()) {
            return collect($response->json())->map(function ($country) {
                return [
                    'name' => $country['translations']['fra']['common'] ?? $country['name']['common'],
                    'code' => $country['cca2'],
                ];
            })->sortBy('name')->values()->all();
        }

        return [];
    }
}
