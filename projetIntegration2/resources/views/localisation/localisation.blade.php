@extends('layouts.app')
@section('style')
    <style>
        body {
            display: flex;
            justify-content: space-between;
            margin: 20px;
            background-color: #3A3A3A;
            /* Couleur de fond */
            color: #FFFFFF;
            /* Couleur du texte */
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            height: 100vh;
            overflow: hidden;
            /* Empêcher le défilement */
        }

        #search-container {
            width: 30%;
            margin-top: 40px;
            margin-left: 20px;
            margin-right: 20px;
            /* Ajouter de l'espace à gauche */
            display: flex;
            flex-direction: column;
            position: relative;
        }

        #search-input-container {
            width: 96%;
            margin-top: 10px;
            display: flex;
            align-items: center;
            position: relative;
        }

        #search-bar {
            flex: 1;
            padding: 10px;
            padding-right: 35px;
            /* Pour laisser de la place au bouton */
            border: 1px solid #A9FE77;
            border-radius: 5px;
            font-size: 16px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        #clear-search {
            background: none;
            border: none;
            font-size: 16px;
            cursor: pointer;
            color: #A9FE77;
            text-shadow: -1px -1px 0 #000, 1px -1px 0 #000, -1px 1px 0 #000, 1px 1px 0 #000;
            display: inline;
            /* Un petit marge négative permet de le superposer à l'intérieur de la textbox */
            margin-left: -35px;
            margin-bottom: 10px;
        }

        #no-results-container {
            display: none;
            background-color: #A9FE77;
            color: #000000;
            /* Couleur du texte */
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
        }

        #results-container {
            display: none;
            background-color: #A9FE77;
            color: #000000;
            /* Couleur du texte */
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
            overflow-y: auto;
            /* Ajouter un défilement vertical si nécessaire */
            max-height: calc(100vh - 200px);
            /* Ajuster la hauteur maximale */
            width: 100%;
            /* Élargir le conteneur pour qu'il soit de la même largeur que les résultats */
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
            flex: 1;
            margin: auto 20px;
            height: calc(100vh - 80px);
            /* Hauteur totale moins les marges en haut et en bas */
            position: relative;
        }

        #map {
            height: 100%;
            width: 100%;
            border: 1px solid black;
            /* Contour noir */
            border-radius: 15px;
            /* Coins arrondis */
        }

        .custom-icon {
            border: 2px solid black;
            /* Contour noir */
            border-radius: 50%;
            /* Coins arrondis */
            background-color: #A9FE77;
            /* Couleur de fond */
            width: 35px;
            /* Largeur de l'icône */
            height: 50px;
            /* Hauteur de l'icône */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .custom-icon img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            /* Coins arrondis */
        }

        .label {
            background-color: transparent;
            /* Fond transparent */
            border: none;
            /* Pas de contour */
            padding: 2px 5px;
            font-size: 12px;
            color: #000000;
            /* Couleur du texte */
            white-space: nowrap;
            text-align: center;
            color: white;
            text-shadow: -1px -1px 0 #000, 1px -1px 0 #000, -1px 1px 0 #000, 1px 1px 0 #000;
        }

        /* Add/update your CSS */
        #current-location-btn {
            position: absolute;
            bottom: 10px;
            left: 300px;
            /* Adjust this value so the button appears to the left of your legend */
            z-index: 1000;
            padding: 10px;
            background-color: #A9FE77;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        /* Increase the size of the Font Awesome icon */
        #current-location-btn i {
            font-size: 24px;
            /* Increase as needed */
        }
    </style>
