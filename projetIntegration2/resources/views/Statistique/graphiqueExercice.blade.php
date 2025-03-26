@extends('layouts.app')
@section('contenu')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" style="text/css" href="\css\Statistique\graphiqueExerciceCss.css">

<div id="main">
    <a href="/stats"><button class="bouton">{{ __('stats.retourpage') }}</button></a>


    <h1 id="titre">{{ __('stats.semaine') }} {{$diffSemaines}}</h1>


    <h1 id="titre">{{ __('stats.amelioration_de') }} {{$exercice->nomStatistique}}</h1>
    <div class="uniteToggle">
        <button class="bouton" id="btnLbs">Lbs</button>
        <button class="bouton" id="btnKg">Kg</button>
    </div>
    <div class="form-container">
        <form action="{{ route('ajouter-score', [$exercice->id]) }}" method="POST">
            @csrf
            <label for="score">{{ __('stats.nouveau_score') }}</label>
            <input type="number" id="score" name="score" required>
            <button type="submit" class="bouton">{{ __('stats.ajouter_modifier') }}</button>
        </form>
    </div>

    <div class="graphiqueContainer">
        <canvas id="exerciceChart"></canvas>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('exerciceChart').getContext('2d');
            let isLbs = true;
            let chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($semaines),
                    datasets: [{
                        label: '{{ __("stats.score_lbs") }}',
                        data: @json($score),
                        borderColor: '#a9fe77',
                        borderWidth: 2,
                        pointBackgroundColor: '#e5e5e5',
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: '{{ __("stats.semaine") }}',
                                color: '#e5e5e5',
                                font: {
                                    size: 16
                                }
                            },
                            ticks: {
                                color: '#e5e5e5',
                                font: {
                                    size: 14
                                }
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: '{{ __("stats.score_lbs") }}',
                                color: '#e5e5e5',
                                font: {
                                    size: 16
                                }
                            },
                            ticks: {
                                color: '#e5e5e5',
                                font: {
                                    size: 14
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false,
                            labels: {
                                font: {
                                    size: 16,
                                    weights: 'bold'
                                },
                            }
                        }
                    }
                }
            });
            document.getElementById('btnLbs').addEventListener('click', function() {
                if (!isLbs) {
                    convertirLbs();
                    isLbs = true;
                    chart.options.scales.y.title.text = '{{ __("stats.score_lbs") }}';
                    chart.data.datasets[0].label = '{{ __("stats.score_lbs") }}';
                    chart.update();
                }
            });

            document.getElementById('btnKg').addEventListener('click', function() {
                if (isLbs) {
                    convertirKg();
                    isLbs = false;
                    chart.options.scales.y.title.text = '{{ __("stats.score_kg") }}';
                    chart.data.datasets[0].label = '{{ __("stats.score_kg") }}';
                    chart.update();
                }
            });

            function convertirLbs() {
                chart.data.datasets[0].data = chart.data.datasets[0].data.map(kg => kg * 2.20462);
            }

            function convertirKg() {
                chart.data.datasets[0].data = chart.data.datasets[0].data.map(lbs => lbs / 2.20462);
            }
        });
    </script>
</div>

@endsection