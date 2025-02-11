document.addEventListener("DOMContentLoaded", function() {
    const categoriesSelectionnees = [];
    let categorieASupprimer = "";

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

    // si il confirme la suppression, cache la fenêtre de confirmation et la catégorie, puis on l'ajout à la liste des catégories à supprimer.
    document.getElementById('confirmer').addEventListener('click', function() {
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
    document.getElementById('annuler').addEventListener('click', function() {
        document.getElementById('confirmationSuppression').style.display = 'none';     
    });


    //obtenir les catégories à supprimer
    document.querySelectorAll('.categorie i.supprimer').forEach(icon => {
        icon.addEventListener('click', function(){
            document.getElementById('confirmationSuppression').style.display = 'flex';
            categorieASupprimer = this.parentElement.querySelector('div').textContent.trim();
        });
    });

});