<?php
// app/Http/Controllers/GraphiquePersoController.php
namespace App\Http\Controllers;

use App\Models\Clan;
use App\Models\GraphSauvegarde;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GraphiquePersoController extends Controller
{
    /**
     * Display the list of saved graphs
     */
    public function index()
    {
        $user = Auth::user();
        $clans = $user->clans;
        $graphs = GraphSauvegarde::where('user_id', $user->id)
            ->where('date_expiration', '>=', now())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('graphs.index', compact('graphs', 'clans'));
    }

    /**
     * Display the form to create a new graph
     */
    public function create()
    {
        $user = Auth::user();
        $clans = $user->clans()->get();

        return view('graphs.create', compact('clans'));
    }

    /**
     * Generate and store a custom graph
     */
    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|max:255',
            'type' => 'required|in:global,clan',
            'clan_id' => 'required_if:type,clan|exists:clans,id',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
        ]);

        $user = Auth::user();

        // Verify user clan membership if a clan is selected
        if ($request->type == 'clan') {
            $isMember = DB::table('clan_users')
                ->where('user_id', $user->id)
                ->where('clan_id', $request->clan_id)
                ->exists();

            if (!$isMember) {
                return redirect()->back()
                    ->with('error', __('graphs.erreur_membre_clan'));
            }
        }

        // Generate graph data
        $data = $this->generateGraphData(
            $request->type,
            $request->clan_id,
            $request->date_debut,
            $request->date_fin
        );

        // Save the graph
        try {
            $savedGraph = GraphSauvegarde::create([
                'user_id' => $user->id,
                'type' => $request->type,
                'clan_id' => $request->type == 'clan' ? $request->clan_id : null,
                'titre' => $request->titre,
                'date_debut' => $request->date_debut,
                'date_fin' => $request->date_fin,
                'data' => $data,
                'date_expiration' => Carbon::now()->addDays(90),
            ]);

            return redirect()->route('graphs.show', $savedGraph->id)
                ->with('success', __('graphs.cree_avec_succes'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('graphs.erreur_creation') . ': ' . $e->getMessage());
        }
    }

    /**
     * Show a specific saved graph
     */
    public function show($id)
    {
        try {
            $graph = GraphSauvegarde::findOrFail($id);

            // Check if user owns this graph
            if ($graph->user_id != Auth::id()) {
                return redirect()->route('graphs.index')
                    ->with('error', __('graphs.non_autorise'));
            }

            // Log for debugging
            \Log::debug('Graph data:', ['data' => $graph->data]);

            $user = Auth::user();
            $clans = $user->clans()->get();
            return view('graphs.show', compact('graph', 'clans'));
        } catch (\Exception $e) {
            return redirect()->route('graphs.index')
                ->with('error', __('graphs.erreur_affichage') . ': ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the graph
     */
    public function edit($id)
    {
        try {
            $graph = GraphSauvegarde::findOrFail($id);

            // Check if user owns this graph
            if ($graph->user_id != Auth::id()) {
                return redirect()->route('graphs.index')
                    ->with('error', __('graphs.non_autorise'));
            }

            $clans = Auth::user()->clans()->get();

            return view('graphs.edit', compact('graph', 'clans'));
        } catch (\Exception $e) {
            return redirect()->route('graphs.index')
                ->with('error', __('graphs.erreur_chargement_formulaire') . ': ' . $e->getMessage());
        }
    }

    /**
     * Update the specified graph
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'titre' => 'required|max:255',
            'type' => 'required|in:global,clan',
            'clan_id' => 'required_if:type,clan|exists:clans,id',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
        ]);

        try {
            $graph = GraphSauvegarde::findOrFail($id);

            // Check if user owns this graph
            if ($graph->user_id != Auth::id()) {
                return redirect()->route('graphs.index')
                    ->with('error', __('graphs.non_autorise'));
            }

            // Check if data needs regeneration
            $regenerateData = $graph->date_debut->format('Y-m-d') != $request->date_debut ||
                $graph->date_fin->format('Y-m-d') != $request->date_fin ||
                $graph->type != $request->type ||
                ($graph->type == 'clan' && $graph->clan_id != $request->clan_id);

            // Update graph properties
            $graph->titre = $request->titre;
            $graph->type = $request->type;
            $graph->clan_id = $request->type == 'clan' ? $request->clan_id : null;
            $graph->date_debut = $request->date_debut;
            $graph->date_fin = $request->date_fin;

            // Regenerate data if needed
            if ($regenerateData) {
                $graph->data = $this->generateGraphData(
                    $request->type,
                    $request->clan_id,
                    $request->date_debut,
                    $request->date_fin
                );
            }

            $graph->save();

            return redirect()->route('graphs.show', $graph->id)
                ->with('success', __('graphs.modifie_avec_succes'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('graphs.erreur_modification') . ': ' . $e->getMessage());
        }
    }

    /**
     * Delete a saved graph
     */
    public function destroy($id)
    {
        try {
            $graph = GraphSauvegarde::findOrFail($id);

            // Check if user owns this graph
            if ($graph->user_id != Auth::id()) {
                return redirect()->route('graphs.index')
                    ->with('error', __('graphs.non_autorise'));
            }

            $graph->delete();

            return redirect()->route('graphs.index')
                ->with('success', __('graphs.supprime_avec_succes'));
        } catch (\Exception $e) {
            return redirect()->route('graphs.index')
                ->with('error', __('graphs.erreur_suppression') . ': ' . $e->getMessage());
        }
    }

    /**
     * Generate the graph data based on type and date range
     */
    private function generateGraphData($type, $clanId, $startDate, $endDate)
    {
        // Make sure we're working with Carbon instances
        $startDate = $startDate instanceof Carbon ? $startDate : Carbon::parse($startDate);
        $endDate = $endDate instanceof Carbon ? $endDate : Carbon::parse($endDate);

        // Force time to be start/end of day to include all records
        $startDate = $startDate->startOfDay();
        $endDate = $endDate->endOfDay();

        // Verify we have data in the database for this date range
        $scoreCount = DB::table('scores')
            ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->count();

        // If we have no scores, log all available dates for debugging
        if ($scoreCount == 0) {
            $allScores = DB::table('scores')
                ->select('date')
                ->distinct()
                ->orderBy('date')
                ->get()
                ->pluck('date');
        }

        // Calculate the number of months between dates
        $diffInMonths = $startDate->diffInMonths($endDate) + 1;

        // Rest of your code...
        $labels = [];
        $values = [];

        // If dates are very close, use daily interval
        if ($diffInMonths <= 1) {
            $interval = 'daily';
            $current = $startDate->copy();
            while ($current <= $endDate) {
                $labels[] = $current->format('d M');

                // Get score for this day
                if ($type == 'global') {
                    $values[] = $this->getGlobalScoreForDay($current);
                } else {
                    $values[] = $this->getClanScoreForDay($clanId, $current);
                }

                $current = $current->addDay();
            }
        } else {
            // Monthly intervals for longer periods
            $current = $startDate->copy()->startOfMonth();
            while ($current <= $endDate) {
                $endOfMonth = min($current->copy()->endOfMonth(), $endDate);

                $labels[] = $current->format('M Y');

                // Get score for this month
                if ($type == 'global') {
                    $values[] = $this->getGlobalScoreForPeriod($current, $endOfMonth);
                } else {
                    $values[] = $this->getClanScoreForPeriod($clanId, $current, $endOfMonth);
                }

                $current = $current->addMonth();
            }
        }

        return [
            'labels' => $labels,
            'values' => $values,
            'interval' => $diffInMonths <= 1 ? 'daily' : 'monthly'
        ];
    }

    /**
     * Get global score data for a specific day
     */
    private function getGlobalScoreForDay(Carbon $date)
    {

        // Get all scores for this specific date, with debug info
        $scores = DB::table('scores')
            ->whereDate('date', $date->format('Y-m-d'))
            ->get();

        // Return the sum of scores
        return DB::table('scores')
            ->whereDate('date', $date->format('Y-m-d'))
            ->sum('score') ?: 0;
    }

    /**
     * Get clan score data for a specific day
     */
    private function getClanScoreForDay($clanId, Carbon $date)
    {

        // Get scores with debug info
        $scores = DB::table('scores')
            ->join('clan_users', 'scores.user_id', '=', 'clan_users.user_id')
            ->where('clan_users.clan_id', $clanId)
            ->whereDate('scores.date', $date->format('Y-m-d'))
            ->get();


        return DB::table('scores')
            ->join('clan_users', 'scores.user_id', '=', 'clan_users.user_id')
            ->where('clan_users.clan_id', $clanId)
            ->whereDate('scores.date', $date->format('Y-m-d'))
            ->sum('scores.score') ?: 0;
    }

    /**
     * Get global score data for a period
     */
    private function getGlobalScoreForPeriod(Carbon $startDate, Carbon $endDate)
    {
        return DB::table('scores')
            ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->sum('score') ?: 0;
    }

    /**
     * Get clan score data for a period
     */
    private function getClanScoreForPeriod($clanId, Carbon $startDate, Carbon $endDate)
    {
        return DB::table('scores')
            ->join('clan_users', 'scores.user_id', '=', 'clan_users.user_id')
            ->where('clan_users.clan_id', $clanId)
            ->whereBetween('scores.date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->sum('scores.score') ?: 0;
    }
}
