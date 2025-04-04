<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use App\Models\Clan;
use App\Models\User;
use App\Models\Canal;
use App\Models\CategorieCanal;
use App\Models\Clan_user;
use App\Models\UtilisateurClan;
use App\Repository\ConversationsRepository;
use App\Repository\ConversationsClan;
use App\Events\MessageGroup;
use App\Events\SuppressionMessageGroup;
use Exception;
use Illuminate\Support\Facades\DB;

class ClanController extends Controller
{
    // Xavier : creation des repository pour les conversations (code plus clean)
    private $ConvRepository;
    private $ClanRepository;

    public function __construct(
        ConversationsRepository $conversationRepository,
        ConversationsClan $ClanRepository
    ) {
        $this->ConvRepository = $conversationRepository;
        $this->ClanRepository = $ClanRepository;
    }


    // Accueil d'un clan
    public function index($id){
        $utilisateur = auth()->id();
        $utilisateur = User::findOrFail($utilisateur);

        // Si l'utilisateur est pas connecté
        if (!$utilisateur){
            Log::info('Utilisateur pas connecté.');
            return redirect('/connexion')->with('erreur', 'Vous devez être connectés pour afficher les clans.');
        }

        //obtenir les clans dont l'utilisateur fait partie
        $clans = $utilisateur->clans()->get();

        //le clan qu'il souhaite afficher ainsi que ses informations
        $clan = Clan::findOrFail($id);
        $membres = $clan->utilisateurs;
        $categories = CategorieCanal::where('clanId', '=', $id)->get();
        $canauxParCategorie = Canal::whereIn('categorieId', $categories->pluck('id'))->get()->groupBy('categorieId');
        Log::info($canauxParCategorie);

        if(!$canauxParCategorie->isEmpty()){
            return redirect()->route('clan.canal', ['id' => $id, 'canal' => $canauxParCategorie->first()->first()->id]);
        }

        return View('Clans.accueilClans', compact('id', 'clans', 'clan', 'membres', 'categories', 'canauxParCategorie', 'utilisateur'));
    }

    // Paramètres d'un clan
    public function parametres($id){

        // Si le clan n'existe pas
        $clan = Clan::findOrFail($id);
        if(!$clan){
            return back()-with('erreur', 'ERREUR - Clan inexistant.');
        }


        if(Route::currentRouteName() === "clan.parametres"){
            //les paramètres généraux
            return View('Clans.parametresClan', compact('id', 'clan' ));
        }
        else if (Route::currentRouteName() === "clan.parametres.canaux") {
            //les paramètres de catégories de canaux
            $categories = CategorieCanal::where('clanId', '=', $clan->id)->get();
            return View('Clans.parametresClanCanaux', compact('id', 'clan', 'categories'));
        }
        else if (Route::currentRouteName() === "clan.parametres.membres"){
            // les paramètres des membres du clan
            $lienInvitation = $this->genererLienInvitation($clan);
            $membres = $clan->utilisateurs;
            return View('Clans.parametresClanMembres', compact('id', 'clan', 'lienInvitation', 'membres'));
        }
    }

