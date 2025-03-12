<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ConnexionRequest;
use App\Http\Requests\CreationCompteGoogleRequest;
use App\Http\Requests\CreationCompteRequest;
use App\Http\Requests\ModificationRequest;
use App\Models\User;
use App\Models\Statistiques;
use App\Models\PoidsUtilisateur;
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
use App\Mail\confirmation;

class ProfilController extends Controller
{
    public function index()
    {
        return View('profil.connexion');
    }

    public function connexion(ConnexionRequest $request)
    {
        $utilisateur = User::where('email', $request->email)->first();
        if (!$utilisateur) {
            return redirect()->back()->withErrors(['email' => 'Informations invalides']);
        }
        if ($utilisateur->email_verified_at == null) {
            return redirect()->back()->withErrors(['email' => __('auth.compte_non_verifie')]);
        }
        $reussi = Auth::guard()->attempt(['email' => $request->email, 'password' => $request->password]);
        if ($reussi) {
            return redirect()->route('profil.profil');
        } else {
            return redirect()->back()->withErrors([__('auth.invalide_info')]);
        }
    }

    public function creerCompte()
    {
        $countries = $this->listePays();
        return View('profil.creerCompte', compact('countries'));
    }

    public function storeCreerCompte(CreationCompteRequest $request)
    {
        if (User::where('email', $request->email)->exists()) {
            return redirect()->route('profil.connexion')->withErrors([__('auth.courriel_existe')]);
        }
        $utilisateur = new User();
        $utilisateur->email = $request->email;
        $utilisateur->prenom = $request->prenom;
        $utilisateur->nom = $request->nom;
        $utilisateur->pays = $request->pays;
        $utilisateur->genre = $request->genre;
        $utilisateur->dateNaissance = $request->dateNaissance;
        $utilisateur->password = bcrypt($request->password);
        $utilisateur->codeVerification = Str::random(64);

        if ($request->hasFile('imageProfil')) {
            $uploadedFile = $request->file('imageProfil');
            $nomFichierUnique = 'img/Utilisateurs/' . uniqid() . '.' . $uploadedFile->extension();
            try {
                $uploadedFile->move(public_path('img/Utilisateurs'), $nomFichierUnique);
                $utilisateur->imageProfil = $nomFichierUnique;
            } catch (\Exception $e) {
                Log::error(__('profile.erreur_tel_image'), [$e]);
                return redirect()->back()->withErrors(['imageProfil' => __('profile.erreur_tel_image')]);
            }
        } else {
            $utilisateur->imageProfil = 'img/Utilisateurs/utilisateurParDefaut.jpg';
        }

        Mail::to($utilisateur->email)->send(new confirmation($utilisateur));
        $utilisateur->save();
        
        Statistiques::create([
            'user_id' => $utilisateur->id,
            'nomStatistique' => 'poids',
            'score' => 0
        ]);
        Statistiques::create([
            'user_id' => $utilisateur->id,
            'nomStatistique' => 'FoisGym',
            'score' => 0
        ]);
        PoidsUtilisateur::create([
            'user_id' => $utilisateur->id,
            'semaine' => 1,
            'poids' => 0
        ]);
        return redirect()->route('profil.connexion')->with('message', __('profile.confirmation_profil'));
    }

    public function creerCompteGoogle()
    {
        $countries = $this->listePays();
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
                if ($existingUser->email_verified_at == null) {
                    return redirect()->route('profil.connexion')->withErrors(['email' => __('auth.compte_non_verifie')]);
                }
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
                    'male' => __('auth.homme'),
                    'female' => __('auth.femme'),
                    default => __('auth.pas_indiquer')
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
            Log::error(__('auth.echec_connexion_google'), ['error' => $e->getMessage()]);
            return redirect()->route('profil.pageConnexion')
                ->withErrors([__('auth.echec_connextion_a_google')]);
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
        $utilisateur->codeVerification = Str::random(64);

        $googleData = session('google_data');
        if ($googleData && isset($googleData['image_url'])) {
            try {
                $imageContent = file_get_contents($googleData['image_url']);
                $nomFichierUnique = 'img/Utilisateurs/' . str_replace(' ', '_', $utilisateur->google_id) . '-' . uniqid() . '.jpg';

                if (file_put_contents(public_path($nomFichierUnique), $imageContent)) {
                    $utilisateur->imageProfil = $nomFichierUnique;
                } else {
                    Log::error(__('profile.image_google_sauv'));
                    return redirect()->back()->withErrors(['imageProfil' => __('profile.erreur_sauv_image')]);
                }
            } catch (\Exception $e) {
                Log::error(__('profile.image_google_telech'), [$e]);
                return redirect()->back()->withErrors(['imageProfil' => __('profile.erreur_tel_image')]);
            }
        } else {
            return redirect()->back()->withErrors(['imageProfil' => __('profile.image_google_erreur')]);
        }

        $utilisateur->save();
        if ($utilisateur->email_verified_at != null) {
            Auth::login($utilisateur);
            return redirect()->route('profil.profil');
        }

        Mail::to($utilisateur->email)->send(new confirmation($utilisateur));
        return redirect()->route('profil.connexion')->with('message', __('profile.confirmation_profil'));
    }

