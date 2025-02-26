<div style="height: 100vh; overflow-y: auto; scrollbar-width: thin; scrollbar-color: transparent transparent;">
    @if(!$showingGraph)
    <!-- Leaderboard Content -->
    <div id="topClansContainer" style="scrollbar-width: thin; scrollbar-color: transparent transparent;">
        <div class="leaderboard-header p-3 mb-3 border rounded d-flex justify-content-between align-items-center">
            <div>
                <div class="d-flex align-items-center">
                    <img src="{{ asset('img/ui/trophy.png') }}" alt="Trophy" style="width:30px; height:30px;" class="mr-2">
                    <h2 class="mb-0" id="titreLeaderboard">Top 10 Meilleurs Groupes</h2>
                </div>
                <p class="text-muted mb-0">Découvrez les clans les plus performants et inspirants du moment</p>
            </div>
            <div class="d-flex align-items-center">
                <button class="btn btn-graphique mr-2" wire:click="showClansGraph">
                    <i class="fa-solid fa-chart-line"></i> Graphique Clans
                </button>
                <div class="dropdown ml-2" wire:ignore>
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="exportDropdown" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <i class="fa-solid fa-share-from-square fa-2x"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="exportDropdown">
                        <a class="dropdown-item" href="{{ route('export.topClans') }}">Exporter Liste</a>
                        <a class="dropdown-item" href="#" id="exportClansImageBtn">Exporter Capture</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Clans Content -->
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
            <div class="col-md-6">
                @foreach ($topClans->slice(5, 5) as $index => $clan)
                <div class="clan-row d-flex align-items-center justify-content-between mb-2 py-2 px-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('clan.montrer', ['id' => $clan->clan_id])}}" class="clan-link {{ request()->routeIs('clan.montrer') && request('id') == $clan->clan_id ? 'active' : '' }}">
                            <div class="position mr-3">{{ $index + 6 }}</div>
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
                <button class="btn btn-graphique mr-2" wire:click="showUsersGraph">
                    <i class="fa-solid fa-chart-line"></i> Graphique Utilisateurs
                </button>
                <div class="dropdown ml-2" wire:ignore>
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="exportDropdownUsers" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <i class="fa-solid fa-share-from-square fa-2x"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="exportDropdownUsers">
                        <a class="dropdown-item" href="{{ route('export.topUsers') }}">Exporter Liste</a>
                        <a class="dropdown-item" href="#" id="exportUsersImageBtn">Exporter Capture</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Content -->
        <div class="row mb-0">
            <div class="col-md-6">
                @foreach ($topUsers->take(5) as $index => $user)
                <div class="clan-row d-flex align-items-center justify-content-between mb-2 py-2 px-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <a href="#" class="clan-link">
                            <div class="position mr-3">{{ $index + 1 }}</div>
                            <img src="{{ asset($user->imageProfil) }}" alt="User Image" class="rounded-circle" style="width:40px; height:40px;">
                            <span class="clan-nom ml-3">{{ $user->prenom }} {{ $user->nom }}</span>
                        </a>
                    </div>
                    <span class="score">{{ $user->total_score }} points</span>
                </div>
                @endforeach
            </div>
            <div class="col-md-6">
                @foreach ($topUsers->slice(5, 5) as $index => $user)
                <div class="clan-row d-flex align-items-center justify-content-between mb-2 py-2 px-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <a href="#" class="clan-link">
                            <div class="position mr-3">{{ $index + 6 }}</div>
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
    <!-- Graph Content -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div wire:key="score-graph-{{ $chartType }}">
                    @livewire('score-graph', ['showType' => $chartType])
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

