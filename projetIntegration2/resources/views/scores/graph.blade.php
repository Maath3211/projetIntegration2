<!-- scores/graph.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('charts.evolution_point_titre') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <style>
        body {
            padding: 20px;
        }

        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">{{ __('charts.evolution_point_periode') }}</h4>
                        <a href="{{ route('scores.meilleursGroupes') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('charts.retour_classements') }}
                        </a>
                    </div>
                    <div class="card-body">
                        <div style="height: 400px;">
                            <canvas id="scoreChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Pass translations to JavaScript
        const translations = {
            clanScores: "{{ __('charts.pointages_clan') }}",
            userScores: "{{ __('charts.pointages_utilisateurs') }}",
            score: "{{ __('charts.score') }}",
            months: "{{ __('charts.months') }}"
        };

        // Initialize chart when page loads
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('scoreChart').getContext('2d');

            const data = {
                labels: @json($months),
                datasets: [{
                        label: translations.clanScores,
                        data: @json($clanScores),
                        borderColor: '#4CAF50',
                        backgroundColor: 'rgba(76, 175, 80, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: translations.userScores,
                        data: @json($userScores),
                        borderColor: '#2196F3',
                        backgroundColor: 'rgba(33, 150, 243, 0.1)',
                        tension: 0.4,
                        fill: true
                    }
                ]
            };

            const config = {
                type: 'line',
                data: data,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: translations.score
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: translations.months
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

            new Chart(ctx, config);
        });
    </script>
</body>

</html>