@endsection
@section('contenu')
<div id="main-container" style="display: flex; flex-direction: row; height: 100vh;">
    <!-- Section résultats de recherche à gauche (30% de la largeur) -->
    <div id="search-container" style="flex: 0 0 30%;">
        <h1>Position des gyms à proximité</h1>
        <div id="search-input-container" style="margin-bottom: 20px;">
            <input type="text" id="search-bar" placeholder="Rechercher un gym (ex: Econofitness)" style="width: 90%;">
            <button id="clear-search">x</button>
        </div>
        <div id="no-results-container">
            {{ __('gyms.aucun_resultats') }}<span id="search-term"></span>"
        </div>
        <div id="results-container">
            <h2>{{ __('gyms.resultats_recherche') }}</h2>
            <ul id="results-list"></ul>
        </div>
    </div>
    
    <!-- Conteneur de la map à droite (70% de la largeur) -->
    <div id="map-container">
        <div id="map"></div>
        <div style="position: absolute; top: 10px; right: 10px; z-index: 1000; background: rgba(0,0,0,0.5); padding: 5px; border-radius: 4px;">
            <input type="checkbox" id="toggle-circles" checked>
            <label for="toggle-circles" style="color: white;">{{ __('gyms.voir_cercle_traffic') }}</label>
        </div>
        <button id="current-location-btn"><i class="fa-solid fa-location-crosshairs"></i></button>
    </div>