    public function profil()
    {
        $utilisateur = Auth::user();
        $clans = $utilisateur->clans; // Fetch all clans associated with the user
        return view('profil.profil', compact('utilisateur', 'clans'));
    }

    public function profilPublic($email)
    {
        $utilisateur = Auth::user();
        $clans = $utilisateur->clans;
        $utilisateur = User::where('email', $email)->first();
        $clansAway = $utilisateur->clans;

        if (!$utilisateur) {
            return redirect()->route('profil.profil')->withErrors([__('profile.utilisateur_non_trouve')]);
        }
        return View('profil.profil', compact('utilisateur', 'clansAway', 'clans'));
    }

    public function suppressionProfil()
    {
        $utilisateur = Auth::user();
        if ($utilisateur->imageProfil && file_exists(public_path($utilisateur->imageProfil))) {
            unlink(public_path($utilisateur->imageProfil));
        }
        $utilisateur->delete();
        return redirect()->route('profil.pageConnexion')->with('message', 'Votre compte a été supprimé avec succès');
    }


    public function deconnexion(Request $request)
    {
        Auth::guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('profil.pageConnexion');
    }

    public function modification()
    {
        $countries = $this->listePays();
        return view('profil.modification', compact('countries'));
    }

    public function updateModification(ModificationRequest $request)
    {
        // Get the authenticated user
        $user = Auth::user();
        // ... other validation and processing
        
        // Update basic user info
        $user->prenom = $request->prenom;
        $user->nom = $request->nom;
        $user->email = $request->email;
        $user->pays = $request->pays;  // Save the country name in the current locale
        $user->dateNaissance = $request->dateNaissance;
        $user->genre = $request->genre;
        $user->aPropos = $request->aPropos;
        
        // ... handle profile image if provided
        
        $user->save();
        
        return redirect()->back()->with('message', __('profile.profil_modifier_succes'));
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
            return redirect()->route('profil.connexion')->with('message', __('profile.message_succes'));
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
        return redirect()->route('profil.connexion')->with('message', __('profile.message_succes'));
    }

    public function showResetPasswordForm(string $token)
    {
        $tokenData = DB::table('password_reset_tokens')->where('token', $token)->first();
        if (!$tokenData) {
            return redirect('/connexion')->withErrors(['token' => __('profile.invalid_token')]);
        }
        return view('profil.reinitialisationMDP', ['token' => $token, 'email' => $tokenData->email]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
            'token' => 'required',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[a-z]/',     // at least one lowercase letter
                'regex:/[A-Z]/',     // at least one uppercase letter
                'regex:/[0-9]/',     // at least one number
            ],
        ], [
            'email.required' => __('profile.courriel_requis'),
            'email.email' => __('profile.courriel_entrer'),
            'password.required' => __('profile.mdp_required'),
            'password.min' => __('profile.mdp_min'),
            'password.confirmed' => __('profile.mdp_confirmed'),
            'password.regex' => 'Le mot de passe doit contenir au moins une lettre majuscule, une lettre minuscule et un chiffre',
            'token.required' => __('profile.token_requis')
        ]);

        $updatePassword = DB::table('password_reset_tokens')
            ->where([
                'email' => $request->email,
                'token' => $request->token
            ])->first();

        if (!$updatePassword) {
            return back()->withErrors(['email' => __('profile.invalid_token')]);
        }

        User::where('email', $request->email)
            ->update(['password' => bcrypt($request->password)]);

        DB::table('password_reset_tokens')->where(['email' => $request->email])->delete();

        return redirect('/connexion')->with('message', __('profile.mdp_update_success'));
    }

    public function confCourriel($codeVerification)
    {
        $user = User::where('codeVerification', $codeVerification)->first();
        if (!$user) {
            return redirect()->route('profil.connexion')->withErrors(['message' => 'Code de vérification invalide']);
        }
        $user->email_verified_at = now();
        $user->codeVerification = null;
        $user->save();
        return redirect()->route('profil.connexion')->with('message', __('auth.compte_verifie'));
    }

    public function listePays()
    {
        // Get current locale
        $locale = app()->getLocale();
        $cacheKey = 'countries_list_' . $locale;
        
        // Use locale-specific cache key
        return Cache::remember($cacheKey, now()->addDay(), function () use ($locale) {
            $response = Http::withoutVerifying()->get('https://restcountries.com/v3.1/all');
            
            if ($response->successful()) {
                return collect($response->json())->map(function ($country) use ($locale) {
                    // Get country name in current locale
                    if ($locale === 'fr') {
                        $name = $country['translations']['fra']['common'] ?? $country['name']['common'];
                    } else {
                        // Default to English
                        $name = $country['name']['common'];
                    }
                    
                    return [
                        'name' => $name,
                        'code' => $country['cca2'],
                    ];
                })->sortBy('name')->values()->all();
            }
            return [];
        });
    }
}
