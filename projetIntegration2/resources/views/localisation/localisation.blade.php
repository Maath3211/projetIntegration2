<!DOCTYPE html>
<html>

<head>
    <title>Carte des Gymnases</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <style>
        body {
            display: flex;
            justify-content: space-between;
            margin: 20px;
            background-color: #3A3A3A; /* Couleur de fond */
            color: #FFFFFF; /* Couleur du texte */
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            height: 100vh;
            overflow: hidden; /* Empêcher le défilement */
        }

        #search-container {
            width: 25%;
            margin-top: 20px;
            margin-left: 20px; /* Ajouter de l'espace à gauche */
            display: flex;
            flex-direction: column;
        }

        #search-bar {
            width: 100%;
            padding: 10px;
            border: 1px solid #A9FE77;
            border-radius: 5px;
            font-size: 16px;
            margin-bottom: 10px;
        }

        #no-results-container {
            display: none;
            background-color: #A9FE77;
            color: #000000; /* Couleur du texte */
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
        }

        #results-container {
            display: none;
            background-color: #A9FE77;
            color: #000000; /* Couleur du texte */
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
            overflow-y: auto; /* Ajouter un défilement vertical si nécessaire */
            max-height: calc(100vh - 200px); /* Ajuster la hauteur maximale */
            width: 100%; /* Élargir le conteneur pour qu'il soit de la même largeur que les résultats */
        }

        #results-list {
            list-style-type: none;
            padding: 0;
        }

        .result-card {
            background-color: #FFFFFF;
            color: #000000;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
            position: relative;
        }

        .result-card h3 {
            margin: 0;
        }

        .result-card button {
            position: absolute;
            right: 10px;
            top: 10px;
            background-color: #A9FE77;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
        }

        .result-details {
            display: none;
            margin-top: 10px;
        }

        #map-container {
            width: 70%;
            margin-top: 20px;
            margin-bottom: 20px;
            height: calc(100vh - 80px); /* Hauteur totale moins les marges en haut et en bas */
        }

        #map {
            height: 100%;
            width: 100%;
            border: 1px solid black; /* Contour noir */
            border-radius: 15px; /* Coins arrondis */
