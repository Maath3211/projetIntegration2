document.addEventListener("DOMContentLoaded", function() {
    const supprimerClan = document.getElementById('confirmationSuppressionClan');
    const categoriesASupprimer = [];
    const categoriesRenommees = {};
    const categoriesAAjouter = [];
    let categorieASupprimer = "";
    let categorieARenommer = "";

    const categoriesParametres = document.querySelectorAll('.categorieParametre');

    // pour naviguer entre les catégories de paramètres
    categoriesParametres.forEach(categorie => {
        categorie.addEventListener('click', function(){
            if(categorie.classList.contains('general')){
                window.location.href = "general";
            } else if(categorie.classList.contains('membres')){
                window.location.href = "membres";
            }

            if(categorie.classList.contains('supprimer')){
                supprimerClan.style.display = 'flex';
            }
        });
    });

    // pour fermer une fenêtre contextuelle de notification
    document.querySelectorAll('.close-btn').forEach(bouton => {
        bouton.addEventListener('click', function(){
            bouton.parentElement.style.display = 'none';
        });
    });


    /*
    FRONT END QUI MARCHE POUR TOUTES LES FENÊTRES CONTEXTUELLES
    */

    // Vérification de l'input au fur et à mesure
    document.querySelectorAll('.entreeNomCategorie').forEach(entree => {
        entree.addEventListener('input', function(){
            valeur = this.value;
            messageErreur = this.parentElement.querySelector('.messageErreur');
    
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
    });

    // Si il annule, on cache la fenêtre contextuelle
    document.querySelectorAll('.annuler').forEach(bouton => {
        bouton.addEventListener('click', function() {
            this.parentElement.parentElement.parentElement.style.display = 'none';
        });
    });


    /*
    GESTION DU FRONT END DE LA SUPPRESSION D'UNE CATÉGORIE DE CANAL
    */

    // si il confirme la suppression, cache la fenêtre de confirmation et la catégorie, puis on l'ajout à la liste des catégories à supprimer.
    document.getElementById('confirmerSuppression').addEventListener('click', function() {
        document.getElementById('confirmationSuppression').style.display = 'none';
        document.querySelector(`.${categorieASupprimer}`).style.display = 'none';

        //ajouter la catégorie dans les "à supprimer"
        if(!categoriesASupprimer.includes(categorieASupprimer)){
            categoriesASupprimer.push(categorieASupprimer);
        }

        // mettre à jour la liste de catégories à supprimer pour les passer dans le formulaire
        document.getElementById('categoriesASupprimer').value = categoriesASupprimer.join(',');
    });

    //obtenir les catégories à supprimer
    document.querySelectorAll('.categorie i.supprimer').forEach(icon => {
        icon.addEventListener('click', function(){
            document.getElementById('confirmationSuppression').style.display = 'flex';
            categorieASupprimer = this.parentElement.classList[1].trim();
        });
    });


    /*
    GESTION DU FRONT END DU RENOMMAGE D'UNE CATÉGORIE DE CANAL
    */
   
    // Afficher la fenêtre contextuelle de renommage de catégorie
    document.querySelectorAll('.categorie i.renommer').forEach(icon => {
        icon.addEventListener('click', function(){
            //Récupérer la catégorier à renommer
            document.getElementById('modificationNomCategorie').style.display = 'flex';

            const categorieCanal = this.parentElement.classList[1].trim();
            categorieARenommer = categorieCanal;

            // vérifier si une modification a déjà été faite pour cette catégorie depuis le chargement de la page
            let dernierNom = "";
            if(categoriesRenommees[categorieARenommer])
                dernierNom = categoriesRenommees[categorieARenommer].split(';')[1];
            else
                dernierNom = categorieARenommer;
            

            // Pré-remplir le champ avec le nom actuel
            document.querySelector('.entreeNomCategorie').value = dernierNom;
        });
    });

    // Quand il clique sur "Confirmer" dans la fenêtre contextuelle
    document.getElementById('confirmerRenommage').addEventListener('click', function() {
        // Cacher la fenêtre contextuelle
        document.getElementById('modificationNomCategorie').style.display = 'none';

        // Récupérer le nom actuel de la catégorie ainsi que le nouveau nom
        const nouveauNom = document.querySelector('.entreeNomCategorie').value.trim();

        // Si le nouveau nom est le même que l'ancien nom, on fait rien.
        if(nouveauNom === categorieARenommer){
            console.log('Nom de catégorie identique. Aucun changement n\'a été effectué.');
            return;
        }
        if(!(nouveauNom.length > 50) && /^[A-Za-z\u00C0-\u00FF-]+$/.test(nouveauNom) && nouveauNom.length !== 0){
            categoriesRenommees[categorieARenommer] = categorieARenommer + ";" + nouveauNom;
            document.querySelector('.' + categorieARenommer + " div").textContent = nouveauNom;
            console.log("Valeur ajoutée : ", categorieARenommer + " : " + nouveauNom);

            document.getElementById('categoriesARenommer').value = Object.values(categoriesRenommees);
        }
    });

    /*
    GESTION DU FRONT END DE L'AJOUT D'UNE CATÉGORIE DE CANAL
    */

    // afficher la fenêtre contextuelle d'ajout d'une catégorie
    document.querySelector('.ajouterCategorie').addEventListener('click', function(){
        document.getElementById('ajoutCategorie').style.display = 'flex';
    });

    // quand il confirme l'ajout d'une catégorie
    document.getElementById('confirmerAjout').addEventListener('click', function(){
        // si une valeur a été entrée
        if(this.parentElement.parentElement.querySelector('.entreeNomCategorie').value !== ''){
            
            // Cacher la valeur entrée
            document.getElementById('ajoutCategorie').style.display = 'none';
            let valeurEntree = this.parentElement.parentElement.querySelector('.entreeNomCategorie').value;


            // ajouter la catégorie à la liste de catégories affichée
            document.querySelector('.parametresCanal').innerHTML = document.querySelector('.parametresCanal').innerHTML + `<div class="categorie ${valeurEntree}">
                            <i class="fa-solid fa-x supprimer"></i>
                            <i class="fa-solid fa-pen renommer"></i>
                            <div>${valeurEntree}</div>
                        </div>`;

            // ajouter la catégorie à la liste de catégories qu'on va ajouter plus tard
            categoriesAAjouter.push(valeurEntree); 
            document.getElementById('categoriesAAjouter').value = categoriesAAjouter.join(',');


            // Ajouter les événements pour cette nouvelle catégorie
            document.querySelector(`.parametresCanal .${valeurEntree} .supprimer`).addEventListener('click', function(){
                document.getElementById('confirmationSuppression').style.display = 'flex';
                categorieASupprimer = this.parentElement.classList[1].trim();
            });

            // Ajouter les événements pour cette nouvelle catégorie
            document.querySelector(`.parametresCanal .${valeurEntree} .renommer`).addEventListener('click', function(){
                //Récupérer la catégorier à renommer
                document.getElementById('modificationNomCategorie').style.display = 'flex';

                const categorieCanal = this.parentElement.classList[1].trim();
                categorieARenommer = categorieCanal;

                // vérifier si une modification a déjà été faite pour cette catégorie depuis le chargement de la page
                let dernierNom = "";
                if(categoriesRenommees[categorieARenommer])
                    dernierNom = categoriesRenommees[categorieARenommer].split(';')[1];
                else
                    dernierNom = categorieARenommer;
                

                // Pré-remplir le champ avec le nom actuel
                document.querySelector('.entreeNomCategorie').value = dernierNom;    
            });

            // effacer la valeur entrée initialement
            this.parentElement.parentElement.querySelector('.entreeNomCategorie').value = '';
        }
    });

    // quand il confirme la suppression d'une catégorie
    supprimerClan.querySelector('#confirmerSuppressionClan').addEventListener('click', function() {
        document.querySelector('#formulaireSuppressionClan').submit();
    });
    
    // pour fermer une fenêtre contextuelle de message d'erreur ou de succès
    document.querySelectorAll('.close-btn').forEach(bouton => {
        bouton.addEventListener('click', function(){
            bouton.parentElement.style.display = 'none';
        });
    });

    // pour fermer les fenêtres contextuelles lorsqu'il appuie sur Esc
    document.addEventListener('keydown', function(event){
        if (event.key === 'Escape'){
            if(supprimerClan.style.display == 'flex')
                supprimerClan.style.display = 'none';
            if(document.getElementById('confirmationSuppression').style.display == 'flex')
                document.getElementById('confirmationSuppression').style.display = 'none';
            if(document.getElementById('modificationNomCategorie').style.display == 'flex')
                document.getElementById('modificationNomCategorie').style.display = 'none';
            if(document.getElementById('ajoutCategorie').style.display == 'flex')
                document.getElementById('ajoutCategorie').style.display = 'none';
        }
    });

});