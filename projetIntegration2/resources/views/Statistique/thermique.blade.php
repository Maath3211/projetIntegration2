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
        <div class="heatmap" id="heatmap"></div>
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

        let moisActif = 0;

        const heatmap = document.getElementById("heatmap");
        const moisTitre = document.getElementById("moisTitre");
        const boutonPrecedant = document.getElementById("moisPrecedant");
        const boutonSuivant = document.getElementById("moisSuivant");

        function renderMonth(index) {
            heatmap.innerHTML = "";
            moisTitre.textContent = mois[index].nom;

            for (let i = 1; i <= mois[index].jours; i++) {
                let jourDiv = document.createElement("div");
                jourDiv.classList.add("jour");
                jourDiv.textContent = i;
                jourDiv.dataset.count = 0;

                const colors = ["#ffffff", "#dfffc2", "#a9fe77", "#60c22c", "#1e6f00"];
                const icons = ["", "🦵", "💪", "🏋️‍♂️", "🦿"];
                const originalNumber = i;

                jourDiv.addEventListener("click", function () {
                    let count = parseInt(this.dataset.count);
                    count = (count + 1) % colors.length;
                    this.dataset.count = count;
                    this.style.backgroundColor = colors[count];
                    this.textContent = count === 0 ? originalNumber : icons[count];
                });

                heatmap.appendChild(jourDiv);
            }
        }

        boutonPrecedant.addEventListener("click", function () {
            moisActif = (moisActif - 1 + mois.length) % mois.length;
            renderMonth(moisActif);
        });

        boutonSuivant.addEventListener("click", function () {
            moisActif = (moisActif + 1) % mois.length;
            renderMonth(moisActif);
        });

        renderMonth(moisActif);
    </script>
</body>

@endsection
