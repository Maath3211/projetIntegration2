@extends('layouts.app')
@section('titre', $graph->titre)
@section('style')
<!-- Importation de la feuille de style spécifique pour les graphiques -->
<link rel="stylesheet" style="text/css" href="{{asset('css/graphs/graphs.css')}}">
@endsection

@section('contenu')
<!-- Conteneur principal avec marge supérieure -->
<div class="container mt-4">
    <!-- Ligne avec centrage du contenu -->
    <div class="row justify-content-center">
        <!-- Colonne centrale occupant 10/12 de la largeur sur écrans moyens et grands -->
        <div class="col-md-10">
            <!-- Carte avec ombre légère pour encadrer le contenu -->
            <div class="card shadow-sm">
                <!-- En-tête de la carte avec le titre du graphique et les boutons d'action -->
                <div class="card-header d-flex justify-content-between align-items-center">
                    <!-- Titre du graphique affiché en grand -->
                    <h1 class="h3 mb-0 text-white">{{ $graph->titre }}</h1>
                    <!-- Conteneur pour les boutons d'action -->
                    <div>
                        <!-- Bouton pour retourner à la liste des graphiques -->
                        <a href="{{ route('graphs.index') }}" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left me-1"></i> {{ __('graphs.retour') }}
                        </a>
                        <!-- Bouton pour créer un nouveau graphique -->
                        <a href="{{ route('graphs.create') }}" class="btn bouton">
                            <i class="fas fa-plus-circle me-1"></i> {{ __('graphs.nouveau_graphique') }}
                        </a>
                    </div>
                </div>
                <!-- Corps de la carte contenant les informations et le graphique -->
                <div class="card-body">
                    <!-- Section des informations sur le graphique -->
                    <div class="row mb-4">
                        <!-- Colonne pour les métadonnées du graphique -->
                        <div class="col-md-6">
                            <!-- Carte secondaire pour les informations -->
                            <div class="card">
                                <div class="card-body p-3">
                                    <!-- Titre de la section d'informations -->
                                    <h5 class="card-title">{{ __('graphs.informations') }}</h5>
                                    <!-- Tableau des informations du graphique -->
                                    <table class="table table-sm table-borderless mb-0">
                                        <!-- Ligne pour le type de graphique -->
                                        <tr>
                                            <td class="fw-bold text-white">{{ __('graphs.type') }}:</td>
                                            <td class="text-white">
                                                <!-- Affichage différent selon le type de graphique -->
                                                @if($graph->type == 'global')
                                                {{ __('graphs.pointages_global') }}
                                                @elseif($graph->clan)
                                                {{ __('graphs.pointages_clan') }}: {{ $graph->clan->nom }}
                                                @endif
                                            </td>
                                        </tr>
                                        <!-- Ligne pour la période couverte par le graphique -->
                                        <tr>
                                            <td class="fw-bold text-white">{{ __('graphs.periode_label') }}</td>
                                            <td class="text-white">{{ $graph->date_debut->format('d/m/Y') }} - {{ $graph->date_fin->format('d/m/Y') }}</td>
                                        </tr>
                                        <!-- Ligne pour la date de création du graphique -->
                                        <tr>
                                            <td class="fw-bold text-white">{{ __('graphs.cree_le') }}:</td>
                                            <td class="text-white">{{ $graph->created_at->format('d/m/Y') }}</td>
                                        </tr>
                                        <!-- Ligne pour la date d'expiration du graphique -->
                                        <tr>
                                            <td class="fw-bold text-white">{{ __('graphs.expire_le') }}:</td>
                                            <td class="text-white">{{ $graph->date_expiration->format('d/m/Y') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Conteneur pour le graphique avec hauteur minimale -->
                    <div class="chart-container" style="position: relative; min-height: 400px;">
                        <!-- Élément canvas où le graphique sera dessiné par Chart.js -->
                        <canvas id="scoreChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<!-- Importation de la bibliothèque Chart.js pour le rendu du graphique -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Attente du chargement complet du DOM avant d'initialiser le graphique
    document.addEventListener('DOMContentLoaded', function() {
        // Récupération du contexte de dessin 2D du canvas
        const ctx = document.getElementById('scoreChart').getContext('2d');
        // Récupération des données du graphique depuis le serveur
        const data = @json($graph -> data);

        // Ajout des traductions pour les mois et autres éléments du graphique
        const translations = {
            clanScores: "{{ __('charts.pointages_clan') }}",
            userScores: "{{ __('charts.pointages_utilisateurs') }}",
            score: "{{ __('charts.score') }}",
            months: "{{ __('charts.months') }}",
            jan: "{{ __('charts.jan') }}",
            feb: "{{ __('charts.fev') }}",
            mar: "{{ __('charts.mar') }}",
            apr: "{{ __('charts.avr') }}",
            may: "{{ __('charts.mai') }}",
            jun: "{{ __('charts.juin') }}",
            jul: "{{ __('charts.jul') }}",
            aug: "{{ __('charts.aug') }}",
            sep: "{{ __('charts.sep') }}",
            oct: "{{ __('charts.oct') }}",
            nov: "{{ __('charts.nov') }}",
            dec: "{{ __('charts.dec') }}"
        };

        // Traduction des étiquettes de mois si l'intervalle est mensuel
        if (data.interval === 'monthly') {
            data.labels = data.labels.map(label => {
                const parts = label.split(' ');
                if (parts.length === 2) {
                    const monthAbbr = parts[0].toLowerCase();
                    const year = parts[1];

                    // Obtention de la clé de traduction basée sur l'abréviation du mois
                    let translationKey;
                    switch(monthAbbr) {
                        case 'jan': translationKey = 'jan'; break;
                        case 'feb': translationKey = 'feb'; break;
                        case 'mar': translationKey = 'mar'; break;
                        case 'apr': translationKey = 'apr'; break;
                        case 'may': translationKey = 'may'; break;
                        case 'jun': translationKey = 'jun'; break;
                        case 'jul': translationKey = 'jul'; break;
                        case 'aug': translationKey = 'aug'; break;
                        case 'sep': translationKey = 'sep'; break;
                        case 'oct': translationKey = 'oct'; break;
                        case 'nov': translationKey = 'nov'; break;
                        case 'dec': translationKey = 'dec'; break;
                        default: translationKey = monthAbbr;
                    }

                    // Retourne le mois traduit avec l'année
                    return translations[translationKey] + ' ' + year;
                }
                return label;
            });
        }

        // Définition des couleurs selon le type de graphique pour maintenir la cohérence visuelle
        const chartColor = '{{ $graph->type == "global" ? "#2196F3" : "#4CAF50" }}';
        const bgColor = '{{ $graph->type == "global" ? "rgba(33, 150, 243, 0.1)" : "rgba(76, 175, 80, 0.1)" }}';

        // Création de l'instance Chart.js avec les données et les options de configuration
        new Chart(ctx, {
            // Type de graphique - ligne pour montrer l'évolution dans le temps
            type: 'line',
            // Données du graphique
            data: {
                // Étiquettes pour l'axe X (jours ou mois)
                labels: data.labels,
                // Ensemble de données à afficher
                datasets: [{
                    // Libellé de la série de données selon le type de graphique
                    label: '{{ $graph->type == "global" ? __("graphs.pointages_global") : __("graphs.pointages_clan") }}',
                    // Valeurs pour l'axe Y
                    data: data.values,
                    // Couleur de la ligne du graphique
                    borderColor: chartColor,
                    // Couleur de fond sous la ligne
                    backgroundColor: bgColor,
                    // Niveau de lissage de la courbe (0 = ligne droite, 1 = très courbé)
                    tension: 0.4,
                    // Remplissage sous la courbe activé
                    fill: true,
                    // Épaisseur de la ligne
                    borderWidth: 3,
                    // Style des points sur la ligne
                    pointBackgroundColor: '#fff',
                    pointBorderColor: chartColor,
                    pointRadius: 5
                }]
            },
            // Options de configuration du graphique
            options: {
                // Adaptation automatique à la taille du conteneur
                responsive: true,
                // Ne pas conserver le ratio hauteur/largeur
                maintainAspectRatio: false,
                // Configuration des axes
                scales: {
                    // Axe Y (vertical)
                    y: {
                        // Commencer à zéro pour une meilleure perspective
                        beginAtZero: true,
                        // Titre de l'axe Y
                        title: {
                            display: true,
                            text: '{{ __("graphs.points") }}',
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
                        },
                        // Style des graduations
                        ticks: {
                            font: {
                                size: 12
                            }
                        },
                        // Couleur des lignes de la grille
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    // Axe X (horizontal)
                    x: {
                        // Titre de l'axe X adapté à l'intervalle des données
                        title: {
                            display: true,
                            text: data.interval === 'daily' ? '{{ __("graphs.jours_label") }}' : '{{ __("graphs.mois_label") }}',
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
                        },
                        // Style des graduations
                        ticks: {
                            font: {
                                size: 12
                            }
                        },
                        // Couleur des lignes de la grille
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    }
                },
                // Configuration des plugins Chart.js
                plugins: {
                    // Configuration de la légende
                    legend: {
                        labels: {
                            font: {
                                size: 14
                            },
                            // Utilisation de points circulaires dans la légende
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    // Configuration des infobulles au survol
                    tooltip: {
                        // Fond semi-transparent pour l'infobulle
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        // Style de la police pour le titre
                        titleFont: {
                            size: 14
                        },
                        // Style de la police pour le contenu
                        bodyFont: {
                            size: 13
                        },
                        // Espacement interne de l'infobulle
                        padding: 10,
                        // Bords arrondis
                        cornerRadius: 5,
                        // Ne pas afficher les couleurs des séries dans l'infobulle
                        displayColors: false
                    }
                }
            }
        });
    });
</script>
@endsection
@endsection