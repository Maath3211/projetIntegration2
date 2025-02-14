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
use Laravel\Socialite\Facades\Socialite;



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

    public function connexionGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function googleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            dd($googleUser);
            $user = User::where('google_id', $googleUser->getId())->first();

            if (!$user || $user == null) {
                  // Store Google data in session
            session([
                'google_data' => [
                    'email' => $googleUser->getEmail(),
                    'prenom' => $googleUser->user['given_name'],
                    'nom' => $googleUser->user['family_name'],
                    'image_url' => $googleUser->getAvatar(),
                    'google_id' => $googleUser->getId()
                ]
            ]);
            
            return redirect()->route('profil.creerCompteGoogle');
            }
    
            else if($user->google_id == $googleUser->getId()) {
                Auth::login($user);
                return redirect('/profil');
            }
            else{
                return redirect()->route('profil.pageConnexion')->withErrors(['La connexion avec Google a échoué 1']);
            }
    
        } catch (\Exception $e) {
            Log::error('Google login failed', [$e]);
            return redirect()->route('profil.pageConnexion')->withErrors(['La connexion avec Google a échoué 2']);
        }
    }

    public function creerCompteGoogle(){
        $countries = Cache::remember('countries_list_french', now()->addDay(), function () {
            return $this->listePays();
        });
        return view('profil.creerCompteGoogle', compact('countries'));
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
            $uploadedFile = $request->file('imageProfil');
            $nomFichierUnique = 'img/Utilisateurs/' . str_replace(' ', '_', $utilisateur->id) . '-' . uniqid() . '.' . $uploadedFile->extension();
            try {
                $uploadedFile->move(public_path('img/Utilisateurs'), $nomFichierUnique);
                $utilisateur->imageProfil = $nomFichierUnique;
            } catch (\Exception $e) {
                Log::error("Erreur lors du téléversement du fichier.", [$e]);
                return redirect()->back()->withErrors(['imageProfil' => 'Erreur lors du téléversement de l\'image']);
            }
        } else {
            return redirect()->back()->withErrors(['imageProfil' => 'Aucune image sélectionnée']);
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

        $response = Http::withoutVerifying()->get('https://restcountries.com/v3.1/all');
        // !! remettre après verification !!         $response = Http::get('https://restcountries.com/v3.1/all');

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
