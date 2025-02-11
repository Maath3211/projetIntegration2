<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

class ClanController extends Controller
{
    // Accueil d'un clan
    public function index($id){
        //$clan = Clan::findOrFail($id);
        return View('Clans.accueilClans'/*, compact('clan')*/);
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
        //
    }

    /**
     * Show the form for creating a new resource.
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
}
