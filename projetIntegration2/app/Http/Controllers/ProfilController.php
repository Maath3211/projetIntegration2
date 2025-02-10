<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConnexionRequest;
use App\Http\Requests\CreationCompteRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;


class ProfilController extends Controller
{
    public function index()
    {
        return View('profil.connexion');
    }

    public function connexion(ConnexionRequest $request)
    {
        $reussi = Auth::guard()->attempt(['email' => $request->email, 'password' => $request->password]);
         if ($reussi) 
         {
             return redirect()->route('profil.profil');
         } 
         else 
         {
             return redirect()->back()->withErrors(['Informations invalides']);
         }
    }

    public function pageCreerCompte(){
        $countries = Cache::remember('countries_list_french', now()->addDay(), function () {
            return $this->listePays();
        });
        return View('profil.creerCompte', compact('countries'));
    }

    public function creerCompte(CreationCompteRequest $request){
        dd($request->all());
        return redirect()->route('profil.connexion');
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

    public function pageModification()
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
