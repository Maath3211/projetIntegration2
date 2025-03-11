document.addEventListener('DOMContentLoaded', function(){
    const creationClan = document.querySelector('#fenetreAjoutClan');
    const boutonTeleverserImage = creationClan.querySelector('#selectionnerImage');
    const boutonConfirmerCreationClan = document.getElementById('confirmerAjoutClan');
    const entreeImageCachee = creationClan.querySelector('#entreeImageCachee');
    const apercuImage = creationClan.querySelector('.apercuImage');
    const srcParDefaut = apercuImage.src;

    // afficher la fenêtre contextuelle pour créer un clan
    document.querySelector('.creerClan').addEventListener('click', function(){
        creationClan.style.display = 'flex';
        apercuImage.src = srcParDefaut;
    });

    // cacher la fenêtre contextuelle si il annule
    creationClan.querySelector('.annuler').addEventListener('click', function(){
        creationClan.style.display = 'none';
    })

    // afficher au fur et à mesure si son nom de clan va être accepté
    creationClan.querySelector('.entreeNomClan').addEventListener('input', function() {
        valeur = this.value;
        messageErreur = creationClan.querySelector('.messageErreur');
        
        // Règle 1 : la catégorie ne doit pas dépasser les 50 caractères
        if(valeur.length > 50){
            messageErreur.textContent = "Le nom du clan ne doit pas dépasser 50 caractères.";
            messageErreur.style.display = "block";
            messageErreur.style.paddingLeft = "15px";
            this.style.borderColor = 'red';
        }
        // Règle 2 : pas de nombres ou de symboles, juste les caractères UTF-8 et les traits (-) sont acceptés.
        else if (!/^[A-Za-z\u00C0-\u00FF-\s]+$/.test(valeur) && valeur.length !== 0){
            messageErreur.textContent = "Seulement les lettres (UTF-8) et les traits (-) sont permis."
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

    // Soumettre le formulaire lorsqu'il confirme le tout. 
    // Obligé de le soumettre ainsi car on a plusieurs formulaires et ça soumets un autre lorsqu'on appuie dessus sinon.
    boutonConfirmerCreationClan.addEventListener('click', function() {
        document.getElementById('formulaireCreationClan').submit();
    });

    // pour pouvoir fermer les fenêtres contextuelles d'erreur ou de messages.
    document.querySelectorAll('.close-btn').forEach(bouton => {
        bouton.addEventListener('click', function(){
            bouton.parentElement.style.display = 'none';
        });
    });

    // pour pouvoir fermer les fenêtres contextuelles en appuyant sur Esc
    document.addEventListener('keydown', function(event){
        if (event.key === 'Escape'){
            if(creationClan.style.display == 'flex')
                creationClan.style.display = 'none';
        }
    });

});