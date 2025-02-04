<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConnexionRequest;
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

    public function profil()
    {
        return View('profil.profil');
    }

    public function pageModification()
    {
        // Cache the countries for 1 day
        $countries = Cache::remember('countries_list_french', now()->addDay(), function () {
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
        });
        return View('profil.modification', compact('countries'));
    }
}
