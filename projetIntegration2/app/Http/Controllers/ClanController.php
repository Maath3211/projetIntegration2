<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

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
        return View('Clans.parametresClan', compact('id'/*, 'clan*/ ));
    }

    // Téléversement de l'image servant de logo à un clan
    public function televerserImage(Request $request, $id){
        try {
            $request->validate([
                'imageClan' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
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
                return redirect()->route('clan.parametres.post', ['id' => $id])->with('success', 'Image téléversée avec succès');
            }

            Log::info('EEEEEE');
            return back()->with('error', 'Veuillez sélectionner une image valide à téléverser.');
        } catch (\Exception $e) {
            Log::error('Image upload failed: ' . $e->getMessage());
    
            return back()->with('error', 'Une erreur est survenue lors du téléversement de l\'image.');
        }
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
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
