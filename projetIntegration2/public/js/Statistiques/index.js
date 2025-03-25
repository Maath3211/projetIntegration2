function convertirPoids(unite) {
    let poidsSpan = document.getElementById('poidsValue');
    let lbs = parseFloat(poidsSpan.getAttribute('data-lbs'));

    if (!isNaN(lbs)) {
        if (unite === 'kg') {
            let kg = (lbs * 0.453592).toFixed(2);
            poidsSpan.innerHTML = ` Votre poids le plus bas : ${kg} kg`;
        } else {
            poidsSpan.innerHTML = ` Votre poids le plus bas : ${lbs} lbs`;
        }
    }
}



function afficherAjouterExerciceFormulaire() {
    document.getElementById('ajouterExerciceFormulaire').classList.remove('hidden');
}

// Fonction pour enregistrer un exercice dans la base de données
function sauvegarderExercice() {
    let nom = document.getElementById('exerciseNom').value.toLowerCase();
    let score = document.getElementById('exerciseScore').value;
    console.log(nom, score);
    if (nom && score) {
        let formData = new FormData();
        formData.append('stats[0][nomStatistique]', nom);
        formData.append('stats[0][score]', score);
        
        const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';

        
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
                let course = nom.includes('course') || nom.includes('run') || nom.includes('marathon') || nom.includes('marche') || 
                                nom.includes('sprint') || nom.includes('jogging') || nom.includes('trail') ||
                                nom.includes('velo') || nom.includes('bike') || nom.includes('cycling');
                // Choisir l'uniteé de mesure
                let uniteOptions = course
                    ? `<button class="bouton" onclick="convertirCourseUnite(this, 'km')">Km</button>
                       <button class="bouton" onclick="convertirCourseUnite(this, 'Miles')">Miles</button>`
                    : `<button class="bouton" onclick="convertirPoidsUnite(this, 'lbs')">Lbs</button>
                       <button class="bouton" onclick="convertirPoidsUnite(this, 'kg')">Kg</button>`;

                let nouveauExercise = document.createElement('div');
                nouveauExercise.classList.add('statRow');
                nouveauExercise.id = `exercise-${data.id}`;
                nouveauExercise.innerHTML = `
                    <span>${nom} : ${score} ${course ? 'km' : 'lbs'}</span>
                    <div class="flex space-x-2">
                        ${uniteOptions}
                        <a href="/statistique/graphiqueExercice/${data.id}" class="text-gray-400">Voir mon graphique</a>
                    </div>
                `;

                document.querySelector('.statContainer').appendChild(nouveauExercise);
                document.getElementById('exerciseNom').value = '';
                document.getElementById('exerciseScore').value = '';
                document.getElementById('ajouterExerciceFormulaire').classList.add('hidden');
                console.log('Exercise ajoute avec succes !');
                
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
function supprimerExercise(id) {
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

function annulerFormulaire() {
    // Cacher le formulaire
    document.getElementById('ajouterExerciceFormulaire').classList.add('hidden');
    
    // Réinitialiser les champs du formulaire
    document.getElementById('exerciseNom').value = '';
    document.getElementById('exerciseScore').value = '';
}

function convertirCourseUnite(button, unite) {
    let exerciseRow = button.closest('.statRow');
    let scoreSpan = exerciseRow.querySelector('span');
    
    let texteParti = scoreSpan.innerHTML.split(': ');
    let scoreText = texteParti[1].split(' ')[0];
    let score = parseFloat(scoreText);

    if (!isNaN(score)) {
        if (unite === 'km' && !scoreSpan.innerHTML.includes('km')) {
            let km = (score * 1.60934).toFixed(2); // Convert miles to kilometers and round to 2 decimal places
            scoreSpan.innerHTML = `${texteParti[0]} : ${km} km`;
        } else if (unite === 'miles' && !scoreSpan.innerHTML.includes('miles')) {
            let miles = (score / 1.60934).toFixed(2); // Convert kilometers to miles
            scoreSpan.innerHTML = `${texteParti[0]} : ${miles} miles`;
        }
    }
}
function convertirPoidsUnite(button, unite) {
    let exerciseRow = button.closest('.statRow');
    let scoreSpan = exerciseRow.querySelector('span');
    
    let texteParti = scoreSpan.innerHTML.split(': ');
    let scoreText = texteParti[1].split(' ')[0];
    let score = parseFloat(scoreText);

    if (!isNaN(score)) {
        if (unite === 'kg' && !scoreSpan.innerHTML.includes('kg')) {
            let kg = (score * 0.453592).toFixed(2); 
            scoreSpan.innerHTML = `${texteParti[0]} : ${kg} kg`;
        } else if (unite === 'lbs' && !scoreSpan.innerHTML.includes('lbs')) {
            let lbs = (score / 0.453592).toFixed(2);
            scoreSpan.innerHTML = `${texteParti[0]} : ${lbs} lbs`;
        }
    }
}

