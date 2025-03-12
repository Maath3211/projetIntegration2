<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Clan;
use Illuminate\Support\Facades\Log;

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
        $clan = Clan::find($request->route('id'));

        // s'il n'est pas connecté
        if (!$utilisateur) {
            return redirect('/')->with('erreur', __('middleware.must_be_logged_in'));
        }

        // si le clan n'existe pas
        if (!$clan) {
            return redirect('/profil')->with('erreur', __('middleware.clan_not_exist'));
        }

        // s'il n'est pas membre du clan
        if (!$utilisateur->clans()->wherePivot('clan_id', $clan->id)->exists()) {
            return redirect('/profil')->with('erreur', __('middleware.not_clan_member'));
        }

        return $next($request);
    }
}
