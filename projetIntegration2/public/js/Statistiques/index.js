function convertWeight(unit) {
    let poidsSpan = document.getElementById('poidsValue');
    let lbs = parseFloat(poidsSpan.getAttribute('data-lbs'));

    if (!isNaN(lbs)) {
        if (unit === 'kg') {
            let kg = (lbs * 0.453592).toFixed(2);
            poidsSpan.innerHTML = ` Votre poids le plus bas : ${kg} kg`;
        } else {
            poidsSpan.innerHTML = ` Votre poids le plus bas : ${lbs} lbs`;
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
                        <a href="{{route('statistique.graphiqueExercice', [$stat->id])}}" class="text-gray-400">Voir mon graphique</a>
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