    // Mise à jour des paramtètres généraux (image & nom du clan)
    public function miseAJourGeneral(Request $request, $id){
        try {
            Log::info('NOM: ' . $request->input('nomClan'));
            // la validation du formulaire
            $request->validate([
                'imageClan' => 'image|mimes:jpeg,png,jpg,gif,webp|max:4096',
                'nomClan' => 'string|max:50|regex:/^[\p{L}\s\-]+$/u',
            ], [
                'imageClan.image' => 'Erreur lors du chargement de l\'image.',
                'imageClan.mimes' => 'Format d\'image invaide.',
                'imageClan.max' => 'L\'image du clan ne doit pas dépasser 4MB.',
                'nomClan.string' => 'Le nom du clan doit être du texte.',
                'nomClan.max' => 'Le nom du clan ne doit pas dépasser les 50 caractères.',
                'nomClan.regex' => 'Le nom du clan ne peut contenir que des lettres UTF-8, des espaces et des tirets (-)',
            ]);


            $nomClan = $request->input('nomClan');

            // si une image a été soumise, on échange l'ancienne avec la nouvelle dans nos fichiers
            if($request->hasFile('imageClan')){
                $imageOriginale = public_path('img/Clans/clan_'.$id.'_.*');

                $images = glob($imageOriginale);

                // Supprimer l'ancienne image si elle existe
                if($images){
                    foreach($images as $image) {
                        if(File::exists($image)){
                            File::delete($image);
                            Log::info('Image supprimée: ' . $image);
                        }
                    }
                }

                //Enregistrer la nouvelle image à la place de l'ancienne image
                $image = $request->file('imageClan');
                $nomImage = 'img/Clans/clan_' . $id . '_.' . $image->getClientOriginalExtension();

                $image->move(public_path('img/Clans'), $nomImage);

                // si nomClan est entré, on fait la mise à jour du nom ET de l'image dans la BD
                if($request->filled('nomClan')){
                    $clan = Clan::findOrFail($id);
                    $clan->image = $nomImage;
                    $clan->nom = $nomClan;
                    $clan->save();

                } // sinon on met juste l'image à jour
                else {
                    $clan = Clan::findOrFail($id);
                    $clan->image = $nomImage;
                    $clan->save();
                }

                return redirect()->route('clan.parametres.post', ['id' => $id])->with('message', 'Changements enregistrés avec succès');
            }
            else {
                if($request->filled('nomClan')){
                    $clan = Clan::findOrFail($id);
                    $clan->nom = $nomClan;
                    $clan->save();
                }

                return redirect()->route('clan.parametres.post', ['id' => $id])->with('message', 'Changements enregistrés avec succès');
            }

        } catch (Exception $e) {
            Log::error('Enregistrement: ' . $e->getMessage());

            return back()->with('erreur', $e->getMessage());
        }
    }

    // Mise à jour des catégories de canaux (ajouter / supprimer)
    public function miseAJourCanaux(Request $request, $id){
        /*
        on recoit les informations comme ca au début:
            info1,info2,info3
        on les transforme en un array en séparant par ,:
            ['info1','info2','info3']
        */

        $categoriesASupprimer = explode(',', $request->input('categoriesASupprimer'));
        $categoriesAModifier = explode(',', $request->input('categoriesARenommer'));
        $categoriesAAjouter = explode(',', $request->input('categoriesAAjouter'));

        // Trier les arrays pour juste prendre ceux qui ont quelque chose dedans
        $categoriesASupprimer = array_filter($categoriesASupprimer);
        $categoriesASupprimer = array_values($categoriesASupprimer);

        $categoriesAModifier = array_filter($categoriesAModifier);
        $categoriesAModifier = array_values($categoriesAModifier);

        $categoriesAAjouter = array_filter($categoriesAAjouter);
        $categoriesAAjouter = array_values($categoriesAAjouter);

        // si la catégorie à supprimer va aussi être modifiée, on l'enlève de la liste à modifier
        for($i = 0; $i < count($categoriesASupprimer); $i++){

            for($j = 0; $j < count($categoriesAModifier); $j++){

                $categorie = explode(';', $categoriesAModifier[$j][0]);

                if($categoriesASupprimer[$i] === $categorie){
                    unset($categoriesAModifier[$j]);
                }
            }
        }

        // mettre à jour l'array pour enlever les valeurs vides
        $categoriesAModifier = array_values($categoriesAModifier);

        // Ajouter les catégories à ajouter
        foreach($categoriesAAjouter as $categorie){
            // Vérifications
            // Critère 1: Les catégories de canal de doivent pas dépasser les 50 caractères.
            if(strlen($categorie) > 50){
                return redirect()->back()->with('erreur', 'Les catégories ne doivent pas dépasser les 50 caractères.');
            }
            // Critère 2: Les catégoires de canal ne doivent pas contenir de chiffres ou de symboles. Juste UTF-8, espaces & tirets.
            else if(!preg_match('/^[\p{L}\s-]+$/u', $categorie)) {
                return redirect()->back()->with('erreur', 'Les catégories ne doivent pas contenir de chiffres ou de symboles. Seul les lettres UTF-8 et les traits (-) sont permis.');
            }

            // Critère 3: Les catégories de canal doivent être uniques à l'intérieur du même clan.
            $existe = CategorieCanal::where('clanId', $id)->where('categorie', $categorie)->first();
            if($existe){
                return redirect()->back()->with('erreur', 'Ce clan possède déjà une catégorie de canal du même nom.');
            }

            // Ajout des catégories
            $cat = [
                'categorie' => $categorie,
                'clanId' => $id
            ];
            CategorieCanal::create($cat);
        }

        // Modifier les catégories à modifier
        foreach($categoriesAModifier as $categorie){
            // Vérifications
            // ['nomOriginalCategorie;nouveauNom']
            $valeurs = explode(';', $categorie);
            // ['nomOriginalCategorie', 'nouveauNom']

            if(count($valeurs) != 2){
                return redirect()->back()->with('erreur', 'Une erreur est survenue lors du processus. Veuillez réessayer plus tard.');
            }

            // Critère 1: Les catégories de canal de doivent pas dépasser les 50 caractères.
            if(strlen($valeurs[1]) > 50){
                return redirect()->back()->with('erreur', 'Les catégories ne doivent pas dépasser les 50 caractères.');
            }
            // Critère 2: Les catégoires de canal ne doivent pas contenir de chiffres ou de symboles. Juste UTF-8, espaces & tirets.
            else if(!preg_match('/^[\p{L}\s-]+$/u', $valeurs[1])) {
                return redirect()->back()->with('erreur', 'Les catégories ne doivent pas contenir de chiffres ou de symboles. Seul les lettres UTF-8 et les traits (-) sont permis.');
            }

            // Renommage des catégories
            $cat = CategorieCanal::where('clanId', $id)->where('categorie', $valeurs[0])->first();
            $cat->categorie = $valeurs[1];
            $cat->save();
        }

        // Supprimer les catégories à supprimer
        CategorieCanal::where('clanId', $id)->whereIn('categorie', $categoriesASupprimer)->delete();

        return redirect()->route('clan.parametres.canaux', ['id' => $id])->with('message', 'Changements enregistrés avec succès');

    }

