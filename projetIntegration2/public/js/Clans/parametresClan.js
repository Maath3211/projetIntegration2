document.addEventListener("DOMContentLoaded", function() {

    const categoriesParametres = document.querySelectorAll('.categorieParametre');

    // pour montrer quelle catégorie de canal est active actuellement
    categoriesParametres.forEach(categorie => {
        categorie.addEventListener('click', function(){
            categoriesParametres.forEach(c => c.classList.remove('active'));

            categorie.classList.add('active');
        });
    });
});