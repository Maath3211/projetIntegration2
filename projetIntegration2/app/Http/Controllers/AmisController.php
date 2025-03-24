<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Utilisé pour la recherche d'amis
use App\Models\Clan; // Utilisé pour la recherche de clans

class AmisController extends Controller
{
    // Affiche le formulaire de recherche
    public function index()
    {
        return view('amis.index');
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

        // Récupérer la liste des IDs des amis déjà ajoutés par l'utilisateur
        $friendIds = \DB::table('user_ami')
            ->where('idEnvoyer', $userId)
            ->pluck('idReceveur')
            ->toArray();

        // S'assurer d'exclure également l'utilisateur lui-même
        $friendIds[] = $userId;

        // Rechercher les utilisateurs dont le nom correspond à la recherche et qui ne sont pas déjà amis
        $utilisateurs = User::where(function($queryBuilder) use ($query) {
            $queryBuilder->where('prenom', 'like', "%{$query}%")
                         ->orWhere('nom', 'like', "%{$query}%");
        })
        ->whereNotIn('id', $friendIds)
        ->get();

<<<<<<< Updated upstream
        return view('amis.index', compact('utilisateurs', 'utilisateurConnecteId'));
=======
        // Récupérer les IDs auxquels une demande a déjà été envoyée par l'utilisateur connecté
        $sentRequests = \DB::table('demande_amis')
            ->where('requester_id', $userId)
            ->pluck('requested_id')
            ->toArray();

        return view('amis.index', compact('utilisateurs', 'sentRequests'));
>>>>>>> Stashed changes
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

        return redirect()->back()->with('success', __('friends.succes_requete_envoyer'));
    }

    // Affiche les demandes d'amis destinées à l'utilisateur connecté (ou 999 en test)
    public function demandes(Request $request)
    {
        // Utiliser l'utilisateur authentifié ou l'ID 999 pour les tests
        $userId = auth()->check() ? auth()->user()->id : 999;

        // Récupérer les demandes reçues en joignant la table users pour obtenir l'avatar et le username du demandeur
        $demandes = \DB::table('demande_amis')
            ->join('users', 'demande_amis.requester_id', '=', 'users.id')
            ->select('demande_amis.*', 'users.username as requester_username', 'users.avatar as requester_avatar')
            ->where('demande_amis.requested_id', $userId)
            ->where('demande_amis.status', 'pending')
            ->get();

        return view('amis.demandes', compact('demandes'));
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
