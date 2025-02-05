<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StatistiqueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return View("statistique.index");
    }


    public function graphique()
    {
        return View("statistique.graphique");
    }

    public function thermique()
    {
        $data = [
            [0, 0, 10], [0, 1, 20], [0, 2, 30],
            [1, 0, 15], [1, 1, 25], [1, 2, 35],
            [2, 0, 20], [2, 1, 30], [2, 2, 40],
        ];
        return View("statistique.thermique", compact('data'));
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
