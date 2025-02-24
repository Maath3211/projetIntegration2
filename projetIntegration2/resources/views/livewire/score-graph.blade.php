<div>
    <!-- Hidden inputs with data -->
    <input type="hidden" x-ref="months" value="{{ json_encode($months) }}">
    <input type="hidden" x-ref="clanScores" value="{{ json_encode($clanScores) }}">
    <input type="hidden" x-ref="userScores" value="{{ json_encode($userScores) }}">
    <input type="hidden" x-ref="showType" value="{{ $showType }}">

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                @switch($showType)
                @case('members')
                Évolution des scores des membres
                @break
                @case('improvements')
                Progression des améliorations
                @break
                @case('clans')
                Évolution des scores des clans
                @break
                @default
                Évolution des scores des utilisateurs
                @endswitch
                @if($selectedClanId && $selectedClanId != 'global')
                - {{ \App\Models\Clan::find($selectedClanId)->nom ?? 'Clan' }}
                @endif
            </h4>
            <button wire:click="hideGraph" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Retour au classement
            </button>
        </div>
        <div class="card-body">
            <div style="height: 400px;">
                <div wire:ignore x-data="{}" x-init="() => {
            console.log('Chart init with type:', $refs.showType.value);
            const chartMonths = JSON.parse($refs.months.value);
            const showType = $refs.showType.value;
            
            let chartData = {
                labels: chartMonths,
                datasets: [{
                    label: showType === 'clans' ? 'Score des Clans' : 
                           showType === 'members' ? 'Score des Membres' :
                           showType === 'improvements' ? 'Améliorations' : 'Score des Utilisateurs',
                    data: showType === 'clans' 
                        ? JSON.parse($refs.clanScores.value)
                        : JSON.parse($refs.userScores.value),
                    borderColor: showType === 'clans' ? '#4CAF50' : 
                                showType === 'improvements' ? '#FF9800' : '#2196F3',
                    backgroundColor: showType === 'clans' 
                        ? 'rgba(76, 175, 80, 0.1)' : 
                        showType === 'improvements' ? 'rgba(255, 152, 0, 0.1)' : 'rgba(33, 150, 243, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            };

            new Chart($refs.canvas.getContext('2d'), {
                type: 'line',
                data: chartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { 
                            beginAtZero: true,
                            title: { display: true, text: 'Score' }
                        },
                        x: { 
                            title: { display: true, text: 'Mois' } 
                        }
                    },
                    plugins: { legend: { position: 'top' } }
                }
            });
        }">
                    <canvas x-ref="canvas"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>