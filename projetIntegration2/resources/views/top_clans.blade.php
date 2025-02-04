<!-- resources/views/top_clans.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top 10 Meilleurs Groupes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            padding: 20px;
        }
        .column {
            width: 48%;
            padding: 10px;
        }
        .column h2 {
            text-align: center;
            font-size: 1.5em;
            margin-bottom: 20px;
        }
        .clan-row {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .clan-row img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 15px;
        }
        .clan-row .clan-info {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .clan-row .clan-info span {
            font-size: 1em;
        }
        .clan-row .position {
            font-weight: bold;
            font-size: 1.2em;
            margin-right: 15px;
        }
        .score {
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="column">
            <h2>Top 5 Meilleurs Groupes</h2>
            @foreach ($topClans->take(5) as $index => $clan)
                <div class="clan-row">
                    <div class="position">{{ $index + 1 }}</div>
                    <img src="{{ asset('images/clans/' . $clan->clan_image) }}" alt="Clan Image"> <!-- Image from Clan table -->
                    <div class="clan-info">
                        <span>{{ $clan->clan_nom }}</span> <!-- Clan name -->
                        <span class="score">{{ $clan->clan_total_score }} points</span> <!-- Clan total score -->
                    </div>
                </div>
            @endforeach
        </div>

        <div class="column">
            <h2>Top 6-10 Meilleurs Groupes</h2>
            @foreach ($topClans->slice(5, 5) as $index => $clan)
                <div class="clan-row">
                    <div class="position">{{ $index + 6 }}</div>
                    <img src="{{ asset('images/clans/' . $clan->clan_image) }}" alt="Clan Image"> <!-- Image from Clan table -->
                    <div class="clan-info">
                        <span>{{ $clan->clan_nom }}</span> <!-- Clan name -->
                        <span class="score">{{ $clan->clan_total_score }} points</span> <!-- Clan total score -->
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</body>
</html>
