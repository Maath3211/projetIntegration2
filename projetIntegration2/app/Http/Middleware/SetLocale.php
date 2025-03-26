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
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Session::has('locale')) {
            $locale = Session::get('locale');
            App::setLocale($locale);
            
            // Ensure session is properly handled when locale changes
            if ($request->session()->get('_previous_locale') !== $locale) {
                $request->session()->put('_previous_locale', $locale);
                
                // Only regenerate CSRF token if we're not in the middle of a form submission
                if (!$request->isMethod('POST') && !$request->isMethod('PUT') && !$request->isMethod('DELETE')) {
                    $request->session()->regenerateToken();
                }
            }
        }

        return $next($request);
    }
}
