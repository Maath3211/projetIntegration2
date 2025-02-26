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
use Exception;

class ClanController extends Controller
{
    // Accueil d'un clan
    public function index($id){
        //$clan = Clan::findOrFail($id);
        return View('Clans.accueilClans', compact('id'/*, $clan*/));
    }

    // Paramètres d'un clan
    public function parametres($id){
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
            Log::info('CLAN: ' . $clan);
            $lienInvitation = $this->genererLienInvitation($clan);
            $membres = $clan->utilisateurs;
            return View('Clans.parametresClanMembres', compact('id', 'clan', 'lienInvitation', 'membres'));
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
                
                // si nomClan est entré, on fait la mise à jour du nom ET de l'image
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
            }

            return back()->with('erreur', 'Veuillez sélectionner une image valide à téléverser.');
        } catch (Exception $e) {
            Log::error('Téléversement d\'image erronné: ' . $e->getMessage());
    
            return back()->with('erreur', 'Une erreur est survenue lors du téléversement de l\'image.');
        }
    }

    // Mise à jour des catégories de canaux (ajouter / supprimer)
    public function miseAJourCanaux(Request $request, $id){
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

        $categoriesAModifier = array_values($categoriesAModifier);

        // Ajouter les catégories à ajouter
        foreach($categoriesAAjouter as $categorie){
            // Vérifications
            if(strlen($categorie) > 50){
                return redirect()->back()->with('erreur', 'Les catégories ne doivent pas dépasser les 50 caractères.');
            } 
            else if(!preg_match('/^[\p{L}-]+$/u', $categorie)) {
                return redirect()->back()->with('erreur', 'Les catégories ne doivent pas contenir de chiffres ou de symboles. Seul les lettres UTF-8 et les traits (-) sont permis.');
            }

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
            $valeurs = explode(';', $categorie);
            if(count($valeurs) != 2){
                return redirect()->back()->with('erreur', 'Une erreur est survenue lors du processus. Veuillez réessayer plus tard.');
            }
            
            if(strlen($valeurs[1]) > 50){
                return redirect()->back()->with('erreur', 'Les catégories ne doivent pas dépasser les 50 caractères.');
            } 
            else if(!preg_match('/^[\p{L}-]+$/u', $valeurs[1])) {
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
        Log::info('clanId: '. $id);
        Log::info('action: '. $request->input('action'));
        Log::info('requete: '. $request->input('requete'));

        // obtenir la requête dans un format JSON
        $action = $request->input('action');
        $requete = json_decode($request->input('requete'), true);

        if(isset($requete['canal'])){
            //$canal = Canal::findOrFail($requete['canal']);

            // pour renommer un canal
            if($action === 'renommer'){
                if(strlen($requete['nouveauNom']) > 50){
                    return redirect()->back()->with('erreur', 'Les canaux ne doivent pas dépasser les 50 caractères.');
                } 
                else if(!preg_match('/^[A-Za-z\u00C0-\u00FF-]+$/', $requete['nouveauNom'])) {
                    return redirect()->back()->with('erreur', 'Les canaux ne doivent pas contenir de chiffres ou de symboles. Seul les lettres UTF-8 et les traits (-) sont permis.');
                }

                //$canal->titre = $requete['nouveauNom'];
                //$canal->save();
                
                return redirect()->back()-with('message', 'modification faite avec succès!');
            } 
            // pour supprimer un canal
            else if($action === 'supprimer') {
                //$canal->delete();
                return redirect()->back()->with('message', 'suppression faite avec succès!');
            }
        }
        // pour ajouter un canal
        else if($action === 'ajouter') {
            if(strlen($requete['nouveauNom']) > 50){
                return redirect()->back()->with('erreur', 'Les canaux ne doivent pas dépasser les 50 caractères.');
            } 
            else if(!preg_match('/^[A-Za-z\u00C0-\u00FF-]+$/', $requete['nouveauNom'])) {
                return redirect()->back()->with('erreur', 'Les canaux ne doivent pas contenir de chiffres ou de symboles. Seul les lettres UTF-8 et les traits (-) sont permis.');
            }

            //Ajouter un nouveau Canal
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
                'mimes:jpeg,png,jpg,gif,svg'
            ],
            'clanPublic' => 'boolean'
        ],[
            'nomClan.required' => 'Le nom du clan est obligatoire',
            'nomClan.string' => 'Le nom du clan doit être de type string',
            'nomClan.max' => 'Le nom du clan ne doit pas dépasser les 50 caractères',
            'nomClan.regex' => 'Le nom du clan ne peut contenir que des lettres UTF-8, des espaces et des tirets (-)',
            'imageClan.image' => 'L\image du clan doit être une image',
            'imageClan.mimes' => 'L\'image du clan doit être un format valide (jpeg, png, jpg, gif, svg)',
            'clanPublic.boolean' => 'Le clan doit être soit privé soit public. (boolean)'
        ]);

        


        // $clan = Clan::create([
        //     'adminId' => $utilisateur,
        //     'image' => null, // on défini l'image plus tard une fois qu'on a l'id
        //     'nom' => $donneesValidees['nomClan'],
        //     'public' => $donneesValidees['clanPublic'] ?? false
        // ]);

        // if ($clan){
        //     if($request->hasFile('imageClan') && $request->file('imageClan')->isValid()) {
        //         $image = $request->file('imageClan');
        //         $chemin = $image->storeAs('public/img/Clans', 'clan_' . $clan->id . '.' . $image->getClientOriginalExtension());
    
        //         //mettre a jour l'image dans la bd
        //         $clan->update(['image' => $chemin]);
        //     }

        //     $categorie = CategorieCanal::create([
        //         'categorie' => 'Général',
        //         'clanId' => $clan->id
        //     ]);

        //     if($categorie) {
        //         $canal = Canal::create([
        //             'titre' => 'bienvenue',
        //             'clanId' => $clan->id,
        //             'categorieId' => $categorie->id
        //         ]);

        //         $canal = Canal::create([
        //             'titre' => 'général',
        //             'clanId' => $clan->id,
        //             'categorieId' => $categorie->id
        //         ]);

                return redirect()->back()->with('message', 'Clan créé avec succès!');
        //     }
        //     return redirect()->back()->with('message', 'Une erreur a été rencontrée lors de la création du clan mais le clan été créé avec succès.');
        // }

        // return redirect()->back()->with('erreur', 'Une erreur a été rencontrée lors de la création du clan. Veuillez réessayer plus tard');
    }

    // Supprimer un clan
    public function supprimer(string $id){
        //$user = Auth::user();
        
        //$clan = Clan::findOrFail($id);
        //if(!$user || !$clan || $clan->adminId != $user){
        //  return redirect()->back()->with('erreur', 'Une erreur est survenue lors de la suppression du clan. Assurez-vous d\'être connectés et d'être l'administrateur du clan')
        //}

        //$clan->delete();
        
        // TODO - FAIRE RETOURNER À L'ACCUEIL
    }

    // Accepter une invitation a un clan
    public function accepterInvitation(Request $request, Clan $clan){
        if(!$request->hasValidSignature()){
            abort(403, 'Lien d\'invitaion invalide ou expiré');
        }

        $utilisateur = Auth::user();
        if(!$utilisateur){
            return redirect('/connexion')->with('erreur', 'Vous devez être connectés pour rejoindre un clan.');
        }

        if($clan->utilisateurs()->where('user_id', $utilisateur->id)->exists()){
           return redirect()->route('clan.montrer', $clan->id)->with('message', 'Vous êtes déjà membre de ce clan.');
        }
        Log::info('CLAN_INVITE1: ' . $clan);
        $clan->utilisateurs()->sync([$utilisateur->id => ['clan_id' => $clan->id]]);

        return redirect()->route('clan.montrer', $clan->id)->with('message', 'Vous avez rejoint le clan avec succès.');
    }

    // générer un lien d'invitation au clan
    public function genererLienInvitation(Clan $clan){
        $lien = URL::temporarySignedRoute('invitation.accepter', now()->addHours(24), ['clan' => $clan->id]);
        return $lien;
    }

}
