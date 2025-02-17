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
        input[type="number"] {
        color: black;
    }
    </style>

<div id="main">
    <a href="/stats"><button class="bouton">retour</button></a>

  
        <h1 id="titre">Vous êtes à la semaine {{$diffWeeks}}</h1>
   

    <h1 id="titre">Amélioration de votre poids</h1>
    <div class="uniteToggle">
        <button class="bouton">Lbs</button>
        <button class="bouton">Kg</button>
    </div>
    <form id="poidsForm">
        <label for="poids">Poids:</label>
        <input type="number" id="poids" name="poids" required>
        <button type="submit" class="bouton">Ajouter</button>
    </form>
 
    <div class="graphiqueContainer">
    <canvas id="exerciceChart"></canvas>
    </div>
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('exerciceChart').getContext('2d');

        new Chart(ctx, {
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
        document.getElementById('poidsForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const poids = document.getElementById('poids').value;
            const currentDate = new Date();
            const diffTime = Math.abs(currentDate - dateCreationCompte);
            const diffWeeks = Math.ceil(diffTime / (1000 * 60 * 60 * 24 * 7));

            fetch('{{ route('ajouter-poids') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    poids: poids,
                    semaine: diffWeeks
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const index = chart.data.labels.indexOf(diffWeeks);
                    if (index !== -1) {
                        chart.data.datasets[0].data[index] = poids;
                    } else {
                        chart.data.labels.push(diffWeeks);
                        chart.data.datasets[0].data.push(poids);
                    }
                    chart.update();
                }
            });
        });
    });
    </script>
</div>

@endsection