    // interactions avec les canaux des clans (ajouter / renommer / supprimer)
    public function actionsCanal(Request $request, $id){
        // si l'utilisateur n'est pas connecté.
        $utilisateur = auth()->id();
        if(!$utilisateur){
            Log::info('Utilisateur pas connecté.');
            return redirect()->back()->with('erreur', 'Une erreur est survenue lors des modifications. Assurez-vous d\'être connectés et d\'être l\'administrateur du clan');
        }

        // si le clan n'existe pas
        $clan = Clan::findOrFail($id);
        if(!$clan){
            Log::info('Clan inexistant.');
            return redirect()->back()->with('erreur', 'Une erreur est survenue lors des modifications. Veuillez réessayer plus tard.');
        }

        // si l'utilisateur n'est pas l'admin.
        if($clan->adminId != $utilisateur){
            Log::info('Utilisateur pas admin.');
            return redirect()->back()->with('erreur', 'Une erreur est survenue lors des modifications. Vous n\'êtes pas l\'administrateur de ce clan.');
        }

        // obtenir la requête dans un format JSON
        $action = $request->input('action');
        $requete = json_decode($request->input('requete'), true);
        if(isset($requete['nouveauNom'])){
            $requete['nouveauNom'] = str_replace(' ', '-', strtolower($requete['nouveauNom']));
        }

        if(isset($requete['canal'])){
            // pour ajouter un canal
            if($action === 'ajouter') {
                $requete['nouveauNom'] = str_replace(' ', '-', strtolower($requete['nouveauNom']));

                // Critère 1: Les canaux de doivent pas dépasser les 50 caractères.
                if(strlen($requete['nouveauNom']) > 50){
                    return redirect()->back()->with('erreur', 'Les canaux ne doivent pas dépasser les 50 caractères.');
                }
                // Critère 2: Les canaux ne doivent pas contenir de chiffres ou de symboles. Juste UTF-8, espaces & tirets.
                else if(!preg_match('/^[\p{L}-]+$/u', $requete['nouveauNom'])) {
                    return redirect()->back()->with('erreur', 'Les canaux ne doivent pas contenir de chiffres ou de symboles. Seul les lettres UTF-8 et les traits (-) sont permis.');
                }

                //Ajouter un nouveau Canal
                $canal = Canal::create([
                    'titre' => $requete['nouveauNom'],
                    'clanId' => $id,
                    'categorieId' => $requete['categorie']
                ]);


                if($canal)
                    return redirect()->back()->with('message', 'Ajout effectué avec succès!');
                else
                    return redirect()->back()->with('erreur', 'Erreur lors de l\'ajout du canal. Veuillez réessayer plus tard.');
            }
            else {
                $canal = Canal::findOrFail($requete['canal']);

                // pour renommer un canal
                if($action === 'renommer'){
                    // Critère 1: Les catégories de canal de doivent pas dépasser les 50 caractères.
                    if(strlen($requete['nouveauNom']) > 50){
                        return redirect()->back()->with('erreur', 'Les canaux ne doivent pas dépasser les 50 caractères.');
                    }
                    // Critère 2: Les catégoires de canal ne doivent pas contenir de chiffres ou de symboles. Juste UTF-8, espaces & tirets.
                    else if(!preg_match('/^[\p{L}-]+$/u', $requete['nouveauNom'])) {
                        return redirect()->back()->with('erreur', 'Les canaux ne doivent pas contenir de chiffres ou de symboles. Seul les lettres UTF-8 et les traits (-) sont permis.');
                    }

                    $canal->titre = str_replace(' ', '-', strtolower($requete['nouveauNom']));
                    $canal->save();

                    return redirect()->back()->with('message', 'Modification effectuée avec succès!');
                }
                // pour supprimer un canal
                else if($action === 'supprimer') {
                    $canal->delete();
                    return redirect()->back()->with('message', 'Suppression effectuée avec succès!');
                }
            }
        }

        // Si il se rend jusque là il y a un problème
        Log::info('ERREUR - Action interdite. Veuillez contacter le support');
    }

