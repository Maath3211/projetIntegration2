<div style="height: 100vh; overflow-y: auto; scrollbar-width: thin; scrollbar-color: transparent transparent;">
    <div id="topMembresContainer" style="scrollbar-width: thin; scrollbar-color: transparent transparent;">
        <!-- Leaderboard Header Box -->
        <div class="leaderboard-header p-3 mb-3 border rounded d-flex justify-content-between align-items-center">
            <div>
                <div class="d-flex align-items-center">
                    <img src="{{ asset('img/ui/trophy.png') }}" alt="Trophy" style="width:30px; height:30px;" class="mr-2">
                    <h2 class="mb-0" id="titreLeaderboard">Top 10 Meilleurs Membres</h2>
                </div>
                <p class="text-muted mb-0">Découvrez les membres les plus performants du groupe</p>
            </div>
            <div class="dropdown ml-2" wire:ignore>
                <button class="btn btn-secondary dropdown-toggle" type="button" id="exportDropdown" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <i class="fa-solid fa-share-from-square fa-2x"></i>
                </button>
                <div class="dropdown-menu" aria-labelledby="exportDropdown">
                    <a class="dropdown-item" href="{{ route('export.topMembres', ['clanId' => $selectedClanId]) }}">Exporter Liste CSV</a>
                    <a class="dropdown-item" href="#" id="exportTopMembresImage">Exporter Capture</a>
                </div>
            </div>
        </div>
        <div class="row mb-0">
            <!-- Column for positions 1 to 5 -->
            <div class="col-md-6">
                @foreach ($meilleursMembres->take(5) as $index => $membre)
                <div class="clan-row d-flex align-items-center justify-content-between mb-2 py-2 px-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <a href="" class="clan-link">
                            <div class="position mr-3">{{ $index + 1 }}</div>
                            <img src="{{asset($membre->user_image) }}" alt="User Image" class="rounded-circle" style="width:40px; height:40px;">
                            <span class="clan-nom ml-3">{{ $membre->user_prenom }} {{ $membre->user_nom }}</span>
                        </a>
                    </div>
                    <span class="score">{{ $membre->user_total_score }} points</span>
                </div>
                @endforeach
            </div>
            <!-- Column for positions 6 to 10 -->
            <div class="col-md-6">
                @foreach ($meilleursMembres->slice(5, 5) as $index => $membre)
                <div class="clan-row d-flex align-items-center justify-content-between mb-2 py-2 px-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <a href="" class="clan-link">
                            <div class="position mr-3">{{ $index + 1 }}</div>
                            <img src="{{ asset($membre->user_image) }}" alt="User Image" class="rounded-circle" style="width:40px; height:40px;">
                            <span class="clan-nom ml-3">{{ $membre->user_prenom }} {{ $membre->user_nom }}</span>
                        </a>
                    </div>
                    <span class="score">{{ $membre->user_total_score }} points</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div id="topAmeliorationContainer" style="scrollbar-width: thin; scrollbar-color: transparent transparent;">
        <div class="leaderboard-header p-3 mb-3 border rounded d-flex justify-content-between align-items-center">
            <div>
                <div class="d-flex align-items-center">
                    <img src="{{ asset('img/ui/trophy.png') }}" alt="Trophy" style="width:30px; height:30px;" class="mr-2">
                    <h2 class="mb-0" id="titreLeaderboard">Top 10 Meilleure Amélioration</h2>
                </div>
                <p class="text-muted mb-0">Découvrez les membres ayant la plus grande amélioration du groupe dans le dernier mois</p>
            </div>
            <div class="dropdown ml-2" wire:ignore>
                <button class="btn btn-secondary dropdown-toggle" type="button" id="exportDropdownUsers" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa-solid fa-share-from-square fa-2x"></i>
                </button>
                <div class="dropdown-menu" aria-labelledby="exportDropdown">
                    <a class="dropdown-item" href="{{ route('export.topAmelioration', ['clanId' => $selectedClanId]) }}">Exporter Liste CSV</a>
                    <a class="dropdown-item" href="#" id="exportTopAmeliorationImage">Exporter Capture</a>
                </div>
            </div>
        </div>
        <div class="row mb-0">
            <!-- Column for positions 1 to 5 -->
            <div class="col-md-6">
                @foreach ($topScoreImprovement->take(5) as $index => $user)
                <div class="clan-row d-flex align-items-center justify-content-between mb-2 py-2 px-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <a href="" class="clan-link">
                            <div class="position mr-3">{{ $index + 1 }}</div>
                            <img src="{{ asset($user->user_image) }}" alt="User Image" class="rounded-circle" style="width:40px; height:40px;">
                            <span class="clan-nom ml-3">{{ $user->user_prenom }} {{ $user->user_nom }}</span>
                        </a>
                    </div>
                    <span class="score">{{ $user->score_improvement }} points</span>
                </div>
                @endforeach
            </div>
            <!-- Column for positions 6 to 10 -->
            <div class="col-md-6">
                @foreach ($topScoreImprovement->slice(5, 5) as $index => $user)
                <div class="clan-row d-flex align-items-center justify-content-between mb-2 py-2 px-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <a href="" class="clan-link">
                            <div class="position mr-3">{{ $index + 1 }}</div>
                            <img src="{{ asset($user->user_image) }}" alt="User Image" class="rounded-circle" style="width:40px; height:40px;">
                            <span class="clan-nom ml-3">{{ $user->user_prenom }} {{ $user->user_nom }}</span>
                        </a>
                    </div>
                    <span class="score">{{ $user->score_improvement }} points</span>
                </div>
                @endforeach
            </div>
        </div>
        <!-- Add a hidden element that outputs the clan name based on the selectedClanId -->
        <div id="clanNameHolder" style="display: none;">
            {{ $selectedClanId === 'global' ? 'Global' : optional(App\Models\Clan::find($selectedClanId))->nom }}
        </div>
    </div>
    <style>
        #topMembresContainer::-webkit-scrollbar,
        #topAmeliorationContainer::-webkit-scrollbar {
            width: 8px;
        }

        #topMembresContainer::-webkit-scrollbar-thumb,
        #topAmeliorationContainer::-webkit-scrollbar-thumb {
            background-color: transparent;
        }

        #topMembresContainer:hover::-webkit-scrollbar-thumb,
        #topAmeliorationContainer:hover::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.5);
        }
    </style>
</div>
