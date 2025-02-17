document.addEventListener("DOMContentLoaded", function() {
    const categoriesParametres = document.querySelectorAll('.categorieParametre');

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
        });
    });

    //obtenir les membres à supprimer
    document.querySelectorAll('.membre i.supprimer').forEach(icon => {
        icon.addEventListener('click', function(){
            //obtenir la catégorie à ajouter dans les "à supprimer"
            const texteCategorie = this.parentElement.querySelector('div').textContent.trim();

            //ajouter la catégorie dans les "à supprimer"
            if(!categoriesSelectionnees.includes(texteCategorie)){
                categoriesSelectionnees.push(texteCategorie);
            }

            document.getElementById('categoriesSelectionnees').value = categoriesSelectionnees.join(',')

        });
    });

    document.querySelector('.copier').addEventListener('click', function(){
        let texte = document.querySelector('.rangeeInviter div:last-of-type').textContent.trim();
        console.log(texte);

        navigator.clipboard.writeText(texte).then(function() {
            alert("lien d'invitation copié.");
        }).catch(function(err) {
            console.error("erreur lors de la copie du lien d'invitation.");
        })
    });

});