    // Mise à jour de la liste de membres d'un clan (supprimer)
    public function miseAJourMembres(Request $request, $id){
        $membreASupprimer = explode(';', $request->input('membresASupprimer'));
        try {
            $clan = Clan::findOrFail($id);
            foreach($membreASupprimer as $membre){
                if(!empty($membre)){
                    $utilisateur = User::findOrFail($membre);
                    if($utilisateur && $utilisateur->id != $clan->adminId){
                        $utilisateur->clans()->detach($id);
                    }
                    else if ($utilisateur->id == $clan->adminId){
                        return redirect()->route('clan.parametres.membres', ['id' => $id])->with('erreur', 'Vous ne pouvez pas supprimer l\'administrateur du clan.');
                    }
                }
            }
        }
        catch (Exception $e) {
            return redirect()->route('clan.parametres.membres', ['id' => $id])->with('erreur', 'Erreur lors de l\'enregistrement des changements. Veuillez réessayer plus tard.');
        }

        return redirect()->route('clan.parametres.membres', ['id' => $id])->with('message', 'Changements enregistrés avec succès');
    }

    // Créer un clan
    public function creerClan(Request $request){
        try {
            Log::info($request);
            // utiliser son id comme id d'administrateur du clan
            $utilisateur = auth()->id();

            // validation des entrees
            $donneesValidees = $request->validate([
                'nomClan' => [
                    'required',
                    'string',
                    'max:50',
                    'regex:/^[\p{L}\s\-]+$/u' // juste les lettres UTF-8, espaces & tirets
                ],
                'imageClan' => [
                    'nullable',
                    'image',
                    'mimes:jpeg,png,jpg,gif,webp',
                    'max:4096'
                ],
                'clanPublic' => 'string'
            ],[
                'nomClan.required' => 'Le nom du clan est obligatoire',
                'nomClan.string' => 'Le nom du clan doit être de type string',
                'nomClan.max' => 'Le nom du clan ne doit pas dépasser les 50 caractères',
                'nomClan.regex' => 'Le nom du clan ne peut contenir que des lettres UTF-8, des espaces et des tirets (-)',
                'imageClan.image' => 'L\image du clan doit être une image',
                'imageClan.mimes' => 'L\'image du clan doit être un format valide (jpeg, png, jpg, gif, webp)',
                'imageClan.max' => 'L\'image du clan ne doit pas dépasser 4MB',
                'clanPublic.boolean' => 'Le clan doit être soit privé soit public. (boolean)'
            ]);

            if(!isset($donneesValidees['clanPublic'])){
                $donneesValidees['clanPublic'] = false;
            }

            if($donneesValidees['clanPublic'] == 'checked'){
                $donneesValidees['clanPublic'] = true;
            } else {
                $donneesValidees['clanPublic'] = false;
            }

            $clan = Clan::create([
                'adminId' => $utilisateur,
                'image' => 'img/Clans/default.jpg', // on défini l'image plus tard une fois qu'on a l'id
                'nom' => $donneesValidees['nomClan'],
                'public' => $donneesValidees['clanPublic'] ?? false
            ]);

            if ($clan){
                $admin = Clan_user::create([
                    'clan_id'    => $clan->id,
                    'user_id'    => $utilisateur,
                    'joined_at' => now(),
                ]);

                if($request->hasFile('imageClan') && $request->file('imageClan')->isValid()) {

                    // supprimer les anciennes images si elles existent
                    $imageOriginale = public_path('img/Clans/clan_'.$clan->id.'_.*');
                    $images = glob($imageOriginale);
                    if($images){
                        foreach($images as $image) {
                            if(File::exists($image)){
                                File::delete($image);
                                Log::info('Image supprimée: ' . $image);
                            }
                        }
                    }

                    // enregistrer l'image localement
                    $image = $request->file('imageClan');
                    $nomImage = 'img/Clans/clan_' . $clan->id . '_.' . $image->getClientOriginalExtension();

                    $image->move(public_path('img/Clans'), $nomImage);

                    //mettre a jour l'image dans la bd
                    $clan->update(['image' => $nomImage]);
                }

                // créer les catégories de canal et les canaux de base pour bien commencer le clan
                $categorie = CategorieCanal::create([
                    'categorie' => 'Général',
                    'clanId' => $clan->id
                ]);

                if($categorie) {
                    $canal = Canal::create([
                        'titre' => 'bienvenue',
                        'clanId' => $clan->id,
                        'categorieId' => $categorie->id
                    ]);

                    $canal = Canal::create([
                        'titre' => 'général',
                        'clanId' => $clan->id,
                        'categorieId' => $categorie->id
                    ]);

                    return redirect()->back()->with('message', 'Clan créé avec succès!');
                }
                return redirect()->back()->with('message', 'Une erreur a été rencontrée lors de la création du clan mais le clan été créé avec succès.');
            }

            return redirect()->back()->with('erreur', 'Une erreur a été rencontrée lors de la création du clan. Veuillez réessayer plus tard');
        } catch (Exception $e) {
            Log::error('Erreur création clan: ' . $e->getMessage());
            return redirect()->back()->with('erreur', 'Une erreur a été rencontrée lors de la création du clan. Veuillez réessayer plus tard.');
        }

    }

