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

        $topUsers = DB::table('users')
            ->join('scores', 'users.id', '=', 'scores.user_id')
            ->select(
                'users.prenom',
                'users.nom',
                'users.imageProfil',
                DB::raw('SUM(scores.score) as total_score')
            )
            ->groupBy('users.id', 'users.prenom', 'users.nom', 'users.imageProfil')
            ->orderByDesc('total_score')
            ->limit(10)
            ->get();

        $topClans = DB::table('Clan_users as cu')
            ->join(DB::raw('(SELECT user_id, SUM(score) as total_score FROM scores GROUP BY user_id) as su'), 'cu.user_id', '=', 'su.user_id')
            ->join('clans', 'clans.id', '=', 'cu.clan_id')  // Join the Clan table to get image and name
            ->select('cu.clan_id', 'clans.nom as clan_nom', 'clans.image as clan_image', DB::raw('SUM(su.total_score) as clan_total_score'))
            ->groupBy('cu.clan_id', 'clans.nom', 'clans.image')
            ->orderByDesc('clan_total_score')  // Sort by total score in descending order
            ->limit(10)  // Get the top 10 clans
            ->get();


        return view('Leaderboard.topClans', compact('topClans', 'topUsers')); // Send the result to a view
    }

    public function exportTopUsers()
    {
        $topUsers = DB::table('users')
            ->join('scores', 'users.id', '=', 'scores.user_id')
            ->select(
                'users.prenom',
                'users.nom',
                DB::raw('SUM(scores.score) as total_score')
            )
            ->groupBy('users.id', 'users.prenom', 'users.nom')
            ->orderByDesc('total_score')
            ->limit(10)
            ->get();

        $filename = 'top_users.csv';
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        // CSV Header: include position column
        fputcsv($output, ['Position', 'Prenom', 'Nom', 'Total Score']);

        $position = 1;
        foreach ($topUsers as $user) {
            fputcsv($output, [$position, $user->prenom, $user->nom, $user->total_score]);
            $position++;
        }
        fclose($output);
        exit;
    }

    public function exportTopClans()
    {
        $topClans = DB::table('Clan_users as cu')
            ->join(DB::raw('(SELECT user_id, SUM(score) as total_score FROM scores GROUP BY user_id) as su'), 'cu.user_id', '=', 'su.user_id')
            ->join('clans', 'clans.id', '=', 'cu.clan_id')
            ->select('clans.nom as clan_nom', DB::raw('SUM(su.total_score) as clan_total_score'))
            ->groupBy('cu.clan_id', 'clans.nom')
            ->orderByDesc('clan_total_score')
            ->limit(10)
            ->get();

        $filename = 'top_clans.csv';
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        // CSV Header: First column is the position
        fputcsv($output, ['Position', 'Clan Name', 'Total Score']);

        $position = 1;
        foreach ($topClans as $clan) {
            fputcsv($output, [$position, $clan->clan_nom, $clan->clan_total_score]);
            $position++;
        }
        fclose($output);
        exit;
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
