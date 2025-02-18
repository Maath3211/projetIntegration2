@extends('Layouts.app')
@section('contenu')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
        #body {
            background-color: #2c2c2c;
            color: black;
            font-family: Arial, sans-serif;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .header {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }
    
        h1 {
            margin-bottom: 10px;
        }
        .uniteToggle {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
        }
    
        .graphiqueContainer {
            width: 90%;
            max-width: 100%;
            height: 500px;
            align-items: center;
            justify-content: center;
        }
        #titre{
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-size: 32px;
        }
        .form-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
    background-color: #3a3a3a;
    padding: 15px;
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
}

label {
    font-size: 18px;
    color: #a9fe77;
    margin-bottom: 5px;
}

input[type="number"] {
    padding: 8px;
    width: 100px;
    font-size: 18px;
    border: 2px solid #a9fe77;
    border-radius: 5px;
    text-align: center;
    background-color: #222;
    color: #fff;
    outline: none;
}
    </style>

<div id="main">
    <a href="/stats"><button class="bouton">retour</button></a>

  
        <h1 id="titre">Vous êtes à la semaine {{$diffWeeks}}</h1>
   

    <h1 id="titre">Amélioration de votre poids</h1>
    <div class="uniteToggle">
        <button class="bouton" id="btnLbs">Lbs</button>
        <button class="bouton" id="btnKg">Kg</button>
    </div>
    <div class="form-container">
    <form action="{{ route('ajouter-poids') }}" method="POST">
        @csrf
        <label for="poids">Poids :</label>
        <input type="number" id="poids" name="poids" required>
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
                    label: 'Poids (Lbs)',
                    data: @json($poids),
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
                            text: 'Poids (Lbs)',
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
                chart.options.scales.y.title.text = 'Poids (Lbs)';
                chart.update();
            }
        });

        document.getElementById('btnKg').addEventListener('click', function () {
            if (isLbs) {
                convertToKg();
                isLbs = false;
                chart.options.scales.y.title.text = 'Poids (Kg)';
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


