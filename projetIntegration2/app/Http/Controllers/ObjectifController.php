<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Objectif;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ObjectifController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $objectifCompleter = Objectif::where("user_id", Auth::id())->where("completer", true)->get();
        $objectifNonCompleter = Objectif::where("user_id", Auth::id())->where("completer", false)->get();
        $utilisateur = Auth::user();
        $clans = $utilisateur->clans; // Fetch all clans associated with the user
        return view('objectif.index', compact('objectifCompleter', 'objectifNonCompleter', 'clans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:50',
            'description' => 'required|string|max:255',
        ], [
            'titre.required' => 'Le titre est obligatoire.',
            'titre.string' => 'Le titre doit être une chaîne de caractères.',
            'titre.max' => 'Le titre ne peut pas dépasser 50 caractères.',
            'description.required' => 'La description est obligatoire.',
            'description.string' => 'La description doit être une chaîne de caractères.',
            'description.max' => 'La description ne peut pas dépasser 255 caractères.',
        ]);
        
        Objectif::create([
            'titre' => $request->input('titre'),
            'description' => $request->input('description'),
            'completer' => false,
            'user_id' => auth()->id(),
        ]);
     
        return redirect()->route('objectif.index')->with('success', __('objectives.cree_avec_success'));
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'titre' => 'required|string|max:50',
            'description' => 'required|string|max:255',
        ], [
            'titre.required' => 'Le titre est obligatoire.',
            'titre.string' => 'Le titre doit être une chaîne de caractères.',
            'titre.max' => 'Le titre ne peut pas dépasser 50 caractères.',
            'description.required' => 'La description est obligatoire.',
            'description.string' => 'La description doit être une chaîne de caractères.',
            'description.max' => 'La description ne peut pas dépasser 255 caractères.',
        ]);

  
    $objectif = Objectif::findOrFail($id);
    $objectif->update([
        'titre' => $request->input('titre'),
        'description' => $request->input('description'),
    ]);


    return redirect()->route('objectif.index')->with('success', __('objectives.mis_a_jourd_successfully'));
    }
    

    public function updateComplet(Request $request, $id)
    {
      
        $objectif = Objectif::findOrFail($id);
        $objectif->update([
            'completer' => $request->input('completer'),
        ]);
        return redirect()->route('objectif.index')->with('success', 'Objectif mis à jour avec succès !');
    }  


    public function destroy($id)
    {
        Objectif::destroy($id);
        return redirect()->route('objectif.index')->with('success', __('objectives.supprimer_avec_success'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('objectif.create');
    }

    /**
     * Store a newly created resource in storage.
     */


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
        $objectif = Objectif::findOrFail($id);
        return view('objectif.edit', compact('objectif'));
    }



    /**
     * Update the specified resource in storage.
     */


    /**
     * Remove the specified resource from storage.
     */
   
}