<<<<<<< Updated upstream
=======
        }

        .custom-icon {
            border: 2px solid black; /* Contour noir */
            border-radius: 50%; /* Coins arrondis */
            background-color: #A9FE77; /* Couleur de fond */
            width: 35px; /* Largeur de l'icône */
            height: 50px; /* Hauteur de l'icône */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .custom-icon img {
            width: 100%;
            height: 100%;
            border-radius: 50%; /* Coins arrondis */
        }

        .label {
            background-color: transparent; /* Fond transparent */
            border: none; /* Pas de contour */
            padding: 2px 5px;
            font-size: 12px;
            color: #000000; /* Couleur du texte */
            white-space: nowrap;
>>>>>>> Stashed changes
        }
    </style>
</head>

<body>
    <div id="search-container">
        <h1>Position des gyms à proximité</h1>
        <input type="text" id="search-bar" placeholder="Rechercher un gym (ex: Econofitness)">
        <div id="no-results-container">
            Aucun résultat trouvé pour la recherche "<span id="search-term"></span>"
        </div>
        <div id="results-container">
            <h2>Voici les résultats de recherche :</h2>
            <ul id="results-list"></ul>
        </div>
    </div>
    <div id="map-container">
        <div id="map"></div>
    </div>
    <script>
        // Initialiser la carte avec les coordonnées du Cégep de Trois-Rivières
        var map = L.map('map').setView([46.35503515618501, -72.57240632483241], 13); // Coordonnées du Cégep de Trois-Rivières

        // Ajouter une couche de tuiles OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Créer une icône personnalisée sans contour
        var customIcon = L.icon({
            iconUrl: '/img/green-marker-icon.png', // Chemin de votre icône personnalisée
            iconSize: [45, 45], // Taille de l'icône
            iconAnchor: [17, 50], // Point de l'icône qui correspondra à la position du marqueur
            popupAnchor: [1, -34] // Point depuis lequel la popup doit s'ouvrir par rapport à l'icône
        });

        // Créer une icône pour la position actuelle
        var currentLocationIcon = L.icon({
            iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon.png',
            iconSize: [25, 41], // Taille de l'icône
            iconAnchor: [12, 41], // Point de l'icône qui correspondra à la position du marqueur
            popupAnchor: [1, -34] // Point depuis lequel la popup doit s'ouvrir par rapport à l'icône
        });

        // Stocker les marqueurs ajoutés à la carte
        var markers = [];

        // Fonction pour obtenir les gymnases dans un rayon de 5 km
        function getGyms(lat, lon, filter = '') {
            var overpassUrl = 'https://overpass-api.de/api/interpreter?data=[out:json];(node["leisure"="sports_centre"](around:5000,' + lat + ',' + lon + ');node["leisure"="fitness_centre"](around:5000,' + lat + ',' + lon + '););out;';
            fetch(overpassUrl)
                .then(response => response.json())
                .then(data => {
                    console.log(data); // Afficher les données retournées par l'API

                    // Supprimer les marqueurs existants
                    markers.forEach(marker => map.removeLayer(marker));
                    markers = [];

                    // Ajouter un marqueur pour la position actuelle avec un label
                    var currentLocationMarker = L.marker([lat, lon], { icon: currentLocationIcon }).addTo(map);
                    var currentLocationLabel = L.divIcon({
                        className: 'label',
                        html: 'Vous êtes ici',
                        iconSize: [100, 20], // Taille du label
                        iconAnchor: [50, 0] // Point de l'icône qui correspondra à la position du marqueur
                    });
                    L.marker([lat, lon], { icon: currentLocationLabel }).addTo(map);
                    markers.push(currentLocationMarker);

                    var gyms = data.elements.filter(element => element.tags.name && element.tags.name.toLowerCase().includes(filter.toLowerCase()));
                    if (gyms.length === 0) {
                        document.getElementById('no-results-container').style.display = 'block';
                        document.getElementById('search-term').innerText = filter;
                        document.getElementById('results-container').style.display = 'none';
                    } else {
                        document.getElementById('no-results-container').style.display = 'none';
                        document.getElementById('results-container').style.display = 'block';
                        var resultsList = document.getElementById('results-list');
                        resultsList.innerHTML = '';
                        gyms.forEach(element => {
                            var marker = L.marker([element.lat, element.lon], { icon: customIcon }).addTo(map)
                                .bindPopup(element.tags.name || 'Gymnase');
                            markers.push(marker);

                            // Ajouter les résultats à la liste
                            var listItem = document.createElement('li');
                            listItem.className = 'result-card';

                            var gymName = element.tags.name;
                            var gymLocation = element.tags['addr:street'] ? ' - ' + element.tags['addr:street'] : '';
                            var gymDetails = `
                                <div class="result-details">
                                    <p>Adresse: ${element.tags['addr:street'] || 'N/A'}</p>
                                    <p>Téléphone: ${element.tags['contact:phone'] || 'N/A'}</p>
                                </div>
                            `;

                            listItem.innerHTML = `
                                <h3>${gymName}${gymLocation}</h3>
                                <button onclick="toggleDetails(this)">+</button>
                                ${gymDetails}
                            `;
                            resultsList.appendChild(listItem);
                        });
                    }
                });
        }

        // Fonction pour afficher/masquer les détails des gyms
        function toggleDetails(button) {
            var details = button.nextElementSibling;
            if (details.style.display === 'none' || details.style.display === '') {
                details.style.display = 'block';
                button.textContent = '-';
            } else {
                details.style.display = 'none';
                button.textContent = '+';
            }
        }

        // Utiliser la géolocalisation pour centrer la carte et obtenir les gymnases
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                var lat = position.coords.latitude;
                var lon = position.coords.longitude;
                map.setView([lat, lon], 13);
                getGyms(lat, lon);
            });
        } else {
            // Si la géolocalisation n'est pas disponible, utiliser des coordonnées par défaut
            getGyms(46.35503515618501, -72.57240632483241); // Coordonnées du Cégep de Trois-Rivières
        }

        // Ajouter un écouteur d'événement pour la barre de recherche
        document.getElementById('search-bar').addEventListener('input', function (e) {
            var filter = e.target.value;
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    var lat = position.coords.latitude;
                    var lon = position.coords.longitude;
                    map.setView([lat, lon], 13);
                    getGyms(lat, lon, filter);
                });
            } else {
                getGyms(46.35503515618501, -72.57240632483241, filter); // Coordonnées du Cégep de Trois-Rivières
            }
        });
    </script>
</body>

</html>