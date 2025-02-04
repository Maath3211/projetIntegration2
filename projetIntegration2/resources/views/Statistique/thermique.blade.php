@extends('Layouts.app')
@section('contenu')
    <title>Heatmap d'Activité</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #2c2c2c;
            color: white;
            text-align: center;
        }
        .heatmap-container {
            max-width: 800px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            color: black;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .title {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .heatmap {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 8px;
            max-width: 90%;
        }
        .day {
            width: 50px;
            height: 50px;
            border-radius: 3px;
            background-color: transparent;
            border: 1px solid #ccc;
            transition: background-color 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            cursor: pointer;
        }
        .day.active {
            background-color: #a9fe77;
           
        }
        .back-button {
            background-color: #a9fe77;
            color: black;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s, transform 0.2s;
        }
        .titre, .month-title {
            text-align: center;
            font-size: 32px;
        }
        @media (max-width: 600px) {
            .heatmap {
                grid-template-columns: repeat(7, 1fr);
                gap: 4px;
            }
            .day {
                width: 30px;
                height: 30px;
                font-size: 12px;
            }
            .month-title {
                font-size: 14px;
            }
            .back-button {
                font-size: 12px;
                padding: 6px 10px;
            }
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".day").forEach(day => {
                day.addEventListener("click", function() {
                    this.classList.toggle("active");
                });
            });
        });
    </script>
</head>
<body>
    <button class="back-button">Retour</button>
    <button class="back-button">Sauvegarder</button>
    <h1 class="titre">Calendrier d'Activité</h1>
    <div class="heatmap-container">
        <div class="month-title">Janvier</div>
        <div class="heatmap">
            @for ($i = 1; $i <= 31; $i++)
                <div class="day">{{ $i }}</div>
            @endfor
        </div>
        <div class="month-title">Février</div>
        <div class="heatmap">
            @for ($i = 1; $i <= 28; $i++)
                <div class="day">{{ $i }}</div>
            @endfor
        </div>
    </div>
</body>
@endsection
