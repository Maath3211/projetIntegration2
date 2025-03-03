document.addEventListener("DOMContentLoaded", function() {
    const categoriesParametres = document.querySelectorAll('.categorieParametre');

    // pour naviguer entre les catégories de paramètres
    categoriesParametres.forEach(categorie => {

        categorie.addEventListener('click', function(){

            if(categorie.classList.contains('canaux')){
                window.location.href = "canaux";
            } else if(categorie.classList.contains('membres')){
                window.location.href = "membres";
            }
        });
    });

    //pour montrer l'apercu de l'image quand l'utilisateur en choisi une avant de l'enregistrer
    const entreFichier = document.getElementById("imageClan");
    const apercuImage = document.querySelector(".parametresGeneraux img");

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

    document.querySelectorAll('.close-btn').forEach(bouton => {
        bouton.addEventListener('click', function(){
            bouton.parentElement.style.display = 'none';
        });
    });
});