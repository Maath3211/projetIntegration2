<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class TraductionController extends Controller
{
    /**
     * Définit la langue de l'application.
     *
     * @param  string  $locale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setLocale($locale)
    {
        // Vérifie que la langue demandée est supportée par l'application
        if (in_array($locale, ['en', 'fr'])) {
            // Enregistre la langue choisie dans la session de l'utilisateur
            Session::put('locale', $locale);
            // Applique immédiatement la nouvelle langue à l'application
            App::setLocale($locale);

            // Facultatif: Journalisation pour le débogage
            Log::info('Langue de session maintenant: ' . Session::get('locale'));
            Log::info('Langue de l'application maintenant: ' . App::getLocale());
        }

        // Redirige l'utilisateur vers la page précédente avec la nouvelle langue appliquée
        return redirect()->back();
    }
}