</div>
    <script>
        const translations = {
            youAreHere: "{{ __('gyms.vous_etes_ici') }}",
            geolocationNotSupported: "{{ __('gyms.geolocalisation_non_supporte') }}",
            address: "{{ __('gyms.adresse') }}",
            phone: "{{ __('gyms.telephone') }}",
            website: "{{ __('gyms.site_web') }}",
            email: "{{ __('gyms.email') }}",
            traffic: "{{ __('gyms.traffic') }}",
            legendTitle: "{{ __('gyms.titre_legendre') }}",
            veryLowTraffic: "{{ __('gyms.traffic_super_faible') }}",
            lowTraffic: "{{ __('gyms.traffic_faible') }}",
            mediumTraffic: "{{ __('gyms.traffic_moyen') }}",
            highTraffic: "{{ __('gyms.traffic_eleve') }}",
            veryHighTraffic: "{{ __('gyms.traffic_tres_eleve') }}",
            doubleClickTip: "{{ __('gyms.double_click_tip') }}"
        };
        // Initialiser la carte avec les coordonnées du Cégep de Trois-Rivières---------------------------------------------------------------------
        var map = L.map('map').setView([46.35503515618501, -72.57240632483241], 13); // Coordonnées du Cégep de Trois-Rivières

        // Ajouter une couche de tuiles OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Créer une icône personnalisée sans contour
        var customIcon = L.icon({
            iconUrl: '/img/green-marker-icon.png', // Chemin de votre icône personnalisée
            iconSize: [45, 45], // Taille de l'icône
            iconAnchor: [22, 45] // Point d'ancrage de l'icône
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

        // Taille de base pour les cercles et zoom de référence
        var baseZoom = 13;
        var baseSize = 120;

        // Tableau pour stocker les marqueurs gradient
        var gradientMarkers = [];

        // Fonction utilitaire pour calculer la couleur en fonction de l'achalandage (valeur entre 1 et 100)
        function getColor(achalandage) {
            // Ratio entre 0 et 1
            var ratio = (achalandage - 1) / 99;
            // Hue: 240 (bleu) pour ratio = 0 et 0 (rouge) pour ratio = 1
            var hue = (1 - ratio) * 240;
            // L'opacité varie de 0.3 à 1.0
            var opacity = 0.3 + 0.7 * ratio;
            return {
                color: `hsla(${hue}, 100%, 50%, ${opacity})`,
                opacity: opacity
            };
        }

        // Fonction pour obtenir les gymnases dans un rayon de 5 km
        function getGyms(lat, lon, filter = '') {
            var overpassUrl = 'https://overpass-api.de/api/interpreter?data=[out:json];(node["leisure"="sports_centre"](around:5000,' + lat + ',' + lon + ');node["leisure"="fitness_centre"](around:5000,' + lat + ',' + lon + '););out;';
            fetch(overpassUrl)
                .then(response => response.json())
                .then(data => {
                    console.log(data); // Afficher les données retournées par l'API

                    // Supprimer les marqueurs (et cercles) existants
                    markers.forEach(item => map.removeLayer(item));
                    markers = [];

                    // Ajouter un marqueur pour la position actuelle avec un label
                    var currentLocationMarker = L.marker([lat, lon], {
                        icon: currentLocationIcon
                    }).addTo(map);
                    var currentLocationLabel = L.divIcon({
                        className: 'label',
                        iconSize: [100, 20],
                        iconAnchor: [50, 60]
                    });
                    L.marker([lat, lon], {
                        icon: currentLocationLabel
                    }).addTo(map);
                    markers.push(currentLocationMarker);

                    // Filtrer les gymnases
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
                            var gymName = element.tags.name;
                            var gymLocation = element.tags['addr:street'] ? ' - ' + element.tags['addr:street'] : '';

                            // Simuler une statistique d'achalandage (entre 1 et 100)
                            var achalandage = Math.floor(Math.random() * 100) + 1;

                            // Choisir la couleur du cercle en fonction de l'achalandage
                            var centerColor = '';
                            var edgeColor = '';
                            if (achalandage <= 20) {
                                centerColor = 'hsla(210,100%,80%,1)'; // bleu pale
                                edgeColor = 'hsla(210,100%,80%,0)';
                            } else if (achalandage <= 40) {
                                centerColor = 'hsla(120,100%,50%,1)'; // vert
                                edgeColor = 'hsla(120,100%,50%,0)';
                            } else if (achalandage <= 60) {
                                centerColor = 'hsla(60,100%,50%,1)'; // jaune
                                edgeColor = 'hsla(60,100%,50%,0)';
                            } else if (achalandage <= 80) {
                                centerColor = 'hsla(30,100%,50%,1)'; // orange
                                edgeColor = 'hsla(30,100%,50%,0)';
                            } else {
                                centerColor = 'hsla(0,100%,50%,1)';  // rouge
                                edgeColor = 'hsla(0,100%,50%,0)';
                            }

                            // Taille de base pour le cercle (modifiable)
                            var baseSize = 50;

                            // Créer un icône gradient centré sur le pin
                            var gradientIcon = L.divIcon({
                                className: 'gradient-circle',
                                iconSize: [baseSize, baseSize],
                                iconAnchor: [baseSize / 2, baseSize / 2],
                                html: `<div style="
                                              width: ${baseSize}px;
                                              height: ${baseSize}px;
                                              border-radius: 50%;
                                              background: radial-gradient(circle, ${centerColor} 0%, ${edgeColor} 70%);
                                           "></div>`
                            });

                            // Créer un marqueur (pin) pour le gym avec un grand zIndexOffset pour qu'il soit au-dessus
                            var marker = L.marker([element.lat, element.lon], {
                                icon: customIcon,
                                zIndexOffset: 1000
                            }).addTo(map)
                                .bindPopup(gymName || 'Gymnase');
                            markers.push(marker);

                            // Ajouter le gradient en tant que marker (non interactif) en dessous du pin
                            var gradientMarker = L.marker([element.lat, element.lon], {
                                icon: gradientIcon,
                                interactive: false,
                                zIndexOffset: 0
                            }).addTo(map);
                            markers.push(gradientMarker);

                            // Stocker le gradientMarker et la teinte utilisée dans le tableau pour actualisation lors du zoom
                            gradientMarkers.push({
                                marker: gradientMarker,
                                centerColor: centerColor,
                                edgeColor: edgeColor
                            });

                            // Lorsqu'on clique sur le marker, mettre le nom dans la barre de recherche et relancer la recherche
                            marker.on('click', function() {
                                document.getElementById('search-bar').value = gymName;
                                getGyms(lat, lon, gymName);
                            });

                            // Construire l'élément de résultat pour le menu de gauche
                            var gymDetails = `
                                    <div class="result-details" style="display: none;">
                                        <p><strong>Name:</strong> ${gymName || 'N/A'}</p>
                                        <p><strong>Address:</strong> ${element.tags['addr:street'] || 'N/A'}, ${element.tags['addr:city'] || 'N/A'} ${element.tags['addr:postcode'] || ''}</p>
                                        <p><strong>Phone:</strong> ${element.tags['contact:phone'] || element.tags.phone || 'N/A'}</p>
                                        <p><strong>Website:</strong> ${element.tags.website ? '<a href="' + element.tags.website + '" target="_blank">' + element.tags.website + '</a>' : 'N/A'}</p>
                                        <p><strong>Email:</strong> ${element.tags['contact:email'] || element.tags.email || 'N/A'}</p>
                                        <p><strong>Achalandage:</strong> ${achalandage}</p>
                                    </div>
                                `;
                            var listItem = document.createElement('li');
                            listItem.className = 'result-card';
                            listItem.innerHTML = `
                                    <h3>${gymName}${gymLocation}</h3>
                                    <div class="toggle-container">
                                        <button onclick="toggleDetails(this)">+</button>
                                        ${gymDetails}
                                    </div>
                                `;
                            resultsList.appendChild(listItem);
                        });
                    }
                });
        }

        // Fonction pour afficher les détails d'un gym spécifique
        function showGymDetails(element) {
            var resultsList = document.getElementById('results-list');
            var gymName = element.tags.name;
            var gymLocation = element.tags['addr:street'] ? ' - ' + element.tags['addr:street'] : '';
            var gymDetails = `
                    <div class="result-details">
                        <p>Adresse: ${element.tags['addr:street'] || 'N/A'}</p>
                        <p>Téléphone: ${element.tags['contact:phone'] || 'N/A'}</p>
                        <p>Site Web: ${element.tags['website'] ? '<a href="' + element.tags['website'] + '" target="_blank">' + element.tags['website'] + '</a>' : 'N/A'}</p>
                        <p>Email: ${element.tags['contact:email'] || 'N/A'}</p>
                    </div>
                `;
            resultsList.innerHTML = `
                    <li class="result-card">
                        <h3>${gymName}${gymLocation}</h3>
                        <div class="toggle-container">
                            <button onclick="toggleDetails(this)">-</button>
                            <div class="result-details" style="display: block;">
                                ${gymDetails}
                            </div>
                        </div>
                    </li>
                `;
        }

        // Fonction pour afficher/masquer les détails des gyms
        function toggleDetails(button) {
            var details = button.parentElement.querySelector('.result-details');
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
            navigator.geolocation.getCurrentPosition(function(position) {
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
        document.getElementById('search-bar').addEventListener('input', function(e) {
            var filter = e.target.value;
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var lat = position.coords.latitude;
                    var lon = position.coords.longitude;
                    map.setView([lat, lon], 13);
                    getGyms(lat, lon, filter);
                });
            } else {
                getGyms(46.35503515618501, -72.57240632483241, filter); // Coordonnées du Cégep de Trois-Rivières
            }
        });

        map.on('zoomend', function () {
            var currentZoom = map.getZoom();
            // Exponential scaling: adjust the exponent (e.g., 2) to shrink circles more when zooming out
            var newSize = baseSize * Math.pow(currentZoom / baseZoom, 5);
            gradientMarkers.forEach(function (item) {
                var newIcon = L.divIcon({
                    className: 'gradient-circle',
                    iconSize: [newSize, newSize],
                    iconAnchor: [newSize / 2, newSize / 2],
                    html: `<div style="
                                  width: ${newSize}px;
                                  height: ${newSize}px;
                                  border-radius: 50%;
                                  background: radial-gradient(circle, ${item.centerColor} 0%, ${item.edgeColor} 70%);
                               "></div>`
                });
                item.marker.setIcon(newIcon);
            });
        });

        // Assuming gradientMarkers is an array storing all your gradient marker objects.
        document.getElementById('toggle-circles').addEventListener('change', function(e) {
            var showCircles = e.target.checked;
            gradientMarkers.forEach(function(item) {
                if (showCircles) {
                    // If the circle was removed, add it back to the map
                    if (!map.hasLayer(item.marker)) {
                        item.marker.addTo(map);
                    }
                } else {
                    // Remove the gradient marker from the map
                    if (map.hasLayer(item.marker)) {
                        map.removeLayer(item.marker);
                    }
                }
            });
        });

        // Create a legend control and add it to the map (bottom left)
        var legend = L.control({ position: 'bottomleft' });

        legend.onAdd = function (map) {
            var div = L.DomUtil.create('div', 'info legend');
            div.style.backgroundColor = 'rgba(255, 255, 255, 0.8)';
            div.style.padding = '10px';
            div.style.borderRadius = '5px';
            div.style.fontSize = '14px';
            div.style.color = '#333';

            // Contenu de la légende en français
            div.innerHTML =
                "<div style='text-align: center;'><strong>Légende Achalandage</strong><br></div>" +
                "<i style='background: hsla(210,100%,80%,1); width:18px; height:18px; display:inline-block; margin-right:5px;'></i> Très peu achalandé (<=20)<br>" +
                "<i style='background: hsla(120,100%,50%,1); width:18px; height:18px; display:inline-block; margin-right:5px;'></i> Peu achalandé (<=40)<br>" +
                "<i style='background: hsla(60,100%,50%,1); width:18px; height:18px; display:inline-block; margin-right:5px;'></i> Achalandage moyen (<=60)<br>" +
                "<i style='background: hsla(30,100%,50%,1); width:18px; height:18px; display:inline-block; margin-right:5px;'></i> Très achalandé (<=80)<br>" +
                "<i style='background: hsla(0,100%,50%,1); width:18px; height:18px; display:inline-block; margin-right:5px;'></i> Énormément achalandé (>80)<br>" +
                "<br><em>Double-cliquez sur la carte pour relocaliser</em>";
            return div;
        };

        legend.addTo(map);
    </script>
    <script>
        document.getElementById('search-bar').addEventListener('input', function () {
            var searchBar = document.getElementById('search-bar');
            var clearButton = document.getElementById('clear-search');
            if (searchBar.value.length > 0) {
                clearButton.style.display = 'inline';
            } else {
                clearButton.style.display = 'inline';
            }
        });

        document.getElementById('clear-search').addEventListener('click', function () {
            var searchBar = document.getElementById('search-bar');
            searchBar.value = '';
            this.style.display = 'inline';
            // Déclenche un événement input pour mettre à jour la recherche (si votre code de recherche y est abonné)
            searchBar.dispatchEvent(new Event('input'));
        });

        // Supposons que vous avez déjà créé et affiché le marqueur de position actuel, par exemple :
        var currentPosMarker;

        // Si la géolocalisation a été utilisée, créez le marqueur initial
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var lat = position.coords.latitude;
                var lon = position.coords.longitude;
                var initialLatLng = [lat, lon];
                map.setView(initialLatLng, 13);
                currentPosMarker = L.marker(initialLatLng, {
                    icon: currentLocationIcon,
                    draggable: false
                }).addTo(map);
                currentPosMarker.bindTooltip(translations.youAreHere, {
                    permanent: true,
                    direction: 'top',
                    offset: [1, -37],
                    className: 'current-position-tooltip'
                }).openTooltip();
                // Afficher les gyms autour de la position initiale
                getGyms(lat, lon);
            });
        }

        // Ajoutez un écouteur d'événement pour le double-clic sur la carte
        // Vous pouvez changer 'dblclick' en 'click' si vous préférez un simple clic
        map.on('dblclick', function (e) {
            var newLatLng = e.latlng;
            // Remove the old current position marker if it exists
            if (typeof currentPosMarker !== 'undefined' && currentPosMarker) {
                map.removeLayer(currentPosMarker);
            }
            // Add a new marker at the double-clicked location
            currentPosMarker = L.marker(newLatLng, {
                icon: currentLocationIcon,
                draggable: false
            }).addTo(map);
            map.setView(newLatLng, 13);
            getGyms(newLatLng.lat, newLatLng.lng);
        });
    </script>
    <script>
        // Variable globale pour le marqueur de position (initial ou sélectionné)
        var currentPosMarker;

        // Créer le marqueur initial avec le tooltip "Vous êtes ici"
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                var lat = position.coords.latitude;
                var lon = position.coords.longitude;
                var initialLatLng = [lat, lon];
                map.setView(initialLatLng, 13);
                currentPosMarker = L.marker(initialLatLng, { icon: currentLocationIcon, draggable: false }).addTo(map);
                currentPosMarker.bindTooltip("Vous êtes ici", {
                    permanent: true, direction: 'top', offset: [1, -37], className: 'current-position-tooltip'
                }).openTooltip();
                getGyms(lat, lon);
            });
        }

        // Sur double-clic (ou clic) sur la carte, déplacez le marqueur et mettez à jour la tooltip
        map.on('dblclick', function (e) {
            var newLatLng = e.latlng;
            if (currentPosMarker) {
                currentPosMarker.setLatLng(newLatLng);
                // Réactualiser la tooltip pour qu'elle suive le marqueur
                currentPosMarker.bindTooltip("Vous êtes ici", {
                    permanent: true, direction: 'top', offset: [0, -15], className: 'current-position-tooltip'
                }).openTooltip();
            } else {
                currentPosMarker = L.marker(newLatLng, { icon: currentLocationIcon, draggable: false }).addTo(map);
                currentPosMarker.bindTooltip("Vous êtes ici", {
                    permanent: true, direction: 'top', offset: [0, -15], className: 'current-position-tooltip'
                }).openTooltip();
            }
            map.setView(newLatLng);
            getGyms(newLatLng.lat, newLatLng.lng);
        });
    </script>
    <script>
        // Variable globale pour le marqueur de position (initial ou sélectionné)
        var currentPosMarker;

        // Créer le marqueur initial sans le tooltip "Vous êtes ici"
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                var lat = position.coords.latitude;
                var lon = position.coords.longitude;
                var initialLatLng = [lat, lon];
                map.setView(initialLatLng, 13);
                currentPosMarker = L.marker(initialLatLng, { icon: currentLocationIcon, draggable: false }).addTo(map);
                getGyms(lat, lon);
            });
        }

        // Lors d'un double-clic sur la carte, supprimez le marqueur actuel
        // et affichez un nouveau pin sans aucun tooltip
        map.on('dblclick', function (e) {
            var newLatLng = e.latlng;
            // Supprimer le marqueur actuel s'il existe
            if (currentPosMarker) {
                map.removeLayer(currentPosMarker);
            }
            // Créer un nouveau marqueur et le stocker dans currentPosMarker
            currentPosMarker = L.marker(newLatLng, { icon: currentLocationIcon, draggable: false }).addTo(map);
            map.setView(newLatLng, 13);
            getGyms(newLatLng.lat, newLatLng.lng);
        });
    </script>
    <script>
        // Déclarer la variable globale pour enregistrer le dernier double-clic
        var currentPosMarker;
        var lastDoubleClickLatLng;

        // Créer le marqueur initial avec le tooltip "Vous êtes ici"
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                var lat = position.coords.latitude;
                var lon = position.coords.longitude;
                var initialLatLng = [lat, lon];
                map.setView(initialLatLng, 13);
                currentPosMarker = L.marker(initialLatLng, { icon: currentLocationIcon, draggable: false }).addTo(map);
                currentPosMarker.bindTooltip("Vous êtes ici", {
                    permanent: true, direction: 'top', offset: [1, -37], className: 'current-position-tooltip'
                }).openTooltip();
                getGyms(lat, lon);
            });
        }

        // Sur double-clic (ou clic) sur la carte, déplacez le marqueur, enregistrez la position, et mettez à jour la tooltip
        map.on('dblclick', function (e) {
            var newLatLng = e.latlng;
            lastDoubleClickLatLng = newLatLng; // Enregistrer la dernière position double-clic
            if (currentPosMarker) {
                currentPosMarker.setLatLng(newLatLng);
                currentPosMarker.bindTooltip("Vous êtes ici", {
                    permanent: true, direction: 'top', offset: [0, -15], className: 'current-position-tooltip'
                }).openTooltip();
            } else {
                currentPosMarker = L.marker(newLatLng, { icon: currentLocationIcon, draggable: false }).addTo(map);
                currentPosMarker.bindTooltip("Vous êtes ici", {
                    permanent: true, direction: 'top', offset: [0, -15], className: 'current-position-tooltip'
                }).openTooltip();
            }
            map.setView(newLatLng);
        });

        // Lorsque l'utilisateur clique sur le bouton "x" de la barre de recherche, revenir à la dernière position double-cliquée
        document.getElementById('clear-search').addEventListener('click', function () {
            // Réinitialiser la barre de recherche
            document.getElementById('search-bar').value = '';
            // Revenir à la dernière position double clic si définie
            if (lastDoubleClickLatLng) {
                map.setView(lastDoubleClickLatLng);
            }
        });
    </script>
    <script>
        // Place this JavaScript code after your map/legend initialization code
        document.getElementById('current-location-btn').addEventListener('click', function () {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    var lat = position.coords.latitude;
                    var lon = position.coords.longitude;
                    var currentLatLng = [lat, lon];
                    map.setView(currentLatLng, 13);

                    // Remove the old marker if it exists
                    if (currentPosMarker) {
                        map.removeLayer(currentPosMarker);
                    }
                    // Add a new marker at the current location
                    currentPosMarker = L.marker(currentLatLng, { icon: currentLocationIcon, draggable: false }).addTo(map);

                    // Optionally update nearby gyms
                    getGyms(lat, lon);
                });
            } else {
                alert('La géolocalisation n\'est pas supportée par ce navigateur.');
            }
        });
    </script>
    <script>
        // Variables globales pour le marqueur et la dernière position double-clic
        var currentPosMarker;
        var lastDoubleClickLatLng;

        // Créer le marqueur initial avec la géolocalisation
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                var lat = position.coords.latitude;
                var lon = position.coords.longitude;
                var initialLatLng = [lat, lon];
                map.setView(initialLatLng, 13);
                currentPosMarker = L.marker(initialLatLng, { icon: currentLocationIcon, draggable: false }).addTo(map);
                // Afficher les gyms autour de la position initiale
                getGyms(lat, lon);
            });
        }

        // Écouteur pour le double-clic sur la carte
        map.on('dblclick', function (e) {
            var newLatLng = e.latlng;
            // Enregsitrer la dernière position double-clic
            lastDoubleClickLatLng = newLatLng;

            // Supprimer l'ancien marqueur s'il existe, puis en ajouter un nouveau
            if (currentPosMarker) {
                map.removeLayer(currentPosMarker);
            }
            currentPosMarker = L.marker(newLatLng, { icon: currentLocationIcon, draggable: false }).addTo(map);
            map.setView(newLatLng, 13);
            getGyms(newLatLng.lat, newLatLng.lng);
        });

        // Attendre que le DOM soit chargé pour ajouter l'événement au bouton "x"
        window.addEventListener('DOMContentLoaded', function () {
            document.getElementById('clear-search').addEventListener('click', function () {
                // Réinitialiser la barre de recherche
                document.getElementById('search-bar').value = '';
                // Si une position double-clic a été enregistrée, recentrer la vue dessus
                if (lastDoubleClickLatLng) {
                    map.setView(lastDoubleClickLatLng, 13);
                }
            });
        });
    </script>
@endsection