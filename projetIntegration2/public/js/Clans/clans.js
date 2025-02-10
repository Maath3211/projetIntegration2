// JavaScript qui soit s'exécuter une fois la page chargée
document.addEventListener("DOMContentLoaded", function() {

    const canals = document.querySelectorAll('.canal');
    const divScrollable = document.querySelector(".contenuScrollable");

    // Pour montrer quel canal est actif actuellement
    canals.forEach(canal => {
        canal.addEventListener('click', function() {
            canals.forEach(c => c.classList.remove('active'));
            canal.classList.add('active');

            // Quand on change de canal de chat, on scroll jusqu'en bas automatiquement
            divScrollable.scrollTop = divScrollable.scrollHeight;
        });
    });

    // Déroulement automatique jusqu'en bas des messages
    divScrollable.scrollTop = divScrollable.scrollHeight;
});