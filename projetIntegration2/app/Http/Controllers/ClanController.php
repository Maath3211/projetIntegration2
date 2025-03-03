<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use App\Models\Clan; // Assurez-vous que le modèle Clan est importé
use Illuminate\Support\Facades\DB;

class ClanController extends Controller
{
    // Accueil d'un clan
    public function index($id){
        //$clan = Clan::findOrFail($id);
        return View('Clans.accueilClans', compact('id'/*, $clan*/));
    }

    // Paramètres d'un clan
    public function parametres($id){
        // $clan = Clan::findOrFail($id);
        if(Route::currentRouteName() === "clan.parametres"){
            //les paramètres généraux
            return View('Clans.parametresClan', compact('id'/*, 'clan*/ ));
        }
        else if (Route::currentRouteName() === "clan.parametres.canaux") {
            //les paramètres de catégories de canaux
            return View('Clans.parametresClanCanaux', compact('id'/*, 'clan*/ ));
        }
        else if (Route::currentRouteName() === ""){
            // les paramètres des membres du clan
            return View('Clans.parametresClan', compact('id'/*, 'clan*/ ));
        }
    }

    // Mise à jour des paramtètres généraux (image & nom du clan)
    public function miseAJourGeneral(Request $request, $id){
        try {
            
            $request->validate([
                'imageClan' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'nomClan' => 'string|max:50',
            ], [
                'imageClan.image' => 'Erreur lors du chargement de l\'image.',
                'imageClan.mimes' => 'Format d\'image invaide.',
                'imageClan.max' => 'Image trop grande.',
                'nomClan.string' => 'Le nom du clan doit être du texte.',
                'nomClan.max' => 'Le nom du clan ne doit pas dépasser les 50 caractères.',
            ]);


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
                $nomImage = 'clan_' . $id . '_.' . $image->getClientOriginalExtension();

                $image->move(public_path('img/Clans'), $nomImage);
                
                // si nomClan est entré, on fait la mise à jour du nom ET de l'image
                if($request->filled('nomClan')){
                    //TODO - mettre à jour le nom du clan dans la BD
                    //TODO - ajouter le chemin de l'image dans la BD
                    /*
                    $clan = Clan::findOrFail($id);
                    $clan->image = $nomImage;
                    $clan->nom = $nomClan
                    $clan->save();
                    */
                } // sinon on met juste l'image à jour
                else {
                    //TODO - ajouter le chemin de l'image dans la BD
                    /*
                    $clan = Clan::findOrFail($id);
                    $clan->image = $nomImage;
                    $clan->save();
                    */
                }

                return redirect()->route('clan.parametres.post', ['id' => $id])->with('success', 'Changements enregistrés avec succès');
            }
            else {
                if($request->filled('nomClan')){
                    //TODO - mettre à jour le nom du clan dans la BD
                    /*
                    $clan = Clan::findOrFail($id);
                    $clan->image = $nomImage;
                    $clan->nom = $nomClan
                    $clan->save();
                    */
                }
            }

            return back()->with('error', 'Veuillez sélectionner une image valide à téléverser.');
        } catch (\Exception $e) {
            Log::error('Téléversement d\'image erronné: ' . $e->getMessage());
    
            return back()->with('error', 'Une erreur est survenue lors du téléversement de l\'image.');
        }
    }

    // Mise à jour des catégories de canaux (ajouter / supprimer)
    public function miseAJourCanaux(Request $request, $id){
        $categoriesASupprimer = explode(',', $request->input('categoriesASupprimer'));
        $categoriesAModifier = explode(',', $request->input('categoriesARenommer'));
        $categoriesAAjouter = explode(',', $request->input('categoriesAAjouter'));

        // si la catégorie à supprimer va aussi être modifiée, on l'enlève de la liste à modifier
        for($i = 0; $i < count($categoriesASupprimer); $i++){
            
            for($j = 0; $j < count($categoriesAModifier); $j++){
                
                $categorie = explode(';', $categoriesAModifier[$j][0]);

                if($categoriesASupprimer[$i] === $categorie){
                    unset($categoriesAModifier[$j]);
                }
            }
        }

        $categoriesAModifier = array_values($categoriesAModifier);


        // Vérification des catégories à modifier
        foreach($categoriesAModifier as $categorie){
            $valeurs = explode(';', $categorie);
            if(count($valeurs) != 2){
                return response()->json([
                    'error' => 'Une erreur est survenue lors du processus. Veuillez réessayer plus tard.'
                ], 400);
            }
            
            if(strlen($valeurs[1]) > 50){
                return response()->json([
                    'error' => 'Les catégories ne doivent pas dépasser les 50 caractères.'
                ], 400);
            } 
            else if(!preg_match('/^[A-Za-z\u00C0-\u00FF-]+$/', $valeurs[1])) {
                return response()->json([
                    'error' => 'Les catégories ne doivent pas contenir de chiffres ou de symboles. Seul les lettres UTF-8 et les traits (-) sont permis.'
                ], 400);
            }

            // TODO Faire les ajouts içi
            // TODO Faire la modification içi
        }

        // TODO Faire la suppression des catégories et leurs canaux içi

        return redirect()->route('clan.parametres.post', ['id' => $id])->with('success', 'Changements enregistrés avec succès');

    }

    public function actionsCanal(Request $request, $id){
        // Renommer le canal
        Log::info('HAHAHAHAAHAH');
        Log::info('clanId: '. $id);
        Log::info('action: '. $request->input('action'));
        Log::info('requete: '. $request->input('requete'));

        $requete = json_decode($request->input('requete'), true);

        if(isset($requete['canal'])){
            //$canal = Canal::findOrFail($requete['canal']);

            if($request->input('action') === 'renommer'){
                if(strlen($requete['nouveauNom']) > 50){
                    return response()->json([
                        'error' => 'Les catégories ne doivent pas dépasser les 50 caractères.'
                    ], 400);
                } 
                else if(!preg_match('/^[A-Za-z\u00C0-\u00FF-]+$/', $requete['nouveauNom'])) {
                    return response()->json([
                        'error' => 'Les catégories ne doivent pas contenir de chiffres ou de symboles. Seul les lettres UTF-8 et les traits (-) sont permis.'
                    ], 400);
                }
                
                //$canal->titre = $requete['nouveauNom'];
                //$canal->save();
                
                return redirect()->back()-with('message', 'modification faite avec succès!');
            } 
            else if($request->input('action') === 'supprimer') {
                //$canal->delete();
                return redirect()->back()->with('message', 'suppression faite avec succès!');
            }
        }
        else if($request->input('action') === 'ajouter') {
            if(strlen($requete['nouveauNom']) > 50){
                return response()->json([
                    'error' => 'Les catégories ne doivent pas dépasser les 50 caractères.'
                ], 400);
            } 
            else if(!preg_match('/^[A-Za-z\u00C0-\u00FF-]+$/', $requete['nouveauNom'])) {
                return response()->json([
                    'error' => 'Les catégories ne doivent pas contenir de chiffres ou de symboles. Seul les lettres UTF-8 et les traits (-) sont permis.'
                ], 400);
            }

            //Ajouter un nouveau Cana
            // $canal = new Canal();
            // $canal->titre = $requete['nouveauNom'];
            // $canal->clanId = $id;
            // $canal->categorieId = $requete['categorie'];
            // $canal->save();
            return redirect()->back()->with('message', 'ajout fait avec succès!');
        }
        else {
            Log::info('ERREUR - Action interdite. Veuillez contacter le support');
        }
    }
    // Mise à jour de la liste de membres d'un clan (supprimer)

    /**
     * Show the form for creating a new resource.im 
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Affiche la page de recherche de clans.
     * Si la méthode est GET, affiche la vue de recherche (rechercheClans.blade.php) sans résultat.
     * Si c'est POST, effectue la recherche et retourne les résultats.
     */
    public function rechercheClans(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('Clans.rechercheClans');
        }

        $request->validate([
            'q' => 'required|string'
        ]);

        $query = $request->input('q');
        $userId = auth()->check() ? auth()->user()->id : 999;

        // Récupérer uniquement les clans publics qui correspondent à la recherche et non déjà rejoints
        $clans = Clan::where('nom', 'like', "%{$query}%")
                    ->where('public', true)
                    ->whereNotIn('id', function($q) use ($userId) {
                        $q->select('clan_id')
                          ->from('clan_user')
                          ->where('user_id', $userId);
                    })
                    ->get();

        return view('Clans.rechercheClans', compact('clans'));
    }

    /**
     * Permet à l'utilisateur de rejoindre un clan.
     * L'utilisateur est déterminé par l'authentification, ou 999 pour les tests.
     */
    public function rejoindre(Request $request)
    {
        $request->validate([
            'clan_id' => 'required|integer|exists:clan,id',
        ]);

        $userId = auth()->check() ? auth()->user()->id : 999;

        // Vérifier si l'utilisateur a déjà rejoint le clan
        $exists = DB::table('clan_user')
            ->where('clan_id', $request->input('clan_id'))
            ->where('user_id', $userId)
            ->exists();

        if ($exists) {
            return back()->withErrors('Vous avez déjà rejoint ce clan.');
        }

        DB::table('clan_user')->insert([
            'clan_id'    => $request->input('clan_id'),
            'user_id'    => $userId,
            'joined_at' => now(),
        ]);

        return back()->with('success', 'Vous avez rejoint le clan !');
    }
}
