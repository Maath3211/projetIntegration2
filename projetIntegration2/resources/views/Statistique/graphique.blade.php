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
        .back-button {
            background-color: #a9fe77;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
            margin-bottom: 10px;
            color: black;
        }
        h1 {
            margin-bottom: 10px;
        }
        .unit-toggle {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
        }
        .unit-btn {
            background-color: #a9fe77;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
            color: black;
        }
        .chart-container {
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
    </style>

<div id="main">
    <button class="back-button">retour</button>
    <h1 id="titre">Amélioration de votre poids</h1>
    <div class="unit-toggle">
        <button class="unit-btn">Lbs</button>
        <button class="unit-btn">Kg</button>
    </div>
    <div class="chart-container">
        <canvas id="weightChart"></canvas>
    </div>
    <script>
        const ctx = document.getElementById('weightChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['1', '2', '3', '4', '5', '6'],
                datasets: [{
                    label: 'Poids (Lbs)',
                    data: [152, 157, 145, 140, 143, 136],
                    borderColor: 'white',
                    borderWidth: 2,
                    pointBackgroundColor: 'white',
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
                            color: 'white'
                        },
                        ticks: { color: 'white' }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Poids (Lbs)',
                            color: 'white'
                        },
                        ticks: { color: 'white' }
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    </script>
    </div>

@endsection