<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chart Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <style>
        body { padding: 20px; }
        .card { box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
    </style>
    @livewireStyles
</head>
<body>
    <div class="container">
        <h1>Score Graph</h1>
        @livewire('score-graph')
    </div>
    @livewireScripts
    <script>
        // Log that we're starting
        console.log('Chart test script starting');
        
        // Initialize when document is ready
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, initializing chart');
            
            // Sample data
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
            const clanScores = [1200, 1350, 1500, 1450, 1600, 1750];
            const userScores = [800, 950, 1100, 1250, 1300, 1400];
            
            // Get canvas
            const canvas = document.getElementById('testChart');
            if (!canvas) {
                console.error('Canvas element not found');
                return;
            }
            
            const ctx = canvas.getContext('2d');
            if (!ctx) {
                console.error('Canvas context not found');
                return;
            }
            
            // Create chart
            try {
                console.log('Creating chart');
                const chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: months,
                        datasets: [
                            {
                                label: 'Score des Clans',
                                data: clanScores,
                                borderColor: '#4CAF50',
                                backgroundColor: 'rgba(76, 175, 80, 0.1)',
                                tension: 0.4,
                                fill: true
                            },
                            {
                                label: 'Score des Utilisateurs',
                                data: userScores,
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
                console.log('Chart created successfully');
            } catch (error) {
                console.error('Error creating chart:', error);
            }
        });
    </script>
</body>
</html>
