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
        $selectedClanId = 'global';
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
                'users.email',
                DB::raw('SUM(scores.score) as total_score')
            )
            ->groupBy('users.id', 'users.prenom', 'users.nom', 'users.imageProfil', 'users.email')
            ->orderByDesc('total_score')
            ->limit(10)
            ->get();

        $topClans = DB::table('clan_users as cu')
            ->join(DB::raw('(SELECT user_id, SUM(score) as total_score FROM scores GROUP BY user_id) as su'), 'cu.user_id', '=', 'su.user_id')
            ->join('clans', 'clans.id', '=', 'cu.clan_id')  // Join the Clan table to get image and name
            ->select('cu.clan_id', 'clans.nom as clan_nom', 'clans.image as clan_image', DB::raw('SUM(su.total_score) as clan_total_score'))
            ->groupBy('cu.clan_id', 'clans.nom', 'clans.image')
            ->orderByDesc('clan_total_score')  // Sort by total score in descending order
            ->limit(10)  // Get the top 10 clans
            ->get();

        $userClans = DB::table('clan_users')
            ->join('clans', 'clans.id', '=', 'clan_users.clan_id')
            ->where('clan_users.user_id', Auth::id())
            ->where('clans.public', 1) // Only include clans where public is 1
            ->select('clans.id as clan_id', 'clans.nom as clan_nom', 'clans.image as clan_image')
            ->get();


        return view('Leaderboard.topClans', compact('topClans', 'topUsers', 'userClans', 'selectedClanId')); // Send the result to a view
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

        $filename = 'meilleurs_membres_global_' . date('d-m-Y') . '.csv';

        // Create a temporary file handle for our CSV
        $handle = fopen('php://temp', 'r+');

        // Write CSV header
        fputcsv($handle, ['Position', 'Prenom', 'Nom', 'Total Score']);

        // Write data rows
        $position = 1;
        foreach ($topUsers as $user) {
            fputcsv($handle, [$position, $user->prenom, $user->nom, $user->total_score]);
            $position++;
        }

        // Reset the file pointer to the beginning
        rewind($handle);

        // Get the content
        $content = '';
        while (!feof($handle)) {
            $content .= fread($handle, 8192);
        }
        fclose($handle);

        // Return as a response with appropriate headers
        return response($content)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function exportTopClans()
    {
        $topClans = DB::table('clan_users as cu')
            ->join(DB::raw('(SELECT user_id, SUM(score) as total_score FROM scores GROUP BY user_id) as su'), 'cu.user_id', '=', 'su.user_id')
            ->join('clans', 'clans.id', '=', 'cu.clan_id')
            ->select('clans.nom as clan_nom', DB::raw('SUM(su.total_score) as clan_total_score'))
            ->groupBy('cu.clan_id', 'clans.nom')
            ->orderByDesc('clan_total_score')
            ->limit(10)
            ->get();

        $filename = 'meilleurs_clans_global_' . date('d-m-Y') . '.csv';

        // Create a temporary file handle for our CSV
        $handle = fopen('php://temp', 'r+');

        // Write CSV header
        fputcsv($handle, ['Position', 'Clan Name', 'Total Score']);

        // Write data rows
        $position = 1;
        foreach ($topClans as $clan) {
            fputcsv($handle, [$position, $clan->clan_nom, $clan->clan_total_score]);
            $position++;
        }

        // Reset the file pointer to the beginning
        rewind($handle);

        // Get the content
        $content = '';
        while (!feof($handle)) {
            $content .= fread($handle, 8192);
        }
        fclose($handle);

        // Return as a response with appropriate headers
        return response($content)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function exportTopMembres($clanId)
    {
        $topMembres = DB::table('users')
            ->join('clan_users', 'users.id', '=', 'clan_users.user_id')
            ->join('scores', 'users.id', '=', 'scores.user_id')
            ->where('clan_users.clan_id', $clanId)
            ->select(
                'users.imageProfil as user_image',
                'users.nom as user_nom',
                'users.prenom as user_prenom',
                'users.email as user_email',
                DB::raw('SUM(scores.score) as user_total_score')
            )
            ->groupBy('users.id', 'users.imageProfil', 'users.nom', 'users.prenom', 'users.email')
            ->orderByDesc('user_total_score')
            ->limit(10)
            ->get();

        $clan = DB::table('clans')->where('id', $clanId)->first();
        $clanSlug = strtolower(str_replace(' ', '_', $clan->nom));
        $filename = 'meilleurs_membres_' . $clanSlug . '_' . date('d-m-Y') . '.csv';

        // Create a temporary file handle for our CSV
        $handle = fopen('php://temp', 'r+');

        // Write CSV header
        fputcsv($handle, ['Position', 'Prenom', 'Nom', 'Total Score']);

        // Write data rows
        $position = 1;
        foreach ($topMembres as $membre) {
            fputcsv($handle, [$position, $membre->user_prenom, $membre->user_nom, $membre->user_total_score]);
            $position++;
        }

        // Reset the file pointer to the beginning
        rewind($handle);

        // Get the content
        $content = '';
        while (!feof($handle)) {
            $content .= fread($handle, 8192);
        }
        fclose($handle);

        // Return as a response with appropriate headers
        return response($content)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function exportTopAmelioration($clanId)
    {
        $oneMonthAgo = now()->subMonth();

        $topAmelioration = DB::table('users')
            ->join('clan_users', 'users.id', '=', 'clan_users.user_id')
            ->join('scores', 'users.id', '=', 'scores.user_id')
            ->where('clan_users.clan_id', $clanId)
            ->where('scores.created_at', '>=', $oneMonthAgo)
            ->select(
                'users.imageProfil as user_image',
                'users.nom as user_nom',
                'users.prenom as user_prenom',
                'users.email as user_email',
                DB::raw('SUM(scores.score) as score_improvement')
            )
            ->groupBy('users.id', 'users.imageProfil', 'users.nom', 'users.prenom', 'users.email')
            ->orderByDesc('score_improvement')
            ->limit(10)
            ->get();

        $clan = DB::table('clans')->where('id', $clanId)->first();
        $clanSlug = strtolower(str_replace(' ', '_', $clan->nom));
        $filename = 'meilleurs_ameliorations_' . $clanSlug . '_' . date('d-m-Y') . '.csv';

        // Create a temporary file handle for our CSV
        $handle = fopen('php://temp', 'r+');

        // Write CSV header
        fputcsv($handle, ['Position', 'Prenom', 'Nom', 'Improvement Score']);

        // Write data rows
        $position = 1;
        foreach ($topAmelioration as $user) {
            fputcsv($handle, [$position, $user->user_prenom, $user->user_nom, $user->score_improvement]);
            $position++;
        }

        // Reset the file pointer to the beginning
        rewind($handle);

        // Get the content
        $content = '';
        while (!feof($handle)) {
            $content .= fread($handle, 8192);
        }
        fclose($handle);

        // Return as a response with appropriate headers
        return response($content)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function testChart()
    {
        // Get the content of static-html-test.blade.php
        $content = file_get_contents(resource_path('views/scores/static-html-test.blade.php'));

        // Return as a plain HTML response
        return response($content)->header('Content-Type', 'text/html');
    }

    public function showChart()
    {
        // Get the same data you'd show on the leaderboard
        $selectedClanId = 'global';

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

        $topClans = DB::table('clan_users as cu')
            ->join(DB::raw('(SELECT user_id, SUM(score) as total_score FROM scores GROUP BY user_id) as su'), 'cu.user_id', '=', 'su.user_id')
            ->join('clans', 'clans.id', '=', 'cu.clan_id')
            ->select('cu.clan_id', 'clans.nom as clan_nom', 'clans.image as clan_image', DB::raw('SUM(su.total_score) as clan_total_score'))
            ->groupBy('cu.clan_id', 'clans.nom', 'clans.image')
            ->orderByDesc('clan_total_score')
            ->limit(10)
            ->get();

        // Create chart data
        $months = [];
        $clanScores = [];
        $userScores = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $months[] = date('M Y', strtotime($month));

            // Use sample data for now - replace with actual queries if needed
            $clanScores[] = rand(1000, 2000);
            $userScores[] = rand(700, 1500);
        }

        return view('scores.chart-page', compact('months', 'clanScores', 'userScores', 'topClans', 'topUsers'));
    }

    public function viewScoreGraph()
    {
        // Generate last 6 months of dates
        $months = [];
        $clanScores = [];
        $userScores = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $months[] = date('M Y', strtotime($month)); // Formatted month for display

            $startOfMonth = date('Y-m-01', strtotime($month));
            $endOfMonth = date('Y-m-t', strtotime($month));

            // Get clan scores for this month (or use dummy data)
            $monthClanScore = DB::table('clan_users as cu')
                ->join('scores', function ($join) use ($startOfMonth, $endOfMonth) {
                    $join->on('cu.user_id', '=', 'scores.user_id')
                        ->whereBetween('scores.date', [$startOfMonth, $endOfMonth]);
                })
                ->sum('scores.score');

            $clanScores[] = $monthClanScore ?: rand(1000, 2000); // Fallback to random data

            // Get user scores for this month (or use dummy data)
            $monthUserScore = DB::table('scores')
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->sum('score');

            $userScores[] = $monthUserScore ?: rand(700, 1500); // Fallback to random data
        }

        return view('scores.graph', compact('months', 'clanScores', 'userScores'));
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