    // Supprimer un clan
    public function supprimer(string $id){
        $utilisateur = auth()->id();

        $clan = Clan::findOrFail($id);
        // si c'est pas l'admin de connecté ou si le clan n'existe pas
        if(!$utilisateur || !$clan || $clan->adminId != $utilisateur){
            return redirect()->back()->with('erreur', 'Une erreur est survenue lors de la suppression du clan. Assurez-vous d\'être connectés et d\'être l\'administrateur du clan');
        }

        $clan->delete();

        return redirect()->route('profil.profil')->with('message', 'Suppression effectuée avec succès!');
    }

    // Accepter une invitation a un clan
    public function accepterInvitation(Request $request, Clan $clan){
        // si la signature du lien d'invitation n'est pas valide
        if(!$request->hasValidSignature()){
            abort(403, 'Lien d\'invitaion invalide ou expiré');
        }

        // si il n'est pas connecté
        $utilisateur = Auth::user();
        if(!$utilisateur){
            return redirect('/connexion')->with('erreur', 'Vous devez être connectés pour rejoindre un clan.');
        }

        // si l'utilisateur fait déjà partie du clan
        if($clan->utilisateurs()->where('user_id', $utilisateur->id)->exists()){
           return redirect()->route('clan.montrer', $clan->id)->with('message', 'Vous êtes déjà membre de ce clan.');
        }

        // ajouter l'utilisateur au clan
        $clan->utilisateurs()->attach($utilisateur->id, ['clan_id' => $clan->id]);

        return redirect()->route('clan.montrer', $clan->id)->with('message', 'Vous avez rejoint le clan avec succès.');
    }

