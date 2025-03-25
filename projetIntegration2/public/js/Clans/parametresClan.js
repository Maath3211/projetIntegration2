document.addEventListener("DOMContentLoaded", function() {
    const categoriesParametres = document.querySelectorAll('.categorieParametre');
    const supprimerClan = document.getElementById('confirmationSuppressionClan');
    //pour montrer l'apercu de l'image quand l'utilisateur en choisi une avant de l'enregistrer
    const entreFichier = document.getElementById("imageClan");
    const apercuImage = document.querySelector(".parametresGeneraux img");

    // pour naviguer entre les catégories de paramètres
    categoriesParametres.forEach(categorie => {

        categorie.addEventListener('click', function(){

            if(categorie.classList.contains('canaux')){
                window.location.href = "canaux";
            } else if(categorie.classList.contains('membres')){
                window.location.href = "membres";
            }

            if(categorie.classList.contains('supprimer')){
                supprimerClan.style.display = 'flex';
            }
        });
    });

    // pour montrer un aperçu de l'image du clan
    entreFichier.addEventListener("change", function(event) {
        //obtenir l'image
        const fichier = event.target.files[0];

        if(fichier) {
            const liseur = new FileReader();
            liseur.onload = function(e) {
                //changer la source de l'image
                apercuImage.src = e.target.result;
            };
            liseur.readAsDataURL(fichier);
        }
    });

    // confirmer la suppression du clan
    supprimerClan.querySelector('#confirmerSuppressionClan').addEventListener('click', function() {
        document.querySelector('#formulaireSuppressionClan').submit();
    });

    // pour annuler la suppression du clan
    supprimerClan.querySelector('.annuler').addEventListener('click', function() {
        this.parentElement.parentElement.parentElement.style.display = 'none';
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
        }
    });
});