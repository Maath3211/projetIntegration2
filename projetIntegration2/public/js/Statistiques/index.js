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



function showAddExerciseForm() {
    document.getElementById('addExerciseForm').classList.remove('hidden');
}

// Fonction pour enregistrer un exercice dans la base de données
function saveExercise() {
    let name = document.getElementById('exerciseName').value.toLowerCase();
    let score = document.getElementById('exerciseScore').value;

    if (name && score) {
        let formData = new FormData();
        formData.append('stats[0][nomStatistique]', name);
        formData.append('stats[0][score]', score);
        
        const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';

        // Add the CSRF token to the FormData instead of headers
        if (csrfToken) {
            formData.append('_token', csrfToken);
        }

        fetch('/statistiques/save', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken // Ajout du token dans les headers
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Server responded with status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Success:', data);
            if (data.message) {
                // Détecter si c'est un exercice de course
                let isRunning = name.includes('course') || name.includes('run') || name.includes('marathon') || name.includes('marche') || 
                                name.includes('sprint') || name.includes('jogging') || name.includes('trail') ||
                                name.includes('velo') || name.includes('bike') || name.includes('cycling');
                // Choisir l'unité de mesure
                let unitOptions = isRunning
                    ? `<button class="bouton" onclick="convertRunUnit(this, 'km')">Km</button>
                       <button class="bouton" onclick="convertRunUnit(this, 'Miles')">Miles</button>`
                    : `<button class="bouton" onclick="convertWeightUnit(this, 'lbs')">Lbs</button>
                       <button class="bouton" onclick="convertWeightUnit(this, 'kg')">Kg</button>`;

                let newExercise = document.createElement('div');
                newExercise.classList.add('statRow');
                newExercise.id = `exercise-${data.id}`;
                newExercise.innerHTML = `
                    <span>${name} : ${score} ${isRunning ? 'km' : 'lbs'}</span>
                    <div class="flex space-x-2">
                        ${unitOptions}
                        <a href="/statistique/graphiqueExercice/${data.id}" class="text-gray-400">Voir mon graphique</a>
                    </div>
                `;

                document.querySelector('.statContainer').appendChild(newExercise);
                document.getElementById('exerciseName').value = '';
                document.getElementById('exerciseScore').value = '';
                document.getElementById('addExerciseForm').classList.add('hidden');
                console.log('Exercise added successfully');
                
                // Wait a moment before reloading to ensure data is saved
                setTimeout(() => {
                    location.reload();
                }, 1000);
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

function convertRunUnit(button, unit) {
    let exerciseRow = button.closest('.statRow');
    let scoreSpan = exerciseRow.querySelector('span');
    
    let textParts = scoreSpan.innerHTML.split(': ');
    let scoreText = textParts[1].split(' ')[0];
    let score = parseFloat(scoreText);

    if (!isNaN(score)) {
        if (unit === 'km' && !scoreSpan.innerHTML.includes('km')) {
            let km = (score * 1.60934).toFixed(2); // Convert miles to kilometers and round to 2 decimal places
            scoreSpan.innerHTML = `${textParts[0]} : ${km} km`;
        } else if (unit === 'miles' && !scoreSpan.innerHTML.includes('miles')) {
            let miles = (score / 1.60934).toFixed(2); // Convert kilometers to miles
            scoreSpan.innerHTML = `${textParts[0]} : ${miles} miles`;
        }
    }
}
function convertWeightUnit(button, unit) {
    let exerciseRow = button.closest('.statRow');
    let scoreSpan = exerciseRow.querySelector('span');
    
    let textParts = scoreSpan.innerHTML.split(': ');
    let scoreText = textParts[1].split(' ')[0];
    let score = parseFloat(scoreText);

    if (!isNaN(score)) {
        if (unit === 'kg' && !scoreSpan.innerHTML.includes('kg')) {
            let kg = (score * 0.453592).toFixed(2); // Convert lbs to kg
            scoreSpan.innerHTML = `${textParts[0]} : ${kg} kg`;
        } else if (unit === 'lbs' && !scoreSpan.innerHTML.includes('lbs')) {
            let lbs = (score / 0.453592).toFixed(2); // Convert kg to lbs
            scoreSpan.innerHTML = `${textParts[0]} : ${lbs} lbs`;
        }
    }
}

