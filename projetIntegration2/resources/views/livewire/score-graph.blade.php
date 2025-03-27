<div>
    <!-- Champs cachés contenant les données pour les graphiques -->
    <input type="hidden" x-ref="months" value="{{ json_encode($months) }}">
    <input type="hidden" x-ref="clanScores" value="{{ json_encode($clanScores) }}">
    <input type="hidden" x-ref="userScores" value="{{ json_encode($userScores) }}">
    <input type="hidden" x-ref="showType" value="{{ $showType }}">

    <div class="card">
        <!-- En-tête du graphique avec titre dynamique et bouton de retour -->
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                <!-- Titre du graphique qui change selon le type affiché -->
                @switch($showType)
                @case('members')
                {{ __('leaderboard.evolution_membre_pointages') }}
                @break
                @case('improvements')
                {{ __('leaderboard.evolution_ameliorations') }}
                @break
                @case('clans')
                {{ __('leaderboard.evolution_pointages_clan') }}
                @break
                @default
                {{ __('leaderboard.evolution_pointages_utilisateurs') }}
                @endswitch
                <!-- Affiche le nom du clan si on est dans un contexte de clan spécifique -->
                @if($selectedClanId && $selectedClanId != 'global')
                - {{ \App\Models\Clan::find($selectedClanId)->nom ?? 'Clan' }}
                @endif
            </h4>
            <!-- Bouton pour fermer le graphique et retourner au classement -->
            <button wire:click="closeGraph" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> {{ __('leaderboard.retour_au_classement') }}
            </button>
        </div>
        <!-- Corps du graphique -->
        <div class="card-body">
            <!-- Conteneur avec hauteur fixe pour le graphique -->
            <div style="height: 400px;">
                <!-- Section ignorée par Livewire pour éviter les rechargements inutiles -->
                <div wire:ignore x-data="{}" x-init="() => {
            // Affiche le type de graphique dans la console (pour débogage)
            console.log('Initialisation du graphique avec le type:', $refs.showType.value);
            
            // Récupération des données depuis les champs cachés
            const chartMonths = JSON.parse($refs.months.value);
            const showType = $refs.showType.value;
            
            // Variables pour stocker le titre et l'étiquette du graphique
            let chartTitle = '';
            let chartLabel = '';
            
            // Détermination des textes selon le type de graphique
            switch(showType) {
                case 'clans': 
                    chartTitle = '{{ __('leaderboard.evolution_pointages_clan') }}';
                    chartLabel = '{{ __('leaderboard.evolution_pointage') }}';
                    break;
                case 'members':
                    chartTitle = '{{ __('leaderboard.evolution_membre_pointages') }}';
                    chartLabel = '{{ __('leaderboard.evolution_pointage') }}';
                    break;
                case 'improvements':
                    chartTitle = '{{ __('leaderboard.evolution_ameliorations') }}';
                    chartLabel = '{{ __('leaderboard.evolution_pointage') }}';
                    break;
                default:
                    chartTitle = '{{ __('leaderboard.evolution_pointages_utilisateurs') }}';
                    chartLabel = '{{ __('leaderboard.evolution_pointage') }}';
            }
            
            // Configuration des données pour le graphique
            let chartData = {
                // Les mois comme étiquettes sur l'axe X
                labels: chartMonths,
                // Ensemble de données à afficher
                datasets: [{
                    label: chartLabel,
                    // Sélection des données selon le type de graphique (clans ou autres)
                    data: showType === 'clans' 
                        ? JSON.parse($refs.clanScores.value)
                        : JSON.parse($refs.userScores.value),
                    // Couleurs différentes selon le type de graphique
                    borderColor: showType === 'clans' ? '#4CAF50' : 
                                showType === 'improvements' ? '#FF9800' : '#2196F3',
                    backgroundColor: showType === 'clans' 
                        ? 'rgba(76, 175, 80, 0.1)' : 
                        showType === 'improvements' ? 'rgba(255, 152, 0, 0.1)' : 'rgba(33, 150, 243, 0.1)',
                    // Lissage de la ligne du graphique
                    tension: 0.4,
                    // Remplissage sous la ligne
                    fill: true
                }]
            };

            // Création du graphique avec Chart.js
            new Chart($refs.canvas.getContext('2d'), {
                // Type de graphique linéaire
                type: 'line',
                // Données préparées ci-dessus
                data: chartData,
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
                            // Commencer à zéro
                            beginAtZero: true,
                            // Titre de l'axe Y
                            title: { 
                                display: true, 
                                text: '{{ __('leaderboard.pointage') }}'
                            }
                        },
                        // Axe X (horizontal)
                        x: { 
                            // Titre de l'axe X
                            title: { 
                                display: true, 
                                text: '{{ __('leaderboard.mois') }}'
                            } 
                        }
                    },
                    // Configuration des plugins Chart.js
                    plugins: { 
                        // Position de la légende en haut
                        legend: { position: 'top' },
                        // Titre principal du graphique
                        title: {
                            display: true,
                            text: chartTitle
                        }
                    }
                }
            });
        }">
                    <!-- Élément canvas où le graphique sera dessiné -->
                    <canvas x-ref="canvas"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>