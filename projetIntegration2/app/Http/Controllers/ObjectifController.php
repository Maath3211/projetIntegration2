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
        $objectifs = Objectif::all();
        return view('objectif.index', compact('objectifs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
        ]);
    
        Objectif::create([
            'titre' => $request->input('titre'),
            'description' => $request->input('description'),
            'completer' => false,
            'user_id' => auth()->id(),
        ]);
     
        return redirect()->route('objectif.index')->with('success', 'Objectif ajouté avec succès !');
    }

    public function update(Request $request, $id)
    {

    $request->validate([
        'titre' => 'required|string|max:255',
        'description' => 'required|string|max:1000',
        'complet' => 'nullable|boolean',
    ]);

  
    $objectif = Objectif::findOrFail($id);
    $objectif->update([
        'titre' => $request->input('titre'),
        'description' => $request->input('description'),
        'complet' => $request->has('complet') ? true : false,
    ]);


    return redirect()->route('objectif.index')->with('success', 'Objectif mis à jour avec succès !');
    }

    public function destroy($id)
    {
        Objectif::destroy($id);
        return redirect()->route('objectif.index');
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
