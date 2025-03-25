<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class TraductionController extends Controller
{
    /**
     * Set the application locale.
     *
     * @param  string  $locale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setLocale($locale)
    {
        // Validate that the locale is supported
        if (in_array($locale, ['en', 'fr'])) {
            Session::put('locale', $locale);
            App::setLocale($locale);

            // Optional: Log for debugging
            Log::info('Session locale now: ' . Session::get('locale'));
            Log::info('App locale now: ' . App::getLocale());
        }

        return redirect()->back();
    }
}
