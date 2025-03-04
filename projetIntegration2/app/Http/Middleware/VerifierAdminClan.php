<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Clan;

class VerifierAdminClan
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
        if(!$utilisateur){
            return redirect('/')->with('erreur', 'Vous devez être connectés pour faire cette action.');
        }

        // si le clan n'existe pas
        if(!$clan){
            return redirect('/profil')->with('erreur', 'Ce clan n\'existe pas.');
        }

        // si l'utilisateur n'est pas l'admin
        if($clan->adminId != $utilisateur->id){
            return redirect('/profil')->with('erreur', 'Vous n\'êtes pas autorisé à faire cette action.');
        }

        return $next($request);
    }
}
