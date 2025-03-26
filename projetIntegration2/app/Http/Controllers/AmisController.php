<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Utilisé pour la recherche d'amis
use App\Models\Clan; // Utilisé pour la recherche de clans
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AmisController extends Controller
{
    // Affiche le formulaire de recherche
    public function index()
    {
        $utilisateur = auth()->id();
        $utilisateur = User::findOrFail($utilisateur);

        // Si l'utilisateur est pas connecté
        if (!$utilisateur){
            Log::info('Utilisateur pas connecté.');
            return redirect('/connexion')->with('erreur', 'Vous devez être connectés pour afficher les clans.');
        }

        //obtenir les clans dont l'utilisateur fait partie
        $clans = $utilisateur->clans()->get();


        return view('amis.index', compact('clans'));
    }

    // Recherche les utilisateurs par nom d'utilisateur
    public function recherche(Request $request)
    {
        $q = $request->input('q');
        
        if(empty($q)) {
            return redirect()->back()->withErrors([__('friends.search_required')]);
        }

        $request->validate([
            'q' => 'required|string'
        ]);

        $query = $request->input('q');

        // Utiliser l'utilisateur authentifié ou l'ID 999 pour les tests
        $userId = auth()->check() ? auth()->user()->id : 999;

        // Définir la variable utilisateurConnecteId
        $utilisateurConnecteId = $userId;

        // Récupérer la liste des IDs des amis déjà ajoutés par l'utilisateur
        $friendIds = \DB::table('amis') // Remplacer "amis" par "user_ami"
            ->where('user_id', $userId)
            ->pluck('friend_id')
            ->toArray();

        // S'assurer d'exclure également l'utilisateur lui-même
        $friendIds[] = $userId;

        // Rechercher les utilisateurs dont l'email correspond à la recherche et qui ne sont pas déjà amis
        $utilisateurs = User::where('email', 'like', "%{$query}%")
            ->whereNotIn('id', $friendIds)
            ->get();

        

        return view('amis.index', compact('utilisateurs', 'utilisateurConnecteId'));
    }

    public function rechercheClan(Request $request)
    {
        $request->validate([
            'q' => 'required|string'
        ]);

        $query = $request->input('q');
        $clans = Clan::where('nom', 'like', "%{$query}%")->get();

        return view('amis.index', compact('clans'));
    }

    // Ajoute un ami sans authentification (les IDs sont passés dans la requête)
    public function ajouter(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'friend_id' => 'required|exists:users,id',
        ]);

        // Vérifiez si la relation existe déjà
        $existe = \DB::table('demande_amis')
            ->where('user_id', $request->input('user_id'))
            ->where('friend_id', $request->input('friend_id'))
            ->exists();

        if ($existe) {
            return redirect()->route('amis.index')
                             ->withErrors(['message' => 'Cette demande d\'ami existe déjà.']);
        }

        // Ajoutez la demande d'ami
        \DB::table('amis')->insert([
            'user_id'    => $request->input('user_id'),
            'friend_id'  => $request->input('friend_id'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('amis.index')
                         ->with('success', 'Demande d\'ami envoyée avec succès.');
    }

    // Affiche les demandes d'amis destinées à l'utilisateur connecté (ou 999 en test)
    public function demandes(Request $request)
    {
        // Utiliser l'utilisateur authentifié ou un ID de test (ici 999)
        $userId = auth()->check() ? auth()->user()->id : 999;

        // Récupérer les demandes d'amis en faisant une jointure pour récupérer l'email et l'imageProfil de l'expéditeur
        $demandes = \DB::table('demande_amis')
            ->join('users', 'demande_amis.requester_id', '=', 'users.id')
            ->where('demande_amis.requested_id', $userId)
            ->where('demande_amis.status', 'pending')
            ->select('demande_amis.*', 'users.email', 'users.imageProfil as requester_imageProfil')
            ->get();

            $utilisateur = auth()->id();
            $utilisateur = User::findOrFail($utilisateur);
    
            // Si l'utilisateur est pas connecté
            if (!$utilisateur){
                Log::info('Utilisateur pas connecté.');
                return redirect('/connexion')->with('erreur', 'Vous devez être connectés pour afficher les clans.');
            }
    
            //obtenir les clans dont l'utilisateur fait partie
            $clans = $utilisateur->clans()->get();
    

        return view('amis.demandes', compact('demandes','clans'));
    }

    // Accepte une demande d'ami
    public function accepter(Request $request)
    {
        $request->validate([
            'demande_id' => 'required|integer|exists:demande_amis,id',
        ]);

        $userId = auth()->check() ? auth()->user()->id : 999;

        // Récupérer la demande
        $demande = \DB::table('demande_amis')->where('id', $request->input('demande_id'))->first();

        // Vérifier que la demande est destinée à l'utilisateur
        if ($demande->requested_id != $userId) {
            return back()->withErrors('Action non autorisée.');
        }

        // Mettre à jour le statut à "accepted"
        \DB::table('demande_amis')
            ->where('id', $request->input('demande_id'))
            ->update([
                'status'     => 'accepted',
                'updated_at' => now(),
            ]);

        // Insérer la relation d'amitié bidirectionnelle dans la table amis
        $result = \DB::table('amis')->insert([
            [
                'user_id'    => $demande->requested_id,
                'friend_id'  => $demande->requester_id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id'    => $demande->requester_id,
                'friend_id'  => $demande->requested_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // Pour déboguer, vous pouvez vérifier le résultat de l'insertion :
        // dd($result); // Affiche true en cas d'insertion réussie

        return redirect()->back()->with('success', __('friends.success_accept'));
    }

    // Refuse une demande d'ami
    public function refuser(Request $request)
    {
        $request->validate([
            'demande_id' => 'required|integer|exists:demande_amis,id',
        ]);

        $userId = auth()->check() ? auth()->user()->id : 999;

        // Récupérer la demande
        $demande = \DB::table('demande_amis')->where('id', $request->input('demande_id'))->first();

        // Vérifier que la demande est destinée à l'utilisateur
        if ($demande->requested_id != $userId) {
            return back()->withErrors('Action non autorisée.');
        }

        // Mettre à jour le statut à "declined"
        \DB::table('demande_amis')
            ->where('id', $request->input('demande_id'))
            ->update([
                'status'     => 'declined',
                'updated_at' => now(),
            ]);

        return redirect()->back()->with('success', __('friends.success_decline'));
    }

    public function envoyer(Request $request)
    {
        $request->validate([
            'friend_id' => 'required|integer|exists:users,id',
        ]);

        // Utiliser l'utilisateur authentifié ou assigner l'ID 999 si personne n'est connecté
        if (auth()->check()) {
            $user = auth()->user();
        } else {
            // Pour les tests, si pas d'utilisateur connecté, considérer l'utilisateur 999
            $user = (object) ['id' => 999];
        }

        $friendId = $request->input('friend_id');

        // Empêcher d'envoyer une demande à soi-même
        if ($user->id == $friendId) {
            return back()->withErrors('Vous ne pouvez pas vous ajouter vous-même.');
        }

        // Vérifier qu'une demande n'existe pas déjà
        $exists = \DB::table('demande_amis')
            ->where('requester_id', $user->id)
            ->where('requested_id', $friendId)
            ->exists();

        if ($exists) {
            return back()->withErrors('Une demande d\'ami a déjà été envoyée.');
        }

        // Insérer la demande d'ami dans la table
        \DB::table('demande_amis')->insert([
            'requester_id' => $user->id,
            'requested_id' => $friendId,
            'status'       => 'pending',
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        return redirect()->back()->with('success', __('friends.success_sent'));
    }
}
