<div style="height: 100vh; overflow-y: auto; scrollbar-width: thin; scrollbar-color: transparent transparent;">
    @if(!$showingGraph)
    <!-- Leaderboard content (unchanged) -->
    <div id="topClansContainer" style="scrollbar-width: thin; scrollbar-color: transparent transparent;">
        <!-- Leaderboard Header Box -->
        <div class="leaderboard-header p-3 mb-3 border rounded d-flex justify-content-between align-items-center">
            <div>
                <div class="d-flex align-items-center">
                    <img src="{{ asset('img/ui/trophy.png') }}" alt="Trophy" style="width:30px; height:30px;" class="mr-2">
                    <h2 class="mb-0" id="titreLeaderboard">Top 10 Meilleurs Groupes</h2>
                </div>
                <p class="text-muted mb-0">Découvrez les clans les plus performants et inspirants du moment</p>
            </div>
            <div class="dropdown ml-2" wire:ignore>
                <button class="btn btn-secondary dropdown-toggle" type="button" id="exportDropdown" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <i class="fa-solid fa-share-from-square fa-2x"></i>
                </button>
                <div class="dropdown-menu" aria-labelledby="exportDropdown">
                    <a class="dropdown-item" href="{{ route('export.topClans') }}">Exporter Liste CSV</a>
                    <a class="dropdown-item" href="#" id="exportClansImageBtn">Exporter Capture</a>
                </div>
            </div>
        </div>
        <div class="row mb-0">
            <div class="col-md-6">
                @foreach ($topClans->take(5) as $index => $clan)
                <div class="clan-row d-flex align-items-center justify-content-between mb-2 py-2 px-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('clan.montrer', ['id' => $clan->clan_id])}}" class="clan-link {{ request()->routeIs('clan.montrer') && request('id') == $clan->clan_id ? 'active' : '' }}">
                            <div class="position mr-3">{{ $index + 1 }}</div>
                            <img src="{{ asset('img/clans/' . $clan->clan_image) }}" alt="Clan Image" class="rounded-circle" style="width:40px; height:40px;">
                            <span class="clan-nom ml-3">{{ $clan->clan_nom }}</span>
                        </a>
                    </div>
                    <span class="score">{{ $clan->clan_total_score }} points</span>
                </div>
                @endforeach
            </div>
            <!-- Column for positions 6 to 10 -->
            <div class="col-md-6">
                @foreach ($topClans->slice(5, 5) as $index => $clan)
                <div class="clan-row d-flex align-items-center justify-content-between mb-2 py-2 px-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('clan.montrer', ['id' => $clan->clan_id])}}" class="clan-link {{ request()->routeIs('clan.montrer') && request('id') == $clan->clan_id ? 'active' : '' }}">
                            <div class="position mr-3">{{ $index + 1 }}</div>
                            <img src="{{ asset('img/clans/' . $clan->clan_image) }}" alt="Clan Image" class="rounded-circle" style="width:40px; height:40px;">
                            <span class="clan-nom ml-3">{{ $clan->clan_nom }}</span>
                        </a>
                    </div>
                    <span class="score">{{ $clan->clan_total_score }} points</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div id="topUsersContainer" style="scrollbar-width: thin; scrollbar-color: transparent transparent;">
        <div class="leaderboard-header p-3 mb-3 border rounded d-flex justify-content-between align-items-center">
            <div>
                <div class="d-flex align-items-center">
                    <img src="{{ asset('img/ui/trophy.png') }}" alt="Trophy" style="width:30px; height:30px;" class="mr-2">
                    <h2 class="mb-0" id="titreLeaderboard">Top 10 Meilleurs Utilisateurs</h2>
                </div>
                <p class="text-muted mb-0">Découvrez les utilisateurs les plus performants et inspirants du moment</p>
            </div>
            <div class="d-flex align-items-center">
                <a href="{{ route('scores.view-graph') }}" class="btn btn-primary mr-2">
                    <i class="fa-solid fa-chart-line"></i> Voir Graphique
                </a>
                <div class="dropdown ml-2" wire:ignore>
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="exportDropdownUsers" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa-solid fa-share-from-square fa-2x"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="exportDropdownUsers">
                        <a class="dropdown-item" href="{{ route('export.topUsers') }}">Exporter Liste CSV</a>
                        <a class="dropdown-item" href="#" id="exportUsersImageBtn">Exporter Capture</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-0">
            <!-- Column for positions 1 to 5 -->
            <div class="col-md-6">
                @foreach ($topUsers->take(5) as $index => $user)
                <div class="clan-row d-flex align-items-center justify-content-between mb-2 py-2 px-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <a href="" class="clan-link">
                            <div class="position mr-3">{{ $index + 1 }}</div>
                            <img src="{{ asset($user->imageProfil) }}" alt="User Image" class="rounded-circle" style="width:40px; height:40px;">
                            <span class="clan-nom ml-3">{{ $user->prenom }} {{ $user->nom }}</span>
                        </a>
                    </div>
                    <span class="score">{{ $user->total_score }} points</span>
                </div>
                @endforeach
            </div>
            <!-- Column for positions 6 to 10 -->
            <div class="col-md-6">
                @foreach ($topUsers->slice(5, 5) as $index => $user)
                <div class="clan-row d-flex align-items-center justify-content-between mb-2 py-2 px-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <a href="" class="clan-link">
                            <div class="position mr-3">{{ $index + 1 }}</div>
                            <img src="{{ asset($user->imageProfil) }}" alt="User Image" class="rounded-circle" style="width:40px; height:40px;">
                            <span class="clan-nom ml-3">{{ $user->prenom }} {{ $user->nom }}</span>
                        </a>
                    </div>
                    <span class="score">{{ $user->total_score }} points</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @else
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <button class="btn btn-secondary mb-3" wire:click="showLeaderboard">
                    <i class="fas fa-arrow-left"></i> Retour au classement
                </button>
                <!-- Load ScoreGraph component and pass data properly -->
                <div id="scoreGraphContainer" wire:key="graph-{{ now() }}">
                    @livewire('score-graph')
                </div>
            </div>
        </div>
    </div>
    @endif
    <style>
        #topClansContainer::-webkit-scrollbar,
        #topUsersContainer::-webkit-scrollbar {
            width: 8px;
        }

        #topClansContainer::-webkit-scrollbar-thumb,
        #topUsersContainer::-webkit-scrollbar-thumb {
            background-color: transparent;
        }

        #topClansContainer:hover::-webkit-scrollbar-thumb,
        #topUsersContainer:hover::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.5);
        }
    </style>
</div>

<!-- Add Chart.js script if not already present in the layout -->
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
@endpush