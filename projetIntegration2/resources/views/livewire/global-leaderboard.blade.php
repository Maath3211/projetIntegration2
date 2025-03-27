<div wire:init="loadData" style="height: 100vh; overflow-y: auto; scrollbar-width: thin; scrollbar-color: transparent transparent;">
    <!-- Loading indicator while component is initializing -->
    @if(!isset($dataLoaded))
    <div class="d-flex justify-content-center p-5">
        <div class="spinner-border text-success" role="status">
            <span class="sr-only">Chargement...</span>
        </div>
    </div>
    @else
    @endif
    @if(!$showingGraph)
    <!-- Contenu du tableau de classement -->
    <div id="topClansContainer" style="scrollbar-width: thin; scrollbar-color: transparent transparent;">
        <!-- En-tête du classement des clans avec titre et options d'exportation -->
        <div class="leaderboard-header p-3 mb-3 border rounded d-flex justify-content-between align-items-center">
            <div>
                <div class="d-flex align-items-center">
                    <!-- Icône de trophée à côté du titre -->
                    <img src="{{ asset('img/ui/trophy.png') }}" alt="Trophée" style="width:30px; height:30px;" class="mr-2">
                    <h2 class="mb-0" id="titreLeaderboard">{{ __('leaderboard.top_10_clans') }}</h2>
                </div>
                <!-- Description secondaire pour le classement des clans -->
                <p class="text-muted mb-0">{{ __('leaderboard.decouvrir_clans') }}</p>
            </div>
            <div class="d-flex align-items-center">
                <!-- Bouton pour basculer vers la vue graphique des clans -->
                <button class="btn btn-graphique mr-2" wire:click="showClansGraph">
                    <i class="fa-solid fa-chart-line"></i> {{ __('leaderboard.graphique_clans') }}
                </button>
                <!-- Menu déroulant pour les options d'exportation -->
                <div class="dropdown ml-2" wire:ignore>
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="exportDropdown" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <i class="fa-solid fa-share-from-square fa-2x"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="exportDropdown">
                        <!-- Option pour exporter la liste en fichier CSV -->
                        <a class="dropdown-item" href="{{ route('export.topClans') }}">{{ __('leaderboard.exporter_liste') }}</a>
                        <!-- Option pour exporter le tableau en image -->
                        <a class="dropdown-item" href="#" id="exportClansImageBtn">{{ __('leaderboard.exporter_capture') }}</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenu du classement des clans en deux colonnes -->
        <div class="row mb-0">
            <!-- Première colonne : clans classés de 1 à 5 -->
            <div class="col-md-6">
                @foreach ($topClans->take(5) as $index => $clan)
                <div class="clan-row d-flex align-items-center justify-content-between mb-2 py-2 px-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <!-- Lien vers la page du clan avec indication si c'est la page active -->
                        <a href="{{ route('clan.montrer', ['id' => $clan->clan_id])}}" class="clan-link {{ request()->routeIs('clan.montrer') && request('id') == $clan->clan_id ? 'active' : '' }}">
                            <!-- Position dans le classement (de 1 à 5) -->
                            <div class="position mr-3">{{ $index + 1 }}</div>
                            <!-- Image du clan en format rond -->
                            <img src="{{$clan->clan_image}}" alt="Image du Clan" class="rounded-circle" style="width:40px; height:40px;">
                            <!-- Nom du clan -->
                            <span class="clan-nom ml-3">{{ $clan->clan_nom }}</span>
                        </a>
                    </div>
                    <!-- Score total du clan avec traduction du mot "points" -->
                    <span class="score">{{ $clan->clan_total_score }} {{ __('leaderboard.points') }}</span>
                </div>
                @endforeach
            </div>
            <!-- Deuxième colonne : clans classés de 6 à 10 -->
            <div class="col-md-6">
                @foreach ($topClans->slice(5, 5) as $index => $clan)
                <div class="clan-row d-flex align-items-center justify-content-between mb-2 py-2 px-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <!-- Lien vers la page du clan avec indication si c'est la page active -->
                        <a href="{{ route('clan.montrer', ['id' => $clan->clan_id])}}" class="clan-link {{ request()->routeIs('clan.montrer') && request('id') == $clan->clan_id ? 'active' : '' }}">
                            <!-- Position dans le classement (de 6 à 10) -->
                            <div class="position mr-3">{{ $index + 6 }}</div>
                            <!-- Image du clan en format rond -->
                            <img src="{{$clan->clan_image}}" alt="Image du Clan" class="rounded-circle" style="width:40px; height:40px;">
                            <!-- Nom du clan -->
                            <span class="clan-nom ml-3">{{ $clan->clan_nom }}</span>
                        </a>
                    </div>
                    <!-- Score total du clan -->
                    <span class="score">{{ $clan->clan_total_score }} {{ __('leaderboard.points') }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Conteneur pour le classement des utilisateurs individuels -->
    <div id="topUsersContainer" style="scrollbar-width: thin; scrollbar-color: transparent transparent;">
        <!-- En-tête du classement des utilisateurs avec titre et options -->
        <div class="leaderboard-header p-3 mb-3 border rounded d-flex justify-content-between align-items-center">
            <div>
                <div class="d-flex align-items-center">
                    <!-- Icône de trophée à côté du titre -->
                    <img src="{{ asset('img/ui/trophy.png') }}" alt="Trophée" style="width:30px; height:30px;" class="mr-2">
                    <h2 class="mb-0" id="titreLeaderboard">{{ __('leaderboard.top_10_usagers') }}</h2>
                </div>
                <!-- Description secondaire pour le classement des utilisateurs -->
                <p class="text-muted mb-0">{{ __('leaderboard.decouvrir_usagers') }}</p>
            </div>
            <div class="d-flex align-items-center">
                <!-- Bouton pour basculer vers la vue graphique des utilisateurs -->
                <button class="btn btn-graphique mr-2" wire:click="showUsersGraph">
                    <i class="fa-solid fa-chart-line"></i> {{ __('leaderboard.graphique_usagers') }}
                </button>
                <!-- Menu déroulant pour les options d'exportation des utilisateurs -->
                <div class="dropdown ml-2" wire:ignore>
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="exportDropdownUsers" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <i class="fa-solid fa-share-from-square fa-2x"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="exportDropdownUsers">
                        <!-- Option pour exporter la liste des utilisateurs en fichier CSV -->
                        <a class="dropdown-item" href="{{ route('export.topUsers') }}">{{ __('leaderboard.exporter_liste') }}</a>
                        <!-- Option pour exporter le tableau en image -->
                        <a class="dropdown-item" href="#" id="exportUsersImageBtn">{{ __('leaderboard.exporter_capture') }}</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenu du classement des utilisateurs en deux colonnes -->
        <div class="row mb-0">
            <!-- Première colonne : utilisateurs classés de 1 à 5 -->
            <div class="col-md-6">
                @foreach ($topUsers->take(5) as $index => $user)
                <div class="clan-row d-flex align-items-center justify-content-between mb-2 py-2 px-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <!-- IMPORTANT: Utilisation de l'opérateur de fusion null pour éviter les erreurs d'email -->
                        <a class="clan-link" href="{{ route('profil.profilPublic', ['email' => $user->email ?? $user->user_email ?? '' ]) }}">
                            <!-- Position dans le classement (de 1 à 5) -->
                            <div class="position mr-3">{{ $index + 1 }}</div>
                            <!-- Image de profil de l'utilisateur -->
                            <img src="{{ asset($user->imageProfil) }}" alt="Image Utilisateur" class="rounded-circle" style="width:40px; height:40px;">
                            <!-- Prénom et nom de l'utilisateur -->
                            <span class="clan-nom ml-3">{{ $user->prenom }} {{ $user->nom }}</span>
                        </a>
                    </div>
                    <!-- Score total de l'utilisateur -->
                    <span class="score">{{ $user->total_score }} {{ __('leaderboard.points') }}</span>
                </div>
                @endforeach
            </div>
            <!-- Deuxième colonne : utilisateurs classés de 6 à 10 -->
            <div class="col-md-6">
                @foreach ($topUsers->slice(5, 5) as $index => $user)
                <div class="clan-row d-flex align-items-center justify-content-between mb-2 py-2 px-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <!-- IMPORTANT: Utilisation de l'opérateur de fusion null pour éviter les erreurs d'email -->
                        <a class="clan-link" href="{{ route('profil.profilPublic', ['email' => $user->email ?? $user->user_email ?? '' ]) }}">
                            <!-- Position dans le classement (de 6 à 10) -->
                            <div class="position mr-3">{{ $index + 6 }}</div>
                            <!-- Image de profil de l'utilisateur -->
                            <img src="{{ asset($user->imageProfil) }}" alt="Image Utilisateur" class="rounded-circle" style="width:40px; height:40px;">
                            <!-- Prénom et nom de l'utilisateur -->
                            <span class="clan-nom ml-3">{{ $user->prenom }} {{ $user->nom }}</span>
                        </a>
                    </div>
                    <!-- Score total de l'utilisateur -->
                    <span class="score">{{ $user->total_score }} {{ __('leaderboard.points') }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @else
    <!-- Affichage du contenu graphique lorsque showingGraph est vrai -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- Composant de graphique avec une clé unique pour éviter les problèmes de rendu -->
                <div wire:key="score-graph-{{ $chartType }}">
                    <!-- Chargement du composant score-graph avec le type de graphique approprié -->
                    @livewire('score-graph', ['showType' => $chartType])
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Styles CSS pour personnaliser l'apparence des barres de défilement -->
<style>
    /* Définit la largeur des barres de défilement pour les conteneurs */
    #topClansContainer::-webkit-scrollbar,
    #topUsersContainer::-webkit-scrollbar {
        width: 8px;
    }

    /* Masque la barre de défilement par défaut pour une interface plus propre */
    #topClansContainer::-webkit-scrollbar-thumb,
    #topUsersContainer::-webkit-scrollbar-thumb {
        background-color: transparent;
    }

    /* Affiche la barre de défilement au survol pour une meilleure expérience utilisateur */
    #topClansContainer:hover::-webkit-scrollbar-thumb,
    #topUsersContainer:hover::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 0.5);
    }
</style>