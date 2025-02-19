<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ConnexionRequest;
use App\Http\Requests\CreationCompteGoogleRequest;
use App\Http\Requests\CreationCompteRequest;
use App\Http\Requests\ModificationRequest;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\reinitialisation;

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
            $uploadedFile = $request->file('imageProfil');
            $nomFichierUnique = 'img/Utilisateurs/' . uniqid() . '.' . $uploadedFile->extension();
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

    public function creerCompteGoogle()
    {
        $countries = Cache::remember('countries_list_french', now()->addDay(), function () {
            return $this->listePays();
        });
        return view('profil.creerCompteGoogle', compact('countries'));
    }

    public function connexionGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function googleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $existingUser = User::where('google_id', $googleUser->getId())
                ->orWhere('email', $googleUser->getEmail())
                ->first();

            if ($existingUser) {
                Auth::login($existingUser);
                return redirect()->route('profil.profil');
            }

            $client = new \Google_Client();
            $client->setAccessToken($googleUser->token);
            $peopleService = new \Google_Service_PeopleService($client);
            $profile = $peopleService->people->get('people/me', [
                'personFields' => 'addresses,birthdays,genders,locations'
            ]);

            $birthday = null;
            if ($profile->getBirthdays()) {
                foreach ($profile->getBirthdays() as $bday) {
                    if ($bday->getDate() && $bday->getDate()->getYear()) {
                        $birthday = sprintf(
                            '%d-%02d-%02d',
                            $bday->getDate()->getYear(),
                            $bday->getDate()->getMonth(),
                            $bday->getDate()->getDay()
                        );
                        break;
                    }
                }
            }

            $gender = null;
            if ($profile->getGenders()) {
                $gender = $profile->getGenders()[0]->getValue();
                $gender = match ($gender) {
                    'male' => 'Homme',
                    'female' => 'Femme',
                    default => 'Prefere ne pas dire'
                };
            }

            session([
                'google_data' => [
                    'email' => $googleUser->getEmail(),
                    'prenom' => $googleUser->user['given_name'],
                    'nom' => $googleUser->user['family_name'],
                    'image_url' => $googleUser->getAvatar(),
                    'google_id' => $googleUser->getId(),
                    'dateNaissance' => $birthday,
                    'genre' => $gender
                ]
            ]);

            return redirect()->route('profil.creerCompteGoogle');
        } catch (\Exception $e) {
            Log::error('Google login failed', ['error' => $e->getMessage()]);
            return redirect()->route('profil.pageConnexion')
                ->withErrors(['La connexion avec Google a échoué']);
        }
    }

    public function storeCreerCompteGoogle(CreationCompteGoogleRequest $request)
    {
        $utilisateur = new User();
        $utilisateur->email = $request->email;
        $utilisateur->prenom = $request->prenom;
        $utilisateur->nom = $request->nom;
        $utilisateur->pays = $request->pays;
        $utilisateur->genre = $request->genre;
        $utilisateur->dateNaissance = $request->dateNaissance;
        $utilisateur->password = bcrypt($request->password);
        $utilisateur->google_id = session('google_data.google_id');

        $googleData = session('google_data');
        if ($googleData && isset($googleData['image_url'])) {
            try {
                $imageContent = file_get_contents($googleData['image_url']);
                $nomFichierUnique = 'img/Utilisateurs/' . str_replace(' ', '_', $utilisateur->google_id) . '-' . uniqid() . '.jpg';

                if (file_put_contents(public_path($nomFichierUnique), $imageContent)) {
                    $utilisateur->imageProfil = $nomFichierUnique;
                } else {
                    Log::error("Erreur lors de la sauvegarde de l'image Google");
                    return redirect()->back()->withErrors(['imageProfil' => "Erreur lors de la sauvegarde de l'image"]);
                }
            } catch (\Exception $e) {
                Log::error("Erreur lors du téléchargement de l'image Google", [$e]);
                return redirect()->back()->withErrors(['imageProfil' => "Erreur lors du téléchargement de l'image"]);
            }
        } else {
            return redirect()->back()->withErrors(['imageProfil' => 'Aucune image Google trouvée']);
        }

        $utilisateur->save();
        Auth::login($utilisateur);
        return redirect()->route('profil.profil');

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

    public function updateModification(ModificationRequest $request)
    {
        $utilisateur = Auth::user();
        $utilisateur->prenom = $request->input('prenom');
        $utilisateur->nom = $request->input('nom');
        $utilisateur->pays = $request->input('pays');
        $utilisateur->genre = $request->input('genre');
        $utilisateur->dateNaissance = $request->input('dateNaissance');

        if ($request->hasFile('imageProfil')) {
            if ($utilisateur->imageProfil && file_exists(public_path($utilisateur->imageProfil))) {
                unlink(public_path($utilisateur->imageProfil));
            }

            $uploadedFile = $request->file('imageProfil');
            $nomFichierUnique = 'img/Utilisateurs/' . str_replace(' ', '_', $utilisateur->id) . '-' . uniqid() . '.' . $uploadedFile->extension();
            try {
                $uploadedFile->move(public_path('img/Utilisateurs'), $nomFichierUnique);
                $utilisateur->imageProfil = $nomFichierUnique;
            } catch (\Exception $e) {
                Log::error("Erreur lors du téléversement du fichier.", [$e]);
                return redirect()->back()->withErrors(['imageProfil' => 'Erreur lors du téléversement de l\'image']);
            }
        }

        $utilisateur->save();
        return redirect()->route('profil.profil')->with('message', 'Votre profil a été mis à jour avec succès');
    }




    public function pageMotDePasseOublie()
    {
        return View('profil.reinitialisation');
    }

    public function emailReinitialisation()
    {
        return View('emails.motDePasseOublie');
    }

    public function motDePasseOublieEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return redirect()->route('profil.connexion')->with('message', 'Un courriel de reinitialisation a été envoyé si le compte existe');
        }

        $token = Str::random(64);
        
        $existingToken = DB::table('password_reset_tokens')->where('email', $request->email)->first();
        if ($existingToken) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        }

        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        Mail::to($request->email)->send(new reinitialisation($token));
        return redirect()->route('profil.connexion')->with('message', 'Un courriel de reinitialisation a été envoyé si le compte existe');
    }

    public function showResetPasswordForm(string $token)
    {
        $tokenData = DB::table('password_reset_tokens')->where('token', $token)->first();
        if (!$tokenData) {
            return redirect('/connexion')->withErrors(['token' => 'Token invalide!']);
        }
        return view('profil.reinitialisationMDP', ['token' => $token, 'email' => $tokenData->email]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
            'token' => 'required'
        ], [
            'email.required' => 'Le courriel est requis',
            'email.email' => 'Le format du courriel est invalide',
            'password.required' => 'Le mot de passe est requis',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas',
            'token.required' => 'Le jeton de réinitialisation est requis'
        ]);

        $updatePassword = DB::table('password_reset_tokens')
            ->where([
                'email' => $request->email,
                'token' => $request->token
            ])->first();

        if (!$updatePassword) {
            return back()->withErrors(['email' => 'Token invalide!']);
        }

        User::where('email', $request->email)
            ->update(['password' => bcrypt($request->password)]);

        DB::table('password_reset_tokens')->where(['email' => $request->email])->delete();

        return redirect('/connexion')->with('message', 'Mot de passe mis à jour!');
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
