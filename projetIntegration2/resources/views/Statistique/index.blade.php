@extends('Layouts.app')
@section('contenu')

<style>
    body {
        background-color: #1a1a1a;
    }
    .container {
        width: 100%;
        min-height: 100vh;
        background-color: #1a1a1a;
        padding: 1.5rem;
        border-radius: 0.5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .profileImage {
        width: 6rem;
        height: 6rem;
        background-color: #666;
        border-radius: 50%;
    }
    .navBouton {
        background-color: #a9fe77;
        padding: 0.5rem 1rem;
        color: black;
        border-radius: 0.25rem;
    }
    .statContainer {
        margin-top: 1.5rem;
        width: 100%;
        max-width: 75%;
    }
    .statRow {
        display: flex;
        justify-content: space-between;
        border-bottom: 1px solid #666;
        padding-bottom: 0.5rem;
        flex-wrap: wrap;
    }
    h1 {
        font-size: 48px;
        font-weight: bold; 
        color: #a9fe77; 
        text-transform: uppercase; 
        letter-spacing: 2px; 
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3); 
        margin-bottom: 20px;
    }
    .hidden {
        display: none;
    }
    .input {
        background-color: #333;
        color: #fff;
        border: 1px solid #666;
        padding: 0.5rem;
        border-radius: 0.25rem;
        width: 20rem;
        margin-right: 1rem;
    }
    .input:focus {
        outline: none;
        border-color: #a9fe77;
    }
    .bouton {
        background-color: #a9fe77;
        padding: 0.5rem 1rem;
        color: black;
        border-radius: 0.25rem;
    }
    .bouton:hover {
        background-color: #7bdb5b;
    }
</style>

<div class="container">
    <!-- Profil Image -->
    <div class="flex justify-center mt-4">
        <div class="profileImage"><img src="{{ $usager->imageProfil }}" alt="image profil" /></div>
    </div>

    <div class="flex flex-wrap justify-center space-x-4 mt-4">
        <h1>Statistiques de {{ $usager->prenom }}</h1>
    </div>

    <!-- Navigation -->
    <div class="flex flex-wrap justify-center space-x-4 mt-4">
      <a href="/profil">  <button class="bouton">Profil</button></a>
    </div>
    
    <!-- Statistiques -->
    <div class="statContainer space-y-4">
        <div class="statRow">
            <span>Vos statistiques :</span>
            <a href="/thermique" class="text-gray-400">Voir mon calendrier</a>
        </div>
        
        <div class="statRow">
            <span>Nombre de fois au gym: {{ isset($foisGym) ? $foisGym->first()->score : 'N/A' }} fois</span>
        </div>
        
        <div class="statRow">
            <span>Meilleur suite : {{ isset($streak) ? $streak->first()->score : 'N/A' }}</span>
        </div>
        
        <div class="statRow">
            <span id="poidsValue" data-lbs="{{ isset($poid) ? $poid->first()->score : 'N/A' }}">
                Poids : {{ isset($poid) ? $poid->first()->score : 'N/A' }} lbs
            </span>
            <div class="flex space-x-2">
                <button class="bouton" onclick="convertWeight('lbs')">Lbs</button>
                <button class="bouton" onclick="convertWeight('kg')">Kg</button>
                <a href="/graphique" class="text-gray-400">Voir mon graphique</a>
            </div>
        </div>
        
        <!-- Bouton Ajouter un exercice -->
        <div class="flex justify-center mt-4">
            <button class="bouton" onclick="showAddExerciseForm()">Ajouter un exercice</button>
        </div>

        <!-- Formulaire d'ajout d'exercice -->
        <div id="addExerciseForm" class="statRow hidden">
            <input type="text" id="exerciseName" placeholder="Nom de l'exercice" class="input" />
            <input type="number" id="exerciseScore" placeholder="Score (en lbs)" class="input" />
            <button class="bouton" onclick="saveExercise()">Sauvegarder</button>
            <button type="button" class="bouton" onclick="cancelForm()">Annuler</button>
        </div>

        <!-- Boucle pour afficher tous les exercices -->
        @foreach($statistiques as $stat)
            <div class="statRow" id="exercise-{{ $stat->id }}">
                <span>{{ $stat->nomStatistique }} : {{ $stat->score }} lbs</span>
                <div class="flex space-x-2">
                    <button class="bouton">Lbs</button>
                    <button class="bouton">Kg</button>
                    <button class="bouton"  onclick="deleteExercise({{ $stat->id }})">🗑️</button>
                    <a href="/graphique/{{ $stat->id }}" class="text-gray-400">Voir mon graphique</a>
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
function convertWeight(unit) {
    let poidsSpan = document.getElementById('poidsValue');
    let lbs = parseFloat(poidsSpan.getAttribute('data-lbs'));

    if (!isNaN(lbs)) {
        if (unit === 'kg') {
            let kg = (lbs * 0.453592).toFixed(2);
            poidsSpan.innerHTML = `Poids : ${kg} kg`;
        } else {
            poidsSpan.innerHTML = `Poids : ${lbs} lbs`;
        }
    }
}

// Fonction pour afficher le formulaire d'ajout d'exercice
function showAddExerciseForm() {
    document.getElementById('addExerciseForm').classList.remove('hidden');
}

// Fonction pour enregistrer un exercice dans la base de données
function saveExercise() {
    let name = document.getElementById('exerciseName').value;
    let score = document.getElementById('exerciseScore').value;

    if (name && score) {
        // Utiliser AJAX pour envoyer les données à la base de données sans recharger la page
        let formData = new FormData();
        // On crée un tableau 'stats' avec un seul élément
        formData.append('stats[0][nomStatistique]', name);
        formData.append('stats[0][score]', score);

        fetch('/statistiques/save', {  // Utilisation de la route correcte
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                // Ajouter l'exercice à la liste
                let newExercise = document.createElement('div');
                newExercise.classList.add('statRow');
                newExercise.innerHTML = `
                    <span>${name} : ${score} lbs</span>
                    <div class="flex space-x-2">
                        <button class="bouton">Lbs</button>
                        <button class="bouton">Kg</button>
                        <button class="bouton" onclick="deleteExercise(${data.id})">🗑️</button>
                        <a href="/graphique/${data.id}" class="text-gray-400">Voir mon graphique</a>
                    </div>
                `;
                document.querySelector('.statContainer').appendChild(newExercise);

                // Réinitialiser le formulaire et cacher le formulaire d'ajout
                document.getElementById('exerciseName').value = '';
                document.getElementById('exerciseScore').value = '';
                document.getElementById('addExerciseForm').classList.add('hidden');
            } else {
                alert('Erreur lors de l\'ajout de l\'exercice');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors de l\'ajout de l\'exercice');
        });
    } else {
        alert('Veuillez remplir tous les champs.');
    }
}

// Fonction pour supprimer un exercice
function deleteExercise(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet exercice ?')) {
        // Utiliser AJAX pour envoyer une requête DELETE au serveur
        fetch(`/statistiques/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.message === 'Exercice supprimé avec succès !') {
                // Supprimer l'exercice du DOM
                const exerciseRow = document.getElementById(`exercise-${id}`);
                exerciseRow.remove(); // Enlever l'élément de la page
            } else {
                alert('Erreur lors de la suppression de l\'exercice');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors de la suppression de l\'exercice');
        });
    }
}

function cancelForm() {
    // Cacher le formulaire
    document.getElementById('addExerciseForm').classList.add('hidden');
    
    // Réinitialiser les champs du formulaire
    document.getElementById('exerciseName').value = '';
    document.getElementById('exerciseScore').value = '';
}

</script>

@endsection
