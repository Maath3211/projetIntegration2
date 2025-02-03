<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ScoresController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createScore()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeScore(Request $request)
    {
        $utilisateur = Auth::user();
        $date = date('Y-m-d');
        $score = $request->input('score');

        $scoreEntry = new Score();
        $scoreEntry->utilisateur = $utilisateur;
        $scoreEntry->date = $score;
        $scoreEntry->save();
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
