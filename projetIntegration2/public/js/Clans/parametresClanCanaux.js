document.addEventListener("DOMContentLoaded", function() {
    const categoriesASupprimer = [];
    const categoriesRenommees = {};
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
        });
    });

    /*
    GESTION DU FRONT END DE LA SUPPRESSION D'UNE CATÉGORIE DE CANAL
    */

    //obtenir les catégories à supprimer
    document.querySelectorAll('.categorie i.supprimer').forEach(icon => {
        icon.addEventListener('click', function(){
            document.getElementById('confirmationSuppression').style.display = 'flex';
            categorieASupprimer = this.parentElement.classList[1].trim();
        });
    });

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

    // cacher la fenêtre de confirmation si il annule le tout
    document.getElementById('annulerSuppression').addEventListener('click', function() {
        document.getElementById('confirmationSuppression').style.display = 'none';     
    });





    /*
    GESTION DU FRONT END DU RENOMMAGE D'UNE CATÉGORIE DE CANAL
    */

    document.querySelector('.entreeNomCategorie').addEventListener('input', function(){
        valeur = this.value;
        messageErreur = document.querySelector('.messageErreur');

        // Règle 1 : la catégorie ne doit pas dépasser les 50 caractères
        if(valeur.length > 50){
            messageErreur.textContent = "La catégorie ne doit pas dépasser 50 caractères.";
            messageErreur.style.display = "block";
            this.style.borderColor = 'red';
        }
        // Règle 2 : pas de nombres ou de symboles, juste les caractères UTF-8 et les traits (-) sont acceptés.
        else if (!/^[A-Za-z\u00C0-\u00FF-]+$/.test(valeur)){
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

    //obtenir les catégories à renommer
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

    // Si il annule, on cache la fenêtre contextuelle
    document.getElementById('annulerRenommage').addEventListener('click', function() {
        document.getElementById('modificationNomCategorie').style.display = 'none';
    });


    // Si il enregistre la modification
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

        // Ajouter la catégorie et sa nouvelle valeur ou la mettre la jour si elle existe déjà
        categoriesRenommees[categorieARenommer] = categorieARenommer + ";" + nouveauNom;
        document.querySelector('.' + categorieARenommer + " div").textContent = nouveauNom;
        console.log("Valeur ajoutée : ", categorieARenommer + " : " + nouveauNom);

        document.getElementById('categoriesARenommer').value = Object.values(categoriesRenommees);
    });
});