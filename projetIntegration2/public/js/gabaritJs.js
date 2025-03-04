document.addEventListener('DOMContentLoaded', function(){
    const creationClan = document.querySelector('#fenetreAjoutClan');
    const boutonTeleverserImage = creationClan.querySelector('#selectionnerImage');
    const entreeImageCachee = creationClan.querySelector('#entreeImageCachee');
    const apercuImage = creationClan.querySelector('.apercuImage');

    // afficher la fenêtre contextuelle
    document.querySelector('.creerClan').addEventListener('click', function(){
        creationClan.style.display = 'flex';
    });

    // cacher la fenêtre contextuelle si il annule
    creationClan.querySelector('.annuler').addEventListener('click', function(){
        creationClan.style.display = 'none';
    })

    creationClan.querySelector('.entreeNomClan').addEventListener('input', function() {
        valeur = this.value;
        messageErreur = creationClan.querySelector('.messageErreur');
        
        // Règle 1 : la catégorie ne doit pas dépasser les 50 caractères
        if(valeur.length > 50){
            messageErreur.textContent = "La catégorie ne doit pas dépasser 50 caractères.";
            messageErreur.style.display = "block";
            this.style.borderColor = 'red';
        }
        // Règle 2 : pas de nombres ou de symboles, juste les caractères UTF-8 et les traits (-) sont acceptés.
        else if (!/^[A-Za-z\u00C0-\u00FF-]+$/.test(valeur) && valeur.length !== 0){
            messageErreur.textContent = "Seulement les lettre (UTF-8) et les traits (-) sont permis."
            messageErreur.style.display = "block";
            this.style.borderColor = 'red';
        }
        // Si tout va bien, on reset le tout
        else {
            messageErreur.style.display = "none";
            this.style.borderColor = '';
        }
    });

    // afficher la fenêtre de sélection d'image
    boutonTeleverserImage.addEventListener('click', function(){
        entreeImageCachee.click();
    });

    // afficher l'apercu de l'image sélectionnée
    entreeImageCachee.addEventListener('change', function(e){
        const fichier = e.target.files[0];
        const liseur = new FileReader();

        if(fichier){
            liseur.onload = (ev) => {
                apercuImage.src = ev.target.result;
            }
        }

        liseur.readAsDataURL(fichier);
    });

    document.querySelectorAll('.close-btn').forEach(bouton => {
        bouton.addEventListener('click', function(){
            bouton.parentElement.style.display = 'none';
        });
    });

});