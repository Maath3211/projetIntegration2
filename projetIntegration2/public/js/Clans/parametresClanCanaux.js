document.addEventListener("DOMContentLoaded", function() {
    const categoriesSelectionnees = [];
    const categoriesRenommage = [];
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
            categorieASupprimer = this.parentElement.querySelector('div').textContent.trim();
        });
    });

    // si il confirme la suppression, cache la fenêtre de confirmation et la catégorie, puis on l'ajout à la liste des catégories à supprimer.
    document.getElementById('confirmerSuppression').addEventListener('click', function() {
        document.getElementById('confirmationSuppression').style.display = 'none';
        document.querySelector(`.${categorieASupprimer}`).style.display = 'none';

        //ajouter la catégorie dans les "à supprimer"
        if(!categoriesSelectionnees.includes(categorieASupprimer)){
            categoriesSelectionnees.push(categorieASupprimer);
        }

        // mettre à jour la liste de catégories à supprimer pour les passer dans le formulaire
        document.getElementById('categoriesSelectionnees').value = categoriesSelectionnees.join(',');
    });

    // cacher la fenêtre de confirmation si il annule le tout
    document.getElementById('annulerSuppression').addEventListener('click', function() {
        document.getElementById('confirmationSuppression').style.display = 'none';     
    });





    /*
    GESTION DU FRONT END DU RENOMMAGE D'UNE CATÉGORIE DE CANAL
    */

    //obtenir les catégories à renommer
    document.querySelectorAll('.categorie i.renommer').forEach(icon => {
        icon.addEventListener('click', function(){
            document.getElementById('modificationNomCategorie').style.display = 'flex';
            categorieARenommer = this.parentElement.querySelector('div').textContent.trim();
            document.querySelector('.texteConfirmation input').value = categorieARenommer;
        });
    });

    document.getElementById('annulerRenommage').addEventListener('click', function() {
        document.getElementById('modificationNomCategorie').style.display = 'none';
    })

    document.getElementById('confirmerRenommage').addEventListener('click', function() {
        document.getElementById('modificationNomCategorie').style.display = 'none';

        valeurEnregistree = (categorieARenommer + ";" + document.querySelector('.texteConfirmation input').value);
        if(!categoriesRenommage.includes(valeurEnregistree)){
            categoriesRenommage.push(valeurEnregistree);
            console.log("Valeur ajoutée : ", valeurEnregistree);
        }

        document.getElementById('categoriesARenommer').value = categoriesRenommage.join(',');
    })

});