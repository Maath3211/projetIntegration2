<!-- resources/views/livewire/score-graph.blade.php -->
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-4">Évolution des scores sur 6 mois</h5>
            <div style="height: 400px;"> <!-- Fixed height container -->
                <canvas id="scoreChart" wire:ignore></canvas>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('livewire:load', function() {
            const ctx = document.getElementById('scoreChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: @json($graphData),
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
        });
    </script>
    @endpush
</div>