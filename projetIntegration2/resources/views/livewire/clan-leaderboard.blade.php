<div wire:init="loadData" style="height: 100vh; overflow-y: auto; scrollbar-width: thin; scrollbar-color: transparent transparent;">
    <!-- Loading indicator while component is initializing -->
    @if(!isset($dataLoaded))
    <div class="d-flex justify-content-center p-5">
        <div class="spinner-border text-success" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    @else
    @if(!$showingGraph)
    <!-- Début de la section d'affichage du classement des membres -->
    <div id="topMembresContainer" style="scrollbar-width: thin; scrollbar-color: transparent transparent;">
        <!-- Entête du classement avec titre et options d'exportation -->
        <div class="leaderboard-header p-3 mb-3 border rounded d-flex justify-content-between align-items-center">
            <div>
                <div class="d-flex align-items-center">
                    <!-- Icône de trophée à côté du titre -->
                    <img src="{{ asset('img/ui/trophy.png') }}" alt="Trophée" style="width:30px; height:30px;" class="mr-2">
                    <h2 class="mb-0" id="titreLeaderboard">{{ __('leaderboard.top_10_membres') }}</h2>
                </div>
                <!-- Description du classement -->
                <p class="text-muted mb-0">{{ __('leaderboard.decouvrir_membres') }}</p>
            </div>
            <div class="d-flex align-items-center">
                <!-- Bouton pour afficher le graphique des meilleurs membres -->
                <button class="btn btn-graphique mr-2" wire:click="showMembersGraph">
                    <i class="fa-solid fa-chart-line"></i> {{ __('leaderboard.graphique_membres') }}
                </button>
                <!-- Menu déroulant pour les options d'exportation -->
                <div class="dropdown ml-2" wire:ignore>
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="exportDropdown" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <i class="fa-solid fa-share-from-square fa-2x"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="exportDropdown">
                        <!-- Option pour exporter la liste en fichier -->
                        <a class="dropdown-item" href="{{ route('export.topMembres', ['clanId' => $selectedClanId]) }}">{{ __('leaderboard.exporter_liste') }}</a>
                        <!-- Option pour exporter une capture d'écran -->
                        <a class="dropdown-item" href="#" id="exportTopMembresImage">{{ __('leaderboard.exporter_capture') }}</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Disposition en grille pour afficher les membres en deux colonnes -->
        <div class="row mb-0">
            <!-- Première colonne: membres classés de 1 à 5 -->
            <div class="col-md-6">
                @foreach ($meilleursMembres->take(5) as $index => $membre)
                <div class="clan-row d-flex align-items-center justify-content-between mb-2 py-2 px-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <!-- Lien vers le profil public du membre -->
                        <a class="clan-link" href="{{ route('profil.profilPublic', ['email' => $membre->email]) }}">
                            <!-- Numéro de position dans le classement -->
                            <div class="position mr-3">{{ $index + 1 }}</div>
                            <!-- Photo de profil du membre -->
                            <img src="{{ asset($membre->user_image) }}" alt="Image Utilisateur" class="rounded-circle" style="width:40px; height:40px;">
                            <!-- Nom et prénom du membre -->
                            <span class="clan-nom ml-3">{{ $membre->user_prenom }} {{ $membre->user_nom }}</span>
                        </a>
                    </div>
                    <!-- Score total du membre avec la traduction du mot "points" -->
                    <span class="score">{{ $membre->user_total_score }} {{ __('leaderboard.points') }}</span>
                </div>
                @endforeach
            </div>
            <!-- Deuxième colonne: membres classés de 6 à 10 -->
            <div class="col-md-6">
                @foreach ($meilleursMembres->slice(5, 5) as $index => $membre)
                <div class="clan-row d-flex align-items-center justify-content-between mb-2 py-2 px-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <!-- Lien vers le profil public du membre -->
                        <a class="clan-link" href="{{ route('profil.profilPublic', ['email' => $membre->email]) }}">
                            <!-- Position de 6 à 10 dans le classement -->
                            <div class="position mr-3">{{ $index + 6 }}</div>
                            <!-- Photo de profil -->
                            <img src="{{ asset($membre->user_image) }}" alt="Image Utilisateur" class="rounded-circle" style="width:40px; height:40px;">
                            <!-- Nom et prénom -->
                            <span class="clan-nom ml-3">{{ $membre->user_prenom }} {{ $membre->user_nom }}</span>
                        </a>
                    </div>
                    <!-- Score total -->
                    <span class="score">{{ $membre->user_total_score }} {{ __('leaderboard.points') }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <!-- Section pour les améliorations de score les plus importantes -->
    <div id="topAmeliorationContainer" style="scrollbar-width: thin; scrollbar-color: transparent transparent;">
        <!-- En-tête de la section d'amélioration -->
        <div class="leaderboard-header p-3 mb-3 border rounded d-flex justify-content-between align-items-center">
            <div>
                <div class="d-flex align-items-center">
                    <img src="{{ asset('img/ui/trophy.png') }}" alt="Trophée" style="width:30px; height:30px;" class="mr-2">
                    <h2 class="mb-0" id="titreLeaderboard">{{ __('leaderboard.top_10_amelioration') }}</h2>
                </div>
                <p class="text-muted mb-0">{{ __('leaderboard.decouvrir_amelioration') }}</p>
            </div>
            <div class="d-flex align-items-center">
                <!-- Bouton pour afficher le graphique d'amélioration -->
                <button class="btn btn-graphique mr-2" wire:click="showImprovementsGraph">
                    <i class="fa-solid fa-chart-line"></i> {{ __('leaderboard.graphique_amelioration') }}
                </button>
                <!-- Menu d'exportation pour les données d'amélioration -->
                <div class="dropdown ml-2" wire:ignore>
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="exportDropdownUsers" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa-solid fa-share-from-square fa-2x"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="exportDropdown">
                        <a class="dropdown-item" href="{{ route('export.topAmelioration', ['clanId' => $selectedClanId]) }}">{{ __('leaderboard.exporter_liste') }}</a>
                        <a class="dropdown-item" href="#" id="exportTopAmeliorationImage">{{ __('leaderboard.exporter_capture') }}</a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Disposition en grille pour les améliorations -->
        <div class="row mb-0">
            <!-- Colonne pour les positions 1 à 5 des améliorations -->
            <div class="col-md-6">
                @foreach ($topScoreImprovement->take(5) as $index => $user)
                <div class="clan-row d-flex align-items-center justify-content-between mb-2 py-2 px-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <a class="clan-link" href="{{ route('profil.profilPublic', ['email' => $user->email]) }}">
                            <div class="position mr-3">{{ $index + 1 }}</div>
                            <img src="{{ asset($user->user_image) }}" alt="Image Utilisateur" class="rounded-circle" style="width:40px; height:40px;">
                            <span class="clan-nom ml-3">{{ $user->user_prenom }} {{ $user->user_nom }}</span>
                        </a>
                    </div>
                    <!-- Points d'amélioration (différence entre les scores) -->
                    <span class="score">{{ $user->score_improvement }} {{ __('leaderboard.points') }}</span>
                </div>
                @endforeach
            </div>
            <!-- Colonne pour les positions 6 à 10 des améliorations -->
            <div class="col-md-6">
                @foreach ($topScoreImprovement->slice(5, 5) as $index => $user)
                <div class="clan-row d-flex align-items-center justify-content-between mb-2 py-2 px-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <a class="clan-link" href="{{ route('profil.profilPublic', ['email' => $user->email]) }}">
                            <div class="position mr-3">{{ $index + 6 }}</div>
                            <img src="{{ asset($user->user_image) }}" alt="Image Utilisateur" class="rounded-circle" style="width:40px; height:40px;">
                            <span class="clan-nom ml-3">{{ $user->user_prenom }} {{ $user->user_nom }}</span>
                        </a>
                    </div>
                    <span class="score">{{ $user->score_improvement }} {{ __('leaderboard.points') }}</span>
                </div>
                @endforeach
            </div>
        </div>
        <!-- Élément caché contenant le nom du clan pour utilisation par JavaScript -->
        <div id="clanNameHolder" style="display: none;">
            {{ $selectedClanId === 'global' ? 'Global' : optional($selectedClan)->nom }}
        </div>
    </div>
    @else
    <!-- Affichage du graphique lorsque showingGraph est true -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- Insertion du composant score-graph avec clé unique pour éviter les conflits de rendu -->
                <div wire:key="clan-score-graph-{{ $chartType }}-{{ $selectedClanId }}">
                    <!-- Chargement dynamique du composant de graphique avec les paramètres nécessaires -->
                    @livewire('score-graph', ['showType' => $chartType, 'selectedClanId' => $selectedClanId])
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Styles CSS pour personnaliser l'apparence des barres de défilement -->
    <style>
        /* Définit la largeur des barres de défilement */
        #topMembresContainer::-webkit-scrollbar,
        #topAmeliorationContainer::-webkit-scrollbar {
            width: 8px;
        }

        /* Masque la barre de défilement par défaut */
        #topMembresContainer::-webkit-scrollbar-thumb,
        #topAmeliorationContainer::-webkit-scrollbar-thumb {
            background-color: transparent;
        }

        /* Affiche la barre de défilement au survol pour une meilleure expérience utilisateur */
        #topMembresContainer:hover::-webkit-scrollbar-thumb,
        #topAmeliorationContainer:hover::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.5);
        }
    </style>
</div>

