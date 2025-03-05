<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Clan;

class VerifierMembreClan
{
    /**
     * 
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $utilisateur = Auth::user();
        $clan = Clan::find($request->route('id'));

        // s'il n'est pas connecté
        if(!$utilisateur){
            return redirect('/')->with('erreur', 'Vous devez être connectés pour faire cette action.');
        }

        // si le clan n'existe pas
        if(!$clan){
            return redirect('/profil')->with('erreur', 'Ce clan n\'existe pas.');
        }

        // s'il n'est pas membre du clan
        if(!$utilisateur->clans()->wherePivot('clan_id', $clan->id)->exists()){
            return redirect('/profil')->with('erreur', 'Vous n\'êtes pas autorisé à faire cette action.');
        }

        return $next($request);
    }
}
