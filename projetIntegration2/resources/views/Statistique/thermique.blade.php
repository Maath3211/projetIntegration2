@extends('Layouts.app')
@section('contenu')

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #2c2c2c;
        color: white;
        text-align: center;
        margin: 0;
    }

    .boutonContainer {
        margin: 20px;
        display: flex;
        justify-content: center;
        gap: 10px;
    }

  
    .heatmapContainer {
        max-width: 800px;
        margin: 20px auto;
        background: white;
        padding: 20px;
        border-radius: 10px;
        color: black;
        display: flex;
        flex-direction: column;
        align-items: center;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    }

    .navigation {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        margin-bottom: 10px;
        gap: 10px;
    }

    .moisTitre {
        font-size: 22px;
        font-weight: bold;
    }

    .heatmap {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 8px;
        max-width: 90%;
    }

    .jour {
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

    .titre, .moisTitre {
        text-align: center;
        font-size: 32px;
        margin-bottom: 20px;
    }

        /* Légende */
        .legende {
        flex: 1;
        background-color: #f9f9f9;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }

    .legende h3 {
        margin-top: 0;
    }

    .legende ul {
        list-style-type: none;
        padding-left: 0;
    }

    .legende li {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }

    .legende .color-box {
        width: 20px;
        height: 20px;
        margin-right: 10px;
        border-radius: 5px;
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
        .heatmap {
            grid-template-columns: repeat(7, 1fr);
            gap: 6px;
        }

        .titre {
            font-size: 28px;
        }

        .moisTitre {
            font-size: 20px;
        }

        .boutonContainer {
            flex-direction: column;
        }

        .back-button, .nav-button {
            font-size: 14px;
            padding: 8px 16px;
        }
    }

    @media (max-width: 768px) {
        .heatmap {
            grid-template-columns: repeat(7, 1fr);
            gap: 4px;
        }

        .titre {
            font-size: 24px;
        }

        .moisTitre {
            font-size: 18px;
        }

        .boutonContainer {
            flex-direction: column;
            gap: 15px;
        }

        .back-button, .nav-button {
            font-size: 14px;
            padding: 8px 14px;
        }
    }

    @media (max-width: 600px) {
        .heatmap {
            grid-template-columns: repeat(7, 1fr);
            gap: 3px;
        }

        .titre {
            font-size: 22px;
        }

        .moisTitre {
            font-size: 16px;
        }

        .boutonContainer {
            flex-direction: column;
            gap: 10px;
        }

        .back-button, .nav-button {
            font-size: 12px;
            padding: 6px 12px;
        }
    }

    @media (max-width: 480px) {
        .heatmap {
            grid-template-columns: repeat(7, 1fr);
            gap: 2px;
        }

        .titre {
            font-size: 18px;
        }

        .moisTitre {
            font-size: 14px;
        }

        .boutonContainer {
            flex-direction: column;
            gap: 8px;
        }

        .back-button, .nav-button {
            font-size: 12px;
            padding: 6px 12px;
        }
    }

    @media (max-width: 320px) {
        .heatmap {
            grid-template-columns: repeat(7, 1fr);
            gap: 2px;
        }

        .titre {
            font-size: 16px;
        }

        .moisTitre {
            font-size: 12px;
        }

        .boutonContainer {
            flex-direction: column;
            gap: 5px;
        }

        .back-button, .nav-button {
            font-size: 10px;
            padding: 5px 10px;
        }
    }
</style>

<body>
    <div class="boutonContainer">
        <a href="/stats"><button class="bouton">Retour</button></a>
        <button class="bouton">Sauvegarder</button>
    </div>

    <h1 class="titre">Calendrier d'Activité</h1>

    <div class="heatmapContainer">
    <div class="navigation">
    <button class="bouton" id="moisPrecedant">⬅️ Précédent</button>
    <div class="moisTitre" id="moisTitre"></div>
    <button class="bouton" id="moisSuivant">Suivant ➡️</button>
</div>

<div class="navigation">
    <label for="anneeSelect">Année :</label>
    <select id="anneeSelect" class="bouton">
        <!-- Les options seront générées dynamiquement -->
    </select>
</div>
        <div class="heatmap" id="heatmap"></div>
        <div class="legende">
            <h3>Légende</h3>
            <ul>
                <li>
                    <div class="color-box" style="background-color: #ffffff;"></div>
                    Jour sans activité
                </li>
                <li>
                    <div class="color-box" style="background-color: #dfffc2;"></div>
                    Bras
                </li>
                <li>
                    <div class="color-box" style="background-color: #ffeb3b;"></div>
                   Jambe
                </li>
                <li>
                    <div class="color-box" style="background-color: #ffcccb;"></div>
                   Pectoraux
                </li>
                <li>
                    <div class="color-box" style="background-color: #a8c7e8;"></div>
                    Dos
                </li>
                <li>
                    <div class="color-box" style="background-color: #ffe0b2;"></div>
                    Course
                </li>
            </ul>
        </div>
    </div>
    </div>


<script>
    const mois = [
        { nom: "Janvier", jours: 31 },
        { nom: "Février", jours: 28 },
        { nom: "Mars", jours: 31 },
        { nom: "Avril", jours: 30 },
        { nom: "Mai", jours: 31 },
        { nom: "Juin", jours: 30 },
        { nom: "Juillet", jours: 31 },
        { nom: "Août", jours: 31 },
        { nom: "Septembre", jours: 30 },
        { nom: "Octobre", jours: 31 },
        { nom: "Novembre", jours: 30 },
        { nom: "Décembre", jours: 31 }
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
                            "{{ asset('images/iconeHeatmap/bras.png') }}",  // bras
                            "{{ asset('images/iconeHeatmap/jambe.png') }}",  // jambe
                            "{{ asset('images/iconeHeatmap/pectoraux.png') }}",  // pec
                            "{{ asset('images/iconeHeatmap/back.png') }}", // dos
                            "{{ asset('images/iconeHeatmap/course.png') }}"   //course
                        ];

            jourDiv.addEventListener("click", function () {
                if (this.dataset.editable === "true") {
                    let count = parseInt(this.dataset.count);
                    count = (count + 1) % colors.length;
                    this.dataset.count = count;
                    this.style.backgroundColor = colors[count];
                    if (count === 0) {
                        this.textContent = i;  // Affiche le jour
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

    boutonPrecedant.addEventListener("click", function () {
        moisSelectionne = (moisSelectionne - 1 + mois.length) % mois.length;
        renderMonth(moisSelectionne, anneeSelectionnee);
    });

    boutonSuivant.addEventListener("click", function () {
        moisSelectionne = (moisSelectionne + 1) % mois.length;
        renderMonth(moisSelectionne, anneeSelectionnee);
    });

    anneeSelect.addEventListener("change", function () {
        anneeSelectionnee = parseInt(this.value);
        renderMonth(moisSelectionne, anneeSelectionnee);
    });

    renderMonth(moisSelectionne, anneeSelectionnee);
</script>
</body>

@endsection
