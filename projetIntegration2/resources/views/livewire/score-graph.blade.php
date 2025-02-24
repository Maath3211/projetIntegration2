<div>
    <input type="hidden" id="chart-months" value="{{ json_encode($months) }}">
    <input type="hidden" id="chart-clan-scores" value="{{ json_encode($clanScores) }}">
    <input type="hidden" id="chart-user-scores" value="{{ json_encode($userScores) }}">
    
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Évolution des scores sur 6 mois</h4>
            <a href="{{ route('scores.meilleursGroupes') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour au classement
            </a>
        </div>
        <div class="card-body">
            <div style="height: 400px;">
                <div wire:ignore x-data="{}" x-init="() => {
                    console.log('Alpine init starting');
                    const chartMonths = JSON.parse($refs.months.value);
                    const chartClanScores = JSON.parse($refs.clanScores.value);
                    const chartUserScores = JSON.parse($refs.userScores.value);
                    
                    console.log('Data loaded:', { chartMonths, chartClanScores, chartUserScores });
                    
                    const ctx = $refs.canvas.getContext('2d');
                    
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: chartMonths,
                            datasets: [
                                {
                                    label: 'Score des Clans',
                                    data: chartClanScores,
                                    borderColor: '#4CAF50',
                                    backgroundColor: 'rgba(76, 175, 80, 0.1)',
                                    tension: 0.4,
                                    fill: true
                                },
                                {
                                    label: 'Score des Utilisateurs',
                                    data: chartUserScores,
                                    borderColor: '#2196F3',
                                    backgroundColor: 'rgba(33, 150, 243, 0.1)',
                                    tension: 0.4,
                                    fill: true
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: { 
                                    beginAtZero: true,
                                    title: { display: true, text: 'Score' }
                                },
                                x: { title: { display: true, text: 'Mois' } }
                            },
                            plugins: { legend: { position: 'top' } }
                        }
                    });
                    console.log('Chart initialization complete');
                }">
                    <input type="hidden" x-ref="months" value="{{ json_encode($months) }}">
                    <input type="hidden" x-ref="clanScores" value="{{ json_encode($clanScores) }}">
                    <input type="hidden" x-ref="userScores" value="{{ json_encode($userScores) }}">
                    <canvas x-ref="canvas"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Remove duplicate Chart.js include if your layout already provides it -->
<!-- <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script> -->

<script>
    // Global variable to store the chart instance
    let scoreChartInstance = null;

    function initChart() {
        console.log("initChart called"); 
        const chartMonths = JSON.parse(document.getElementById('chart-months').value);
        const chartClanScores = JSON.parse(document.getElementById('chart-clan-scores').value);
        const chartUserScores = JSON.parse(document.getElementById('chart-user-scores').value);
        
        const canvas = document.getElementById('scoreChart');
        if (!canvas) {
            console.error("Canvas element not found");
            return;
        }
        const ctx = canvas.getContext('2d');
        
        // Destroy previous chart instance, if any, before creating a new one
        if (scoreChartInstance) {
            scoreChartInstance.destroy();
            console.log("Previous chart destroyed");
        }
        
        // Optional small delay to ensure rendering is complete
        setTimeout(() => {
            scoreChartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartMonths,
                    datasets: [
                        {
                            label: 'Score des Clans',
                            data: chartClanScores,
                            borderColor: '#4CAF50',
                            backgroundColor: 'rgba(76, 175, 80, 0.1)',
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'Score des Utilisateurs',
                            data: chartUserScores,
                            borderColor: '#2196F3',
                            backgroundColor: 'rgba(33, 150, 243, 0.1)',
                            tension: 0.4,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { 
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Score'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Mois'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    }
                }
            });
            console.log("Chart initialized successfully");
        }, 100);
    }
    
    document.addEventListener('livewire:load', initChart);
    document.addEventListener('livewire:update', initChart);
</script>