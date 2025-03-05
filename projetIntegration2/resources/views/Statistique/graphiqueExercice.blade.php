@extends('Layouts.app')
@section('contenu')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" style="text/css" href="\css\Statistique\graphiqueExerciceCss.css"> 

<div id="main">
    <a href="/stats"><button class="bouton">retour</button></a>

  
        <h1 id="titre">Vous êtes à la semaine {{$diffSemaines}}</h1>
   

    <h1 id="titre">Amélioration de votre {{$exercice->nomStatistique}}</h1>
    <div class="uniteToggle">
        <button class="bouton" id="btnLbs">Lbs</button>
        <button class="bouton" id="btnKg">Kg</button>
    </div>
    <div class="form-container">
    <form action="{{ route('ajouter-score', [$exercice->id]) }}" method="POST">
        @csrf
        <label for="score">Nouveau Score (Lbs) :</label>
        <input type="number" id="score" name="score" required>
        <button type="submit" class="bouton">Ajouter/Modifier</button>
    </form>
</div>
 
    <div class="graphiqueContainer">
    <canvas id="exerciceChart"></canvas>
    </div>
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('exerciceChart').getContext('2d');
        let isLbs = true; 
        let chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($semaines),
                datasets: [{
                    label: 'Score (Lbs)',
                    data: @json($score),
                    borderColor: '#a9fe77',
                    borderWidth: 2,
                    pointBackgroundColor: '#e5e5e5',
                    pointRadius: 6,
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
                            text: 'Semaines',
                            color: '#e5e5e5',
                            font: { size: 16 }
                        },
                        ticks: { 
                            color: '#e5e5e5',
                            font: { size: 14 }
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Score (Lbs)',
                            color: '#e5e5e5',
                            font: { size: 16 }
                        },
                        ticks: { 
                            color: '#e5e5e5',
                            font: { size: 14 }
                        }
                    }
                },
                plugins: {
                    legend: { 
                        display: false,
                        labels: {
                            font: { size: 16, weights: 'bold' },
                        }
                    }
                }
            }
        });
        document.getElementById('btnLbs').addEventListener('click', function () {
            if (!isLbs) {
                convertToLbs();
                isLbs = true;
                chart.options.scales.y.title.text = 'Score (Lbs)';
                chart.update();
            }
        });

        document.getElementById('btnKg').addEventListener('click', function () {
            if (isLbs) {
                convertToKg();
                isLbs = false;
                chart.options.scales.y.title.text = 'Score (Kg)';
                chart.update();
            }
        });

        function convertToLbs() {
            chart.data.datasets[0].data = chart.data.datasets[0].data.map(kg => kg * 2.20462);
        }

        function convertToKg() {
            chart.data.datasets[0].data = chart.data.datasets[0].data.map(lbs => lbs / 2.20462);
        }
      });
 
    </script>
</div>

@endsection


