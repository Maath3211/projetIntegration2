<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Score;
use Illuminate\Support\Facades\DB;

class ScoresController extends Controller
{
    /**
     * Affiche une liste des ressources.
     */
    public function index()
    {
        //
    }

    /**
     * Affiche le formulaire pour créer un nouveau score.
     */
    public function createScore()
    {
        //
    }

    /**
     * Enregistre un nouveau score dans la base de données.
     */
    public function storeScore(Request $request)
    {
        // Récupère l'utilisateur connecté
        $utilisateur = Auth::user();
        // Obtient la date du jour
        $date = date('Y-m-d');
        // Récupère le score depuis la requête
        $score = $request->input('score');

        // Crée une nouvelle instance de Score
        $scoreEntry = new Score();
        $scoreEntry->user_id = $utilisateur;
        $scoreEntry->score = $score;
        $scoreEntry->date = $date;
        // Sauvegarde le score dans la base de données
        $scoreEntry->save();
    }

    /**
     * Obtient et affiche les meilleurs clans et utilisateurs pour le tableau de classement.
     */
    public function meilleursGroupes()
    {
        // Récupère l'utilisateur connecté et ses clans
        $utilisateur = Auth::user();
        $clans = $utilisateur->clans;
        // Définit le clan sélectionné par défaut (vue globale)
        $selectedClanId = 'global';
        
        // Récupère tous les scores des utilisateurs
        $userScores = DB::table('scores')
            ->select('user_id', DB::raw('SUM(score) as total_score'))
            ->groupBy('user_id')
            ->get();

        // Obtient les 10 meilleurs utilisateurs triés par score total
        $topUsers = DB::table('users')
            ->join('scores', 'users.id', '=', 'scores.user_id')
            ->select(
                'users.prenom',
                'users.nom',
                'users.imageProfil',
                'users.email as email',
                DB::raw('SUM(scores.score) as total_score')
            )
            ->groupBy('users.id', 'users.prenom', 'users.nom', 'users.imageProfil', 'users.email')
            ->orderByDesc('total_score')
            ->limit(10)
            ->get();

        // Obtient les 10 meilleurs clans triés par score total de leurs membres
        $topClans = DB::table('clan_users as cu')
            ->join(DB::raw('(SELECT user_id, SUM(score) as total_score FROM scores GROUP BY user_id) as su'), 'cu.user_id', '=', 'su.user_id')
            ->join('clans', 'clans.id', '=', 'cu.clan_id')  // Joint la table Clan pour obtenir l'image et le nom
            ->select('cu.clan_id', 'clans.nom as clan_nom', 'clans.image as clan_image', DB::raw('SUM(su.total_score) as clan_total_score'))
            ->groupBy('cu.clan_id', 'clans.nom', 'clans.image')
            ->orderByDesc('clan_total_score')  // Trie par score total en ordre décroissant
            ->limit(10)  // Obtient les 10 meilleurs clans
            ->get();

        // Récupère les clans publics dont l'utilisateur est membre
        $userClans = DB::table('clan_users')
            ->join('clans', 'clans.id', '=', 'clan_users.clan_id')
            ->where('clan_users.user_id', Auth::id())
            ->where('clans.public', 1) // Inclut uniquement les clans où public est 1
            ->select('clans.id as clan_id', 'clans.nom as clan_nom', 'clans.image as clan_image')
            ->get();

        // Retourne la vue avec les données pour le tableau de classement
        return view('leaderboard.topClans', compact('topClans', 'topUsers', 'userClans', 'selectedClanId', 'clans'));
    }

