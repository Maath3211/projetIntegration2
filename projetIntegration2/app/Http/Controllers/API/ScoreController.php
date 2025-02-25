<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Clan;

class ScoreController extends Controller
{
    public function getScoreGraph(Request $request)
    {
        // Validate request
        $request->validate([
            'showType' => 'nullable|string|in:users,clans,members,improvements',
            'selectedClanId' => 'nullable|integer',
        ]);

        $showType = $request->showType ?? 'users';
        $selectedClanId = $request->selectedClanId;

        // Generate data for last 6 months (same logic as your Livewire component)
        $months = [];
        $clanScores = [];
        $userScores = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $months[] = $month->format('M Y');

            $startOfMonth = $month->startOfMonth()->format('Y-m-d');
            $endOfMonth = $month->endOfMonth()->format('Y-m-d');

            // Get clan scores
            try {
                $clanQuery = DB::table('clan_users as cu')
                    ->join('scores', function ($join) use ($startOfMonth, $endOfMonth) {
                        $join->on('cu.user_id', '=', 'scores.user_id')
                            ->whereBetween('scores.date', [$startOfMonth, $endOfMonth]);
                    });

                // Filter by clan if selected
                if ($selectedClanId && $selectedClanId != 'global') {
                    $clanQuery->where('cu.clan_id', $selectedClanId);
                }

                $clanScore = $clanQuery->sum('scores.score');
                $clanScores[] = $clanScore ?: 0; // Return 0 instead of random numbers
            } catch (\Exception $e) {
                $clanScores[] = 0; // Return 0 on error
            }

            // Get user scores or member scores if clan is selected
            try {
                $userQuery = DB::table('scores')
                    ->whereBetween('date', [$startOfMonth, $endOfMonth]);

                // Filter by clan if selected
                if ($selectedClanId && $selectedClanId != 'global') {
                    $userQuery->join('clan_users', 'scores.user_id', '=', 'clan_users.user_id')
                        ->where('clan_users.clan_id', $selectedClanId);
                }

                $userScore = $userQuery->sum('scores.score');
                $userScores[] = $userScore ?: 0; // Return 0 instead of random numbers
            } catch (\Exception $e) {
                $userScores[] = 0; // Return 0 on error
            }
        }

        return response()->json([
            'months' => $months,
            'clanScores' => $clanScores,
            'userScores' => $userScores,
        ]);
    }

    public function getTopImprovements(Request $request)
    {
        // Validate request
        $request->validate([
            'clanId' => 'required|integer',
        ]);

        $clanId = $request->clanId;
        $oneMonthAgo = Carbon::now()->subMonth()->format('Y-m-d');

        // Get top improvements (using 'date' field instead of 'created_at')
        $topScoreImprovement = DB::table('users')
            ->join('clan_users', 'users.id', '=', 'clan_users.user_id')
            ->join('scores', 'users.id', '=', 'scores.user_id')
            ->where('clan_users.clan_id', $clanId)
            ->where('scores.date', '>=', $oneMonthAgo) // Using date instead of created_at
            ->select(
                'users.imageProfil as user_image',
                'users.nom as user_nom',
                'users.prenom as user_prenom',
                DB::raw('SUM(scores.score) as score_improvement')
            )
            ->groupBy('users.id', 'users.imageProfil', 'users.nom', 'users.prenom')
            ->orderByDesc('score_improvement')
            ->limit(10)
            ->get();

        return response()->json([
            'topScoreImprovement' => $topScoreImprovement
        ]);
    }

    public function getClans()
    {
        // Fetch all clans or the ones the user has access to
        $clans = Clan::select('id', 'name')
            ->orderBy('name')
            ->get();

        return response()->json($clans);
    }
}
