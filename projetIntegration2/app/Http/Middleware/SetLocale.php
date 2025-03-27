<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class SetLocale
{
    /**
     * Traite une requête entrante et configure la langue de l'application.
     *
     * Ce middleware vérifie si une langue est définie dans la session et l'applique
     * à l'application avant que la requête ne soit traitée par les contrôleurs.
     *
     * @param  \Illuminate\Http\Request  $request  La requête HTTP entrante
     * @param  \Closure  $next  Fonction pour passer à l'étape suivante du pipeline de requête
     * @return \Symfony\Component\HttpFoundation\Response  La réponse HTTP
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifie si une langue est déjà définie dans la session de l'utilisateur
        if (Session::has('locale')) {
            // Récupère le code de langue depuis la session
            $locale = Session::get('locale');
            
            // Applique cette langue à l'application pour la requête en cours
            App::setLocale($locale);
            
            // Assure une gestion correcte de la session lorsque la langue change
            if ($request->session()->get('_previous_locale') !== $locale) {
                // Mémorise la langue actuelle pour comparaison lors des prochaines requêtes
                $request->session()->put('_previous_locale', $locale);
                
                // Régénère uniquement le jeton CSRF si nous ne sommes pas au milieu d'une soumission de formulaire
                // Cela évite les erreurs de validation CSRF lors des changements de langue pendant une soumission
                if (!$request->isMethod('POST') && !$request->isMethod('PUT') && !$request->isMethod('DELETE')) {
                    $request->session()->regenerateToken();
                }
            }
        }

        // Passe la requête au middleware suivant dans la chaîne ou au contrôleur final
        return $next($request);
    }
}
