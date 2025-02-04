<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Score;
use Illuminate\Support\Facades\DB;

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
        $scoreEntry->user_id = $utilisateur;
        $scoreEntry->score = $score;
        $scoreEntry->date = $date;
        $scoreEntry->save();
    }

    public function meilleursGroupes()
    {
        $userScores = DB::table('scores')
            ->select('user_id', DB::raw('SUM(score) as total_score'))
            ->groupBy('user_id')
            ->get();

        $topClans = DB::table('Clan_users as cu')
            ->join(DB::raw('(SELECT user_id, SUM(score) as total_score FROM scores GROUP BY user_id) as su'), 'cu.user_id', '=', 'su.user_id')
            ->join('clans', 'clans.id', '=', 'cu.clan_id')  // Join the Clan table to get image and name
            ->select('cu.clan_id', 'clans.nom as clan_nom', 'clans.image as clan_image', DB::raw('SUM(su.total_score) as clan_total_score'))
            ->groupBy('cu.clan_id', 'clans.nom', 'clans.image')
            ->orderByDesc('clan_total_score')  // Sort by total score in descending order
            ->limit(10)  // Get the top 10 clans
            ->get();


        return view('top_clans', compact('topClans')); // Send the result to a view
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