    /**
     * Exporte les 10 meilleurs utilisateurs en format CSV.
     */
    public function exportTopUsers()
    {
        // Obtient les 10 meilleurs utilisateurs triés par score total
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

        // Crée un nom de fichier avec la date du jour
        $filename = 'meilleurs_membres_global_' . date('d-m-Y') . '.csv';

        // Crée un descripteur de fichier temporaire pour notre CSV
        $handle = fopen('php://temp', 'r+');

        // Écrit l'en-tête CSV
        fputcsv($handle, ['Position', 'Prenom', 'Nom', 'Total Score']);

        // Écrit les lignes de données
        $position = 1;
        foreach ($topUsers as $user) {
            fputcsv($handle, [$position, $user->prenom, $user->nom, $user->total_score]);
            $position++;
        }

        // Réinitialise le pointeur de fichier au début
        rewind($handle);

        // Récupère le contenu
        $content = '';
        while (!feof($handle)) {
            $content .= fread($handle, 8192);
        }
        fclose($handle);

        // Retourne une réponse avec les en-têtes appropriés
        return response($content)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Exporte les 10 meilleurs clans en format CSV.
     */
    public function exportTopClans()
    {
        // Obtient les 10 meilleurs clans triés par score total
        $topClans = DB::table('clan_users as cu')
            ->join(DB::raw('(SELECT user_id, SUM(score) as total_score FROM scores GROUP BY user_id) as su'), 'cu.user_id', '=', 'su.user_id')
            ->join('clans', 'clans.id', '=', 'cu.clan_id')
            ->select('clans.nom as clan_nom', DB::raw('SUM(su.total_score) as clan_total_score'))
            ->groupBy('cu.clan_id', 'clans.nom')
            ->orderByDesc('clan_total_score')
            ->limit(10)
            ->get();

        // Crée un nom de fichier avec la date du jour
        $filename = 'meilleurs_clans_global_' . date('d-m-Y') . '.csv';

        // Crée un descripteur de fichier temporaire pour notre CSV
        $handle = fopen('php://temp', 'r+');

        // Écrit l'en-tête CSV
        fputcsv($handle, ['Position', 'Clan Name', 'Total Score']);

        // Écrit les lignes de données
        $position = 1;
        foreach ($topClans as $clan) {
            fputcsv($handle, [$position, $clan->clan_nom, $clan->clan_total_score]);
            $position++;
        }

        // Réinitialise le pointeur de fichier au début
        rewind($handle);

        // Récupère le contenu
        $content = '';
        while (!feof($handle)) {
            $content .= fread($handle, 8192);
        }
        fclose($handle);

        // Retourne une réponse avec les en-têtes appropriés
        return response($content)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Exporte les 10 meilleurs membres d'un clan spécifique en format CSV.
     */
    public function exportTopMembres($clanId)
    {
        // Obtient les 10 meilleurs membres du clan spécifié
        $topMembres = DB::table('users')
            ->join('clan_users', 'users.id', '=', 'clan_users.user_id')
            ->join('scores', 'users.id', '=', 'scores.user_id')
            ->where('clan_users.clan_id', $clanId)
            ->select(
                'users.imageProfil as user_image',
                'users.nom as user_nom',
                'users.prenom as user_prenom',
                'users.email as email',
                DB::raw('SUM(scores.score) as user_total_score')
            )
            ->groupBy('users.id', 'users.imageProfil', 'users.nom', 'users.prenom', 'users.email')
            ->orderByDesc('user_total_score')
            ->limit(10)
            ->get();

        // Récupère les informations du clan pour le nom du fichier
        $clan = DB::table('clans')->where('id', $clanId)->first();
        // Crée un slug pour le nom du clan (minuscules, espaces remplacés par des underscores)
        $clanSlug = strtolower(str_replace(' ', '_', $clan->nom));
        $filename = 'meilleurs_membres_' . $clanSlug . '_' . date('d-m-Y') . '.csv';

        // Crée un descripteur de fichier temporaire pour notre CSV
        $handle = fopen('php://temp', 'r+');

        // Écrit l'en-tête CSV
        fputcsv($handle, ['Position', 'Prenom', 'Nom', 'Total Score']);

        // Écrit les lignes de données
        $position = 1;
        foreach ($topMembres as $membre) {
            fputcsv($handle, [$position, $membre->user_prenom, $membre->user_nom, $membre->user_total_score]);
            $position++;
        }

        // Réinitialise le pointeur de fichier au début
        rewind($handle);

        // Récupère le contenu
        $content = '';
        while (!feof($handle)) {
            $content .= fread($handle, 8192);
        }
        fclose($handle);

        // Retourne une réponse avec les en-têtes appropriés
        return response($content)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Exporte les 10 membres avec la meilleure amélioration de score du mois en format CSV.
     */
    public function exportTopAmelioration($clanId)
    {
        // Calcule la date d'un mois en arrière
        $oneMonthAgo = now()->subMonth();

        // Obtient les 10 utilisateurs avec la plus grande amélioration de score ce mois-ci
        $topAmelioration = DB::table('users')
            ->join('clan_users', 'users.id', '=', 'clan_users.user_id')
            ->join('scores', 'users.id', '=', 'scores.user_id')
            ->where('clan_users.clan_id', $clanId)
            ->where('scores.created_at', '>=', $oneMonthAgo)
            ->select(
                'users.imageProfil as user_image',
                'users.nom as user_nom',
                'users.prenom as user_prenom',
                'users.email as email',
                DB::raw('SUM(scores.score) as score_improvement')
            )
            ->groupBy('users.id', 'users.imageProfil', 'users.nom', 'users.prenom', 'users.email')
            ->orderByDesc('score_improvement')
            ->limit(10)
            ->get();

        // Récupère les informations du clan pour le nom du fichier
        $clan = DB::table('clans')->where('id', $clanId)->first();
        $clanSlug = strtolower(str_replace(' ', '_', $clan->nom));
        $filename = 'meilleurs_ameliorations_' . $clanSlug . '_' . date('d-m-Y') . '.csv';

        // Crée un descripteur de fichier temporaire pour notre CSV
        $handle = fopen('php://temp', 'r+');

        // Écrit l'en-tête CSV
        fputcsv($handle, ['Position', 'Prenom', 'Nom', 'Improvement Score']);

        // Écrit les lignes de données
        $position = 1;
        foreach ($topAmelioration as $user) {
            fputcsv($handle, [$position, $user->user_prenom, $user->user_nom, $user->score_improvement]);
            $position++;
        }

        // Réinitialise le pointeur de fichier au début
        rewind($handle);

        // Récupère le contenu
        $content = '';
        while (!feof($handle)) {
            $content .= fread($handle, 8192);
        }
        fclose($handle);

        // Retourne une réponse avec les en-têtes appropriés
        return response($content)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Méthode de test pour afficher une page HTML statique.
     */
    public function testChart()
    {
        // Récupère le contenu du fichier static-html-test.blade.php
        $content = file_get_contents(resource_path('views/scores/static-html-test.blade.php'));

        // Retourne une réponse HTML simple
        return response($content)->header('Content-Type', 'text/html');
    }

    /**
     * Affiche la page avec le graphique des scores et les tableaux de classement.
     */
    public function showChart()
    {
        // Récupère les mêmes données que pour le tableau de classement
        $selectedClanId = 'global';

        // Obtient les 10 meilleurs utilisateurs triés par score total
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

        // Obtient les 10 meilleurs clans triés par score total
        $topClans = DB::table('clan_users as cu')
            ->join(DB::raw('(SELECT user_id, SUM(score) as total_score FROM scores GROUP BY user_id) as su'), 'cu.user_id', '=', 'su.user_id')
            ->join('clans', 'clans.id', '=', 'cu.clan_id')
            ->select('cu.clan_id', 'clans.nom as clan_nom', 'clans.image as clan_image', DB::raw('SUM(su.total_score) as clan_total_score'))
            ->groupBy('cu.clan_id', 'clans.nom', 'clans.image')
            ->orderByDesc('clan_total_score')
            ->limit(10)
            ->get();

        // Prépare les données pour le graphique
        $months = [];
        $clanScores = [];
        $userScores = [];

        // Génère des données pour les 6 derniers mois
        for ($i = 5; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $months[] = date('M Y', strtotime($month));

            $startOfMonth = date('Y-m-01', strtotime($month));
            $endOfMonth = date('Y-m-t', strtotime($month));

            // Récupère les scores réels des clans pour ce mois
            $monthClanScore = DB::table('clan_users as cu')
                ->join('scores', function ($join) use ($startOfMonth, $endOfMonth) {
                    $join->on('cu.user_id', '=', 'scores.user_id')
                        ->whereBetween('scores.date', [$startOfMonth, $endOfMonth]);
                })
                ->sum('scores.score');
            
            // Utilise 0 au lieu de données aléatoires si aucun score n'est trouvé
            $clanScores[] = $monthClanScore ?: 0;

            // Récupère les scores réels des utilisateurs pour ce mois
            $monthUserScore = DB::table('scores')
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->sum('score');
            
            // Utilise 0 au lieu de données aléatoires si aucun score n'est trouvé
            $userScores[] = $monthUserScore ?: 0;
        }

        // Retourne la vue avec toutes les données nécessaires
        return view('scores.chart-page', compact('months', 'clanScores', 'userScores', 'topClans', 'topUsers'));
    }

    /**
     * Affiche uniquement le graphique des scores.
     */
    public function viewScoreGraph()
    {
        // Génère les dates des 6 derniers mois
        $months = [];
        $clanScores = [];
        $userScores = [];

        // Boucle pour les 6 derniers mois
        for ($i = 5; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $months[] = date('M Y', strtotime($month)); // Mois formaté pour l'affichage

            $startOfMonth = date('Y-m-01', strtotime($month));
            $endOfMonth = date('Y-m-t', strtotime($month));

            // Récupère les scores des clans pour ce mois (ou utilise des données fictives)
            $monthClanScore = DB::table('clan_users as cu')
                ->join('scores', function ($join) use ($startOfMonth, $endOfMonth) {
                    $join->on('cu.user_id', '=', 'scores.user_id')
                        ->whereBetween('scores.date', [$startOfMonth, $endOfMonth]);
                })
                ->sum('scores.score');

            // Utilise 0 au lieu de données aléatoires si aucun score n'est trouvé
            $clanScores[] = $monthClanScore ?: 0;

            // Récupère les scores des utilisateurs pour ce mois (ou utilise des données fictives)
            $monthUserScore = DB::table('scores')
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->sum('score');

            // Utilise 0 au lieu de données aléatoires si aucun score n'est trouvé
            $userScores[] = $monthUserScore ?: 0;
        }

        // Retourne la vue avec les données du graphique
        return view('scores.graph', compact('months', 'clanScores', 'userScores'));
    }

    /**
     * Affiche la ressource spécifiée.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Affiche le formulaire pour modifier la ressource spécifiée.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Met à jour la ressource spécifiée dans la base de données.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Supprime la ressource spécifiée de la base de données.
     */
    public function destroy(string $id)
    {
        //
    }
}
