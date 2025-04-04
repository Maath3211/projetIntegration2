@extends('layouts.app')
@section('contenu')
<link rel="stylesheet" style="text/css" href="\css\Statistique\thermique.css">

<body>
    <div class="boutonContainer">
        <a href="/stats"><button class="bouton">{{ __('stats.retourpage') }}</button></a>
        <form id="thermiqueForm" action="{{ route('Statistique.storeThermique') }}" method="POST">
            @csrf
            <input type="hidden" name="donnees" id="donnees">
            <button class="bouton" id="sauvegarder">{{ __('stats.sauvegarde') }}</button>
        </form>
    </div>

    <h1 class="titre">{{ __('stats.calendrier_activite') }}</h1>

    <div class="heatmapContainer">
        <div class="navigation">
            <button class="bouton" id="moisPrecedant">{{ __('stats.precedent') }}</button>
            <div class="moisTitre" id="moisTitre"></div>
            <button class="bouton" id="moisSuivant">{{ __('stats.suivant') }}</button>
        </div>

        <div class="navigation">
            <label for="anneeSelect">{{ __('stats.annee') }}</label>
            <select id="anneeSelect" class="bouton">

            </select>
        </div>
        <div class="heatmap" id="heatmap"></div>
        <div class="legende">
            <h3>{{ __('stats.legende') }}</h3>
            <ul>
                <li>
                    <div class="color-box" style="background-color: #ffffff;"></div>
                    {{ __('stats.journee_sans_activite') }}
                </li>
                <li>
                    <div class="color-box" style="background-color: #dfffc2;"></div>
                    {{ __('stats.bras') }}
                </li>
                <li>
                    <div class="color-box" style="background-color: #ffeb3b;"></div>
                    {{ __('stats.jambes') }}
                </li>
                <li>
                    <div class="color-box" style="background-color: #ffcccb;"></div>
                    {{ __('stats.pectoraux') }}
                </li>
                <li>
                    <div class="color-box" style="background-color: #a8c7e8;"></div>
                    {{ __('stats.dos') }}
                </li>
                <li>
                    <div class="color-box" style="background-color: #ffe0b2;"></div>
                    {{ __('stats.course') }}
                </li>
            </ul>
        </div>
    </div>
    </div>
    <script>
        let donnees = @json($donnees);
    </script>



    <script>
        const mois = [{
                nom: "{{ __('stats.janvier') }}",
                jours: 31
            },
            {
                nom: "{{ __('stats.fevrier') }}",
                jours: 28
            },
            {
                nom: "{{ __('stats.mars') }}",
                jours: 31
            },
            {
                nom: "{{ __('stats.avril') }}",
                jours: 30
            },
            {
                nom: "{{ __('stats.mai') }}",
                jours: 31
            },
            {
                nom: "{{ __('stats.juin') }}",
                jours: 30
            },
            {
                nom: "{{ __('stats.juillet') }}",
                jours: 31
            },
            {
                nom: "{{ __('stats.aout') }}",
                jours: 31
            },
            {
                nom: "{{ __('stats.septembre') }}",
                jours: 30
            },
            {
                nom: "{{ __('stats.octobre') }}",
                jours: 31
            },
            {
                nom: "{{ __('stats.novembre') }}",
                jours: 30
            },
            {
                nom: "{{ __('stats.decembre') }}",
                jours: 31
            }
        ];

        let aujourdHui = new Date();
        let jourActuel = aujourdHui.getDate();
        let moisActuel = aujourdHui.getMonth();
        let anneeActuelle = aujourdHui.getFullYear();
        let anneeMin = 2025;
        let anneeMax = 2035;
        let anneeSelectionnee = anneeActuelle >= anneeMin && anneeActuelle <= anneeMax ? anneeActuelle : anneeMin;
        let moisSelectionne = moisActuel; // Initialisé au mois actuel

        const heatmap = document.getElementById("heatmap");
        const moisTitre = document.getElementById("moisTitre");
        const boutonPrecedant = document.getElementById("moisPrecedant");
        const boutonSuivant = document.getElementById("moisSuivant");
        const anneeSelect = document.getElementById("anneeSelect");

        // Remplir la liste déroulante des années
        for (let annee = anneeMin; annee <= anneeMax; annee++) {
            let option = document.createElement("option");
            option.value = annee;
            option.textContent = annee;
            if (annee === anneeSelectionnee) {
                option.selected = true;
            }
            anneeSelect.appendChild(option);
        }

        function estBissextile(annee) {
            return (annee % 4 === 0 && annee % 100 !== 0) || (annee % 400 === 0);
        }

        function renderMonth(index, annee) {
            heatmap.innerHTML = "";
            moisTitre.textContent = `${mois[index].nom} ${annee}`;

            let joursDansMois = mois[index].jours;
            if (index === 1 && estBissextile(annee)) {
                joursDansMois = 29;
            }

            let isCurrentDate = (index === moisActuel && annee === anneeActuelle);

            for (let i = 1; i <= joursDansMois; i++) {
                let jourDiv = document.createElement("div");
                jourDiv.classList.add("jour");
                jourDiv.textContent = i;
                jourDiv.dataset.count = 0;

                if (isCurrentDate && i === jourActuel) {
                    jourDiv.style.border = "2px solid green";
                    jourDiv.style.cursor = "pointer";
                    jourDiv.dataset.editable = "true";
                } else {
                    jourDiv.style.opacity = "0.5";
                    jourDiv.style.pointerEvents = "none";
                }

                const colors = ["#ffffff", "#dfffc2", "#ffeb3b", "#ffcccb", "#a8c7e8", "#ffe0b2"];
                const icons = [
                    "",
                    "{{ asset('images/iconeHeatmap/bras.png') }}",
                    "{{ asset('images/iconeHeatmap/jambe.png') }}",
                    "{{ asset('images/iconeHeatmap/pectoraux.png') }}",
                    "{{ asset('images/iconeHeatmap/back.png') }}",
                    "{{ asset('images/iconeHeatmap/course.png') }}"
                ];

                let dateStr = `${annee}-${(index + 1).toString().padStart(2, '0')}-${i.toString().padStart(2, '0')}`;
                let activite = donnees.find(d => d.date === dateStr);

                if (activite) {
                    let count = activite.type_activite;
                    jourDiv.dataset.count = count;
                    jourDiv.style.backgroundColor = colors[count];

                    if (count !== 0) {
                        const iconImg = document.createElement("img");
                        iconImg.src = icons[count];
                        iconImg.alt = "Icone";
                        iconImg.style.width = "30px";
                        iconImg.style.height = "30px";
                        iconImg.style.margin = "auto";
                        jourDiv.innerHTML = "";
                        jourDiv.appendChild(iconImg);
                    }
                }

                jourDiv.addEventListener("click", function() {
                    if (this.dataset.editable === "true") {
                        let count = parseInt(this.dataset.count);
                        count = (count + 1) % colors.length;
                        this.dataset.count = count;
                        this.style.backgroundColor = colors[count];

                        if (count === 0) {
                            this.textContent = i;
                        } else {
                            const iconImg = document.createElement("img");
                            iconImg.src = icons[count];
                            iconImg.alt = "Icone";
                            iconImg.style.width = "30px";
                            iconImg.style.height = "30px";
                            iconImg.style.margin = "auto";
                            this.innerHTML = "";
                            this.appendChild(iconImg);
                        }
                    }
                });

                heatmap.appendChild(jourDiv);
            }
        }

        boutonPrecedant.addEventListener("click", function() {
            moisSelectionne = (moisSelectionne - 1 + mois.length) % mois.length;
            renderMonth(moisSelectionne, anneeSelectionnee);
        });

        boutonSuivant.addEventListener("click", function() {
            moisSelectionne = (moisSelectionne + 1) % mois.length;
            renderMonth(moisSelectionne, anneeSelectionnee);
        });

        anneeSelect.addEventListener("change", function() {
            anneeSelectionnee = parseInt(this.value);
            renderMonth(moisSelectionne, anneeSelectionnee);
        });

        document.getElementById("sauvegarder").addEventListener("click", function() {
            let jours = document.querySelectorAll(".jour");
            let donnees = [];
            console.log("click");
            jours.forEach(jour => {
                let jourText = jour.textContent.padStart(2, '0');
                console.log(jourText);
                let date = `${anneeSelectionnee}-${(moisSelectionne + 1).toString().padStart(2, '0')}-${jour.textContent.padStart(2, '0')}`;
                let count = parseInt(jour.dataset.count);

                if (count > 0) { // On enregistre seulement les jours avec une activité
                    let dateAujourdhui = new Date().toISOString().split('T')[0];
                    donnees.push({
                        date: dateAujourdhui,
                        type_activite: count
                    });
                }
            });

            if (donnees.length === 0) {
                alert("{{ __('stats.aucune_activite_select') }}");
                return;
            }

            console.log(donnees);
            document.getElementById('donnees').value = JSON.stringify(donnees);
            document.getElementById('thermiqueForm').submit();

        });



        renderMonth(moisSelectionne, anneeSelectionnee);
    </script>
</body>

@endsection