    // générer un lien d'invitation au clan
    public function genererLienInvitation(Clan $clan){
        // faire un lien d'invitation qui est valide pour les prochaines 24 heures
        $lien = URL::temporarySignedRoute('invitation.accepter', now()->addHours(24), ['clan' => $clan->id]);
        return $lien;
    }

    /**
     * Affiche la page de recherche de clans.
     * Si la méthode est GET, affiche la vue de recherche (rechercheClans.blade.php) sans résultat.
     * Si c'est POST, effectue la recherche et retourne les résultats.
     */
    public function rechercheClans(Request $request)
    {
        // Vérifier l'authentification de l'utilisateur
        $utilisateurId = auth()->id();
        if (!$utilisateurId) {
            Log::info('Utilisateur pas connecté.');
            return redirect('/connexion')->with('erreur', 'Vous devez être connecté pour afficher les clans.');
        }

        // Récupérer l'objet utilisateur à partir de son ID
        $utilisateur = User::findOrFail($utilisateurId);

        // Récupérer les clans auxquels l'utilisateur appartient
        $clans = $utilisateur->clans()->get();

        // Si la requête est en GET, renvoyer la vue avec les clans de l'utilisateur
        if ($request->isMethod('get')) {
            return view('Clans.rechercheClans', compact('clans'));
        }

        // Pour une recherche par POST, valider et effectuer la recherche des clans publics non rejoints
        $request->validate([
            'q' => 'required|string'
        ]);

        $query = $request->input('q');

        // Rechercher uniquement les clans publics correspondant à la recherche et
        // dont l'utilisateur n'est pas déjà membre.
        // On stocke les résultats dans $mesClans (anciennement $clans)
        $mesClans = Clan::where('nom', 'like', "%{$query}%")
            ->where('public', true)
            ->whereNotIn('id', function ($q) use ($utilisateurId) {
                $q->select('clan_id')
                    ->from('clan_users')
                    ->where('user_id', $utilisateurId);
            })
            ->get();

        // Inverser les noms des variables si nécessaire :
        // Retourner la vue avec $mesClans contenant la recherche et $clans contenant les clans existants de l'utilisateur.
        return view('Clans.rechercheClans', compact('mesClans', 'clans'));
    }
    public function rejoindre(Request $request)
    {
        $request->validate([
            'clan_id' => 'required|integer|exists:clans,id',
        ]);

        $userId = auth()->check() ? auth()->user()->id : 999;

        // Vérifier si l'utilisateur a déjà rejoint le clan
        $exists = DB::table('clan_users')
            ->where('clan_id', $request->input('clan_id'))
            ->where('user_id', $userId)
            ->exists();

        if ($exists) {
            return back()->withErrors('Vous avez déjà rejoint ce clan.');
        }

        DB::table('clan_users')->insert([
            'clan_id'    => $request->input('clan_id'),
            'user_id'    => $userId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Vous avez rejoint le clan !');
    }




    /**
     * Xavier : communication dans le clan
     */


    public function showCanalClan($id, $canal)
    {

        $utilisateur = auth()->id();
        $utilisateur = User::findOrFail($utilisateur);

        if (!$utilisateur){
            Log::info('Utilisateur pas connecté.');
            return redirect('/connexion')->with('erreur', 'Vous devez être connectés pour afficher les clans.');
        }

        $clans = $utilisateur->clans()->get();
        $clan = Clan::findOrFail($id);
        $membres = $clan->utilisateurs;
        $categories = CategorieCanal::where('clanId', '=', $id)->get();
        $canauxParCategorie = Canal::whereIn('categorieId', $categories->pluck('id'))->get()->groupBy('categorieId');
        $messages = $this->ClanRepository->getMessageClanFor($id, $canal);


        //Log::info('CLANS: ' . json_encode($clans->toArray()));
        //Log::info('CATEGORIES: ' . json_encode($categories->pluck('id')->toArray()));
        //Log::info('CANAUX: ' . json_encode($canauxParCategorie->toArray()));
        return View('Clans.canalClan',
        compact(
        'id',
        'clans',
        'clan',
        'membres',
        'categories',
        'canauxParCategorie',
        'utilisateur',
        'messages'
    ));
    }


    // Xavier : communication dans le clan
    public function broadcastClan(Request $request)
    {
        $request->validate([
            'message' => 'nullable|string|max:2000',
            'fichier' => 'nullable|file|max:20480', // 20 Mo
        ]);

        if (!$request->filled('message') && !$request->hasFile('fichier')) {
            return response()->json(['erreur' => 'Vous devez envoyer soit un message, soit un fichier, soit les deux.'], 422);
        }

        try {
            $nomFichier = null;
            // Si un fichier est envoyé
            if ($request->hasFile('fichier')) {
                $fichier = $request->file('fichier');

                // Générer un nom unique avec horodatage
                $nomFichier = time() . '_' . $fichier->getClientOriginalName();

                // Déterminer le dossier en fonction du type de fichier
                $dossier = in_array($fichier->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'gif'])
                    ? 'img/conversations_photo/'
                    : 'fichier/conversations_fichier/';

                // Stocker le fichier
                $fichier->move(public_path($dossier), $nomFichier);
            }

            // Insérer le message dans la base de données
            $dernierId = \DB::table('conversation_clan')->insertGetId([
                'idEnvoyer' => auth()->id(),
                'idClan'    => $request->vers,
                'idCanal'   => $request->canal,
                'message'   => $request->message,
                'fichier'   => $nomFichier, // Stocke le chemin public
                'created_at'=> now(),
                'updated_at'=> now()
            ]);

            // Log des données envoyées à Pusher
            \Log::info('Données diffusées via Pusher', [
                'message' => e($request->message),
                'idExpediteur' => auth()->id(),
                'idCanal' => $request->canal,
                'idGroupe' => $request->vers,
                'dernierId' => $dernierId,
                'photo' => $nomFichier,
                'email' => auth()->user()->email,
            ]);

            // Diffuser l’événement via Pusher
            broadcast(new MessageGroup(
                e($request->message),
                auth()->id(),
                $request->vers, // idGroupe (idClan)
                $request->canal, // idCanal
                false,
                $dernierId,
                $nomFichier,
                auth()->user()->email
            ))->toOthers();

        } catch (\Exception $e) {
            \Log::error('❌ Erreur lors du broadcast: ' . $e->getMessage());
        }

        return response()->json([
            'message'         => $request->message,
            'dernier_id'      => $dernierId,
            'expediteur_id'   => auth()->id(),
            'email_expediteur'=> auth()->user()->email,
            'fichier'         => $nomFichier ? asset($dossier . $nomFichier) : null // Retourne l'URL complète
        ]);
    }

    //non fonctionelle
    public function receiveClan(Request $request){


        return response()->json([

            'message' => $request->message,
            'id_expediteur' => $request->sender_id,
            'id_groupe' => $request->group_id,
            'id_canal' => $request->canal_id,
            'supprime' => $request->deleted,
            'last_id' => $request->last_id,
            'photo' => $request->photo,

        ]);
    }



    public function destroy(UtilisateurClan $message)
    {
        if (auth()->id() !== $message->idEnvoyer) {
            return response()->json(['error' => 'Action non autorisée'], 403);
        }

        \Log::info('Détails du message avant suppression', ['message_id' => $message->id, 'fichier' => $message->fichier]);

        if ($message->fichier) {
            $fichierNom = $message->fichier;

            // Déterminer le dossier selon l'extension
            $dossier = in_array(pathinfo($fichierNom, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif'])
                ? 'img/conversations_photo/'
                : 'fichier/conversations_fichier/';

            $fichierChemin = public_path($dossier . $fichierNom);

            \Log::info('Chemin du fichier à supprimer', ['fichier_path' => $fichierChemin]);

            if (file_exists($fichierChemin)) {
                unlink($fichierChemin);
                \Log::info('Fichier supprimé', ['fichier_path' => $fichierChemin]);
            } else {
                \Log::warning('Le fichier n\'existe pas', ['fichier_path' => $fichierChemin]);
            }
        } else {
            \Log::info('Aucun fichier associé au message', ['message_id' => $message->id]);
        }

        $messageId = $message->id;
        $message->delete();

        broadcast(new SuppressionMessageGroup($messageId, $message->idClan, $message->idCanal))->toOthers();

        return response()->json(['success' => 'Message supprimé']);
    }




}


