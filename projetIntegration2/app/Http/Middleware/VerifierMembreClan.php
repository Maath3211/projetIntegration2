<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class VerifierMembreClan
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $utilisateur = Auth::user();
        $clan = $request->route('clan');

        if(!$utilisateur || !$clan || !$utilisateur->clans()->contains($clan->id)){
            return redirect('/')->with('error', 'Vous ne faites pas partie de ce clan.');
        }

        return $next($request);
    }
}
