// JavaScript qui soit s'exécuter une fois la page chargée
document.addEventListener("DOMContentLoaded", function() {

    const canals = document.querySelectorAll('.canal');
    const divScrollable = document.querySelector(".contenuScrollable");
    const renommer = document.getElementById('renommerCanal');
    const ajouter = document.getElementById('ajoutCanal');
    const supprimer = document.getElementById('confirmationSuppression')
    const formulaire = document.getElementById('formulaireClan');
    let classe = "";
    let canalARenommer = "";
    let _canal = '';
    let _categorie = '';

    // Déroulement automatique jusqu'en bas des messages
    divScrollable.scrollTop = divScrollable.scrollHeight;

    document.querySelectorAll('.annuler').forEach(bouton => {
        bouton.addEventListener('click', function() {
            bouton.parentElement.parentElement.parentElement.style.display = 'none';
        })
    })

    // ajouter les événements pour les boutons des fenêtres contextuelles
    renommer.querySelector('#confirmerRenommage').addEventListener('click', function(){
        nouveauNom = renommer.querySelector('input').value;
        formulaire.querySelector('.action').value = 'renommer';
        formulaire.querySelector('.requete').value = JSON.stringify({
            canal: _canal,
            canalARenommer: canalARenommer,
            nouveauNom: nouveauNom,
            categorie: _categorie,
        });

        formulaire.submit();
    });

    ajouter.querySelector('#confirmerAjout').addEventListener('click', function(){
        nouveauNom = ajouter.querySelector('input').value;
        formulaire.querySelector('.action').value = 'ajouter';
        formulaire.querySelector('.requete').value = JSON.stringify({
            canal: -1,
            canalARenommer: -1,
            nouveauNom: nouveauNom,
            categorie: _categorie,
        });
        
        formulaire.submit();
    });

    supprimer.querySelector('#confirmerSuppression').addEventListener('click', function(){
        formulaire.querySelector('.action').value = 'supprimer';
        formulaire.querySelector('.requete').value = JSON.stringify({
            canal: _canal,
            canalARenommer: -1,
            nouveauNom: '',
            categorie: -1,
        });

        formulaire.submit();
    });


    canals.forEach(canal => {

        // Pour montrer quel canal est actif actuellement
        canal.addEventListener('click', function() {
            canals.forEach(c => c.classList.remove('active'));
            canal.classList.add('active');

            // Quand on change de canal de chat, on scroll jusqu'en bas automatiquement
            divScrollable.scrollTop = divScrollable.scrollHeight;
        });

        canal.querySelector('.modifier').addEventListener('click', function() {


            classe = canal.querySelector('a div').textContent.trim();
            console.log(canal.querySelector('a').classList[0].split('canal_')[1]);
            _canal = canal.querySelector('a').classList[0].split('canal_')[1];
            _categorie = canal.parentElement.querySelector('div').classList[1].split('categorie_')[1];
            
            // afficher la fenêtre contextuelle pour renommer un canal
            renommer.querySelector('input').value = classe;
            renommer.style.display = 'flex';

            canalARenommer = classe;
        });

        canal.querySelector('.supprimer').addEventListener('click', function() {
            classe = canal.querySelector('a div').textContent.trim();
            _canal = canal.querySelector('a').classList[0].split('canal_')[1];

            // afficher la fenêtre contextuelle pour renommer un canal
            supprimer.style.display = 'flex';

            canalARenommer = classe;
        });
    });

    // ajouter les événements pour ajouter un canal à une catégorie
    document.querySelectorAll('.titreCategorieCanal i.fa-plus').forEach(categorie => {
        categorie.addEventListener('click', function() {
            ajouter.style.display = 'flex';
            
            _categorie = categorie.parentElement.classList[1].split('categorie_')[1].trim();
        })
    });

    document.querySelectorAll('.entreeNomCanal').forEach(entree => {
        entree.addEventListener('input', function(){
            valeur = this.value;
            messageErreur = this.parentElement.querySelector('.messageErreur');
    
            // Règle 1 : le canal ne doit pas dépasser les 50 caractères
            if(valeur.length > 50){
                messageErreur.textContent = "Le canal ne doit pas dépasser 50 caractères.";
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
    })
});