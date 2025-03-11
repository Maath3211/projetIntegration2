document.addEventListener("DOMContentLoaded", function() {
    const categoriesParametres = document.querySelectorAll('.categorieParametre');
    const supprimer = document.getElementById('confirmationSuppressionMembre');
    const supprimerClan = document.getElementById('confirmationSuppressionClan');
    let membreSelectionnes = [];
    let _membre = "";

    // pour montrer quelle catégorie de canal est active actuellement
    categoriesParametres.forEach(categorie => {

        categorie.addEventListener('click', function(){
            categoriesParametres.forEach(c => c.classList.remove('actif'));

            categorie.classList.add('actif');
            if(categorie.classList.contains('canaux')){
                window.location.href = "canaux";
            } else if(categorie.classList.contains('general')){
                window.location.href = "general";
            }

            if(categorie.classList.contains('supprimer')){
                supprimerClan.style.display = 'flex';
            }
        });
    });

    // fermer un fenêtre contextuelle si on clique sur annuler
    document.querySelectorAll('.annuler').forEach(bouton => {
        bouton.addEventListener('click', function(){
            bouton.parentElement.parentElement.parentElement.style.display = 'none';
        });
    });

    // confirmer la suppression
    supprimer.querySelector('#confirmerSuppression').addEventListener('click', function() {
        if(!membreSelectionnes.includes(_membre.classList[1].split('membre_')[1])){
            membreSelectionnes.push(_membre.classList[1].split('membre_')[1]);
            _membre.style.display = 'none';
        }

        document.querySelector('input#membresASupprimer').value = membreSelectionnes.join(';');
        supprimer.style.display = 'none';
    });

    // obtenir les membres à supprimer
    document.querySelectorAll('.membre i.supprimer').forEach(icon => {
        icon.addEventListener('click', function(){
            //obtenir la catégorie à ajouter dans les "à supprimer"
            _membre = this.parentElement;
            supprimer.style.display = 'flex';
        });
    });

    // copier le lien d'invitation au clan
    document.querySelector('.copier').addEventListener('click', function(){
        let texte = document.querySelector('.rangeeInviter div:last-of-type').textContent.trim();
        console.log(texte);

        navigator.clipboard.writeText(texte).then(function() {
            alert("lien d'invitation copié.");
        }).catch(function(err) {
            console.error("erreur lors de la copie du lien d'invitation.");
        })
    });

    // confirmer la suppression du clan
    supprimerClan.querySelector('#confirmerSuppressionClan').addEventListener('click', function() {
        document.querySelector('#formulaireSuppressionClan').submit();
    });

    // fermer une fenêtre contextuelle de message d'erreur ou de succès
    document.querySelectorAll('.close-btn').forEach(bouton => {
        bouton.addEventListener('click', function(){
            bouton.parentElement.style.display = 'none';
        });
    });

    // fermer les fenêtres contextuelles lorsqu'il appuie sur Esc
    document.addEventListener('keydown', function(event){
        if (event.key === 'Escape'){
            if(supprimer.style.display == 'flex')
                supprimer.style.display = 'none';
            if(supprimerClan.style.display == 'flex')
                supprimerClan.style.display = 'none';
        }
    });

});