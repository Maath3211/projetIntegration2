<!-- resources/views/livewire/score-graph.blade.php -->
<div class="card">
    <div class="card-body">
        <h5 class="card-title mb-4">Évolution des scores sur 6 mois</h5>
        <div style="height: 400px;"> <!-- Fixed height container -->
            <canvas id="scoreChart"></canvas>
        </div>
    </div>
</div>

<script>
    // Initialize chart when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        initChart();
    });
    
    function initChart() {
        const ctx = document.getElementById('scoreChart');
        
        // Chart.js configuration
        const config = {
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
        };
        
        // Create the chart
        new Chart(ctx, config);
    }
</script>