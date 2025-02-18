@extends('Layouts.app')
@section('titre', 'Clans')

@section('style')
    <link rel="stylesheet" style="text/css" href="{{asset('css/Clans/clans.css')}}">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">

    <!-- Pas le choix de mettre le style de .conteneurImage comme ça vu que l'image du clan change et c'est une image en "background" et non directement <img> -->
    <style>
        .conteneurImage{
            background-image: url('{{ asset('img/workoutMasterLogo.jpg') }}');
            background-size: cover;
            background-image-opacity: 0.5;
            background-position: center center;
            width: 100%;
            height: 150px;
            opacity: 0.5;
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            padding: 15px;
            border-bottom: 2px solid rgba(255, 255, 255, 1);
        }
    </style>
@endsection()

@section('contenu')

<div class="contenuPrincipal">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 colonneCanaux">
                <div class="container">
                    <div class="column"> <!-- column est en anglais parce que c'est le nom de la classe bootstrap c'est pas mon choix -->
                        <div class="conteneurImage">
                            <div class="texteSurImage">Workout Master</div>
                            {{-- <div><a href="{{ route('clan.parametres', ['id' => $clan->id]) }}"><i class="fa-solid fa-ellipsis"></i></a></div> --}}
                            <div><a href="{{ route('clan.parametres', ['id' => 1]) }}"><i class="fa-solid fa-ellipsis"></i></a></div>
                        </div>
                        <div class="conteneurCanaux">
                            <div class="categorieCanal">
                                <div class="titreCategorieCanal categorie_1">
                                    <div>
                                        <i class="fa-solid fa-minus"></i>
                                        Général
                                    </div>
                                    <i class="fa-solid fa-plus fa-xs"></i>
                                </div>

                                <div class="canal">
                                    <a href="/clan/{{$id}}/bienvenue" class="canal_1">
                                        <div>
                                            <i class="fa-solid fa-hashtag"></i>
                                            bienvenue
                                        </div>
                                    </a>
                                    <div class="iconesModificationCanal">
                                        <i class="fa-solid fa-pen modifier"></i>
                                        <i class="fa-solid fa-x supprimer"></i>
                                    </div>
                                </div>
                                <div class="canal">
                                    <a href="/clan/{{$id}}/annonces" class="canal_2">
                                        <div>
                                            <i class="fa-solid fa-hashtag"></i>
                                            annonces
                                        </div>
                                    </a>
                                    <div class="iconesModificationCanal">
                                        <i class="fa-solid fa-pen modifier"></i>
                                        <i class="fa-solid fa-x supprimer"></i>
                                    </div>
                                </div>
                                <div class="canal">
                                    <a href="/clan/{{$id}}/règles-et-informations" class="canal_3">
                                        <div>
                                            <i class="fa-solid fa-hashtag"></i>
                                            règles-et-informations
                                        </div>
                                    </a>
                                    <div class="iconesModificationCanal">
                                        <i class="fa-solid fa-pen modifier"></i>
                                        <i class="fa-solid fa-x supprimer"></i>
                                    </div>
                                </div>
                                <div class="canal">
                                    <a href="/clan/{{$id}}/introductions" class="canal_4">
                                        <div>
                                            <i class="fa-solid fa-hashtag"></i>
                                            introductions
                                        </div>
                                    </a>
                                    <div class="iconesModificationCanal">
                                        <i class="fa-solid fa-pen modifier"></i>
                                        <i class="fa-solid fa-x supprimer"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="categorieCanal">
                                <div class="titreCategorieCanal categorie_2">
                                    <div>
                                        <i class="fa-solid fa-minus"></i>
                                        Aide
                                    </div>
                                    <i class="fa-solid fa-plus fa-xs"></i>
                                </div>

                                <div class="canal">
                                    <a href="/clan/{{$id}}/trucs-et-astuces" class="canal_5">
                                        <div>
                                            <i class="fa-solid fa-hashtag"></i>
                                            trucs-et-astuces
                                        </div>
                                    </a>
                                    <div class="iconesModificationCanal">
                                        <i class="fa-solid fa-pen modifier"></i>
                                        <i class="fa-solid fa-x supprimer"></i>
                                    </div>
                                </div>
                                <div class="canal">
                                    <a href="/clan/{{$id}}/plan-entrainement"  class="canal_6">
                                        <div>
                                            <i class="fa-solid fa-hashtag"></i>
                                            plan-entrainement
                                        </div>
                                    </a>
                                    <div class="iconesModificationCanal">
                                        <i class="fa-solid fa-pen modifier"></i>
                                        <i class="fa-solid fa-x supprimer"></i>
                                    </div>
                                </div>
                                <div class="canal">
                                    <a href="/clan/{{$id}}/images-progrès" class="canal_7">
                                        <div>
                                            <i class="fa-solid fa-hashtag"></i>
                                            images-progrès
                                        </div>
                                    </a>
                                    <div class="iconesModificationCanal">
                                        <i class="fa-solid fa-pen modifier"></i>
                                        <i class="fa-solid fa-x supprimer"></i>
                                    </div>
                                </div>
                                <div class="canal">
                                    <a href="/clan/{{$id}}/nutrition" class="canal_8">
                                        <div>
                                            <i class="fa-solid fa-hashtag"></i>
                                            nutrition
                                        </div>
                                    </a>
                                    <div class="iconesModificationCanal">
                                        <i class="fa-solid fa-pen modifier"></i>
                                        <i class="fa-solid fa-x supprimer"></i>
                                    </div>
                                </div>
                                <div class="canal">
                                    <a href="/clan/{{$id}}/efforts-journaliers" class="canal_9">
                                        <div>
                                            <i class="fa-solid fa-hashtag"></i>
                                            efforts-journaliers
                                        </div>
                                    </a>
                                    <div class="iconesModificationCanal">
                                        <i class="fa-solid fa-pen modifier"></i>
                                        <i class="fa-solid fa-x supprimer"></i>
                                    </div>
                                </div>
                                <div class="canal">
                                    <a href="/clan/{{$id}}/zone-de-récupération" class="canal_10">
                                        <div>
                                            <i class="fa-solid fa-hashtag"></i>
                                            zone-de-récupération
                                        </div>
                                    </a>
                                    <div class="iconesModificationCanal">
                                        <i class="fa-solid fa-pen modifier"></i>
                                        <i class="fa-solid fa-x supprimer"></i>
                                    </div>
                                </div>
                                <div class="canal">
                                    <a href="/clan/{{$id}}/musculation" class="canal_11">
                                        <div>
                                            <i class="fa-solid fa-hashtag"></i>
                                            musculation
                                        </div>
                                    </a>
                                    <div class="iconesModificationCanal">
                                        <i class="fa-solid fa-pen modifier"></i>
                                        <i class="fa-solid fa-x supprimer"></i>
                                    </div>
                                </div>
                                <div class="canal">
                                    <a href="/clan/{{$id}}/cardio" class="canal_12">
                                        <div>
                                            <i class="fa-solid fa-hashtag"></i>
                                            cardio
                                        </div>
                                    </a>
                                    <div class="iconesModificationCanal">
                                        <i class="fa-solid fa-pen modifier"></i>
                                        <i class="fa-solid fa-x supprimer"></i>
                                    </div>
                                </div>
                                <div class="canal">
                                    <a href="/clan/{{$id}}/entrainements-maison" class="canal_13">
                                        <div>
                                            <i class="fa-solid fa-hashtag"></i>
                                            entrainements-maison
                                        </div>
                                    </a>
                                    <div class="iconesModificationCanal">
                                        <i class="fa-solid fa-pen modifier"></i>
                                        <i class="fa-solid fa-x supprimer"></i>
                                    </div>
                                </div>
                                <div class="canal">
                                    <a href="/clan/{{$id}}/discussion" class="canal_14">
                                        <div>
                                            <i class="fa-solid fa-hashtag"></i>
                                            discussion
                                        </div>
                                    </a>
                                    <div class="iconesModificationCanal">
                                        <i class="fa-solid fa-pen modifier"></i>
                                        <i class="fa-solid fa-x supprimer"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="categorieCanal">
                                <div class="titreCategorieCanal categorie_3">
                                    <div>
                                        <i class="fa-solid fa-minus"></i>
                                        Compétition
                                    </div>
                                    <i class="fa-solid fa-plus fa-xs"></i>
                                </div>

                                <div class="canal">
                                    <a href="/clan/{{$id}}/defis-hebdomadaires" class="canal_15">
                                        <div>
                                            <i class="fa-solid fa-hashtag"></i>
                                            defis-hebdomadaires
                                        </div>
                                    </a>
                                    <div class="iconesModificationCanal">
                                        <i class="fa-solid fa-pen modifier"></i>
                                        <i class="fa-solid fa-x supprimer"></i>
                                    </div>
                                </div>
                                <div class="canal">
                                    <a href="/clan/{{$id}}/combats-de-clans" class="canal_16">
                                        <div>
                                            <i class="fa-solid fa-hashtag"></i>
                                            combats-de-clans
                                        </div>
                                    </a>
                                    <div class="iconesModificationCanal">
                                        <i class="fa-solid fa-pen modifier"></i>
                                        <i class="fa-solid fa-x supprimer"></i>
                                    </div>
                                </div>
                                <div class="canal">
                                    <a href="/clan/{{$id}}/mur-de-motivation" class="canal_17">
                                        <div>
                                            <i class="fa-solid fa-hashtag"></i>
                                            mur-de-motivation
                                        </div>
                                    </a>
                                    <div class="iconesModificationCanal">
                                        <i class="fa-solid fa-pen modifier"></i>
                                        <i class="fa-solid fa-x supprimer"></i>
                                    </div>
                                </div>
                                <div class="canal">
                                    <a href="/clan/{{$id}}/victoires" class="canal_18">
                                        <div>
                                            <i class="fa-solid fa-hashtag"></i>
                                            victoires
                                        </div>
                                    </a>
                                    <div class="iconesModificationCanal">
                                        <i class="fa-solid fa-pen modifier"></i>
                                        <i class="fa-solid fa-x supprimer"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8 colonneMessages">
                <div class="contenuScrollable">
                    <div class="message">
                        <a href=""><img src="{{asset('img/Utilisateurs/utilisateurParDefaut.jpg')}}" ></a>
                        <div class="contenu">
                            <div class="utilisateur">
                                <a><strong>Gymcord#654302</strong></a>
                            </div>
                            <div class="texteMessage">
                                Quelqu'un a des recommendations pour des trucs de base en calisthénie que je pourrais faire pour commencer?
                            </div>
                        </div>
                    </div>

                    <div class="message">
                        <a href=""><img src="{{asset('img/Utilisateurs/coachNoah.jpg')}}" ></a>
                        <div class="contenu">
                            <div class="utilisateur">
                                <a href=""><strong>Coach Noah</strong></a>
                            </div>
                            <div class="texteMessage">
                                Pas de problème! As-tu des trucs en particulier que tu souhaites travailler?
                            </div>
                        </div>
                    </div>

                    <div class="message">
                        <a href=""><img src="{{asset('img/Utilisateurs/utilisateurParDefaut.jpg')}}" ></a>
                        <div class="contenu">
                            <div class="utilisateur">
                                <a><strong>Gymcord#654302</strong></a>
                            </div>
                            <div class="texteMessage">
                                Honnêtement j'en ai aucune idée je viens juste de télécharger l'app et j'essaie de trouver de l'aide pour m'entrainer. J'ai aucun équipement de gym si c'est ça que tu demandais.
                            </div>
                        </div>
                    </div>

                    <div class="message">
                        <a href=""><img src="{{asset('img/Utilisateurs/coachNoah.jpg')}}" ></a>
                        <div class="contenu">
                            <div class="utilisateur">
                                <a href=""><strong>Coach Noah</strong></a>
                            </div>
                            <div class="texteMessage">
                                Aucun problème, il-y-a beaucoup de personnes comme toi qui viennent sur l'app elle est vraiment cool! Ce que je veux dire c'est plus quel genre de but tu as en t'entrainant?
                                Par exemple: mobilité, endurance, force, santé générale, etcetc.
                            </div>
                        </div>
                    </div>

                    <div class="message">
                        <a href=""><img src="{{asset('img/Utilisateurs/utilisateurParDefaut.jpg')}}" ></a>
                        <div class="contenu">
                            <div class="utilisateur">
                                <a><strong>Gymcord#654302</strong></a>
                            </div>
                            <div class="texteMessage">
                                Ahhh ok je vois. En vrai je veux juste pas mourir à 30 ans je sors pas et je fais pas de sport et je stress que je vais mourir jeune.
                            </div>
                        </div>
                    </div>

                    <div class="message">
                        <a href=""><img src="{{asset('img/Utilisateurs/coachNoah.jpg')}}" ></a>
                        <div class="contenu">
                            <div class="utilisateur">
                                <a href=""><strong>Coach Noah</strong></a>
                            </div>
                            <div class="texteMessage">
                                Ouais pas de problème santé générale alors. Pour ça tu regarderas # plans-entrainement ils ont des trucs vraiment cool tu peux trouver des très bons entrainements qu'on a fait pour les débutants.
                                À part ça je peux te donner des trucs généraux :

                                - Concentre toi sur les bases : concentre toi sur les bases comme les push-ups, squats et les planches avant d'Aller pour des formes plus avancées.
                                - Priorise la forme : c'est mieux de faire quelques répétitions d'un entrainement en ayant une bonne form plutôt que beaucoup mais avec une mauvaise forme. Concentre toi sur garder une bonne technique.
                                - La clé c'est être constant :  vise pour 3 sessions par semaine au minimum, en gardant les entraînements de force, de flexibilité et de mobilité balancés.
                                - Contracte ton centre : garde ton centre (ta core comme ils disent en anglais) contractée tout au long de tes mouvements et de tes positions de calisthénie, ça va t'aider à rester stable
                                et à avoir un bon contrôle sur ton corps

                                Vu que t'as peur pour ta santé le mieux en ce moment ça serait de demander à l'administrateur pour de l'aide son travail est nutritioniste. Tu devrais aussi avoir une session de cardio par semaine pour
                                être sur que ton coeur est en santé.
                            </div>
                        </div>
                    </div>

                    <div class="message">
                        <a href=""><img src="{{asset('img/Utilisateurs/utilisateurParDefaut.jpg')}}" ></a>
                        <div class="contenu">
                            <div class="utilisateur">
                                <a><strong>Gymcord#654302</strong></a>
                            </div>
                            <div class="texteMessage">
                                Wow ok je m'attendais vraiment pas à un guide complet merci! Je vais regarder ça super!
                            </div>
                        </div>
                    </div>

                    
                    <div class="message">
                        <a href=""><img src="{{asset('img/Utilisateurs/coachNoah.jpg')}}" ></a>
                        <div class="contenu">
                            <div class="utilisateur">
                                <a href=""><strong>Coach Noah</strong></a>
                            </div>
                            <div class="texteMessage">
                                Pas de problème!
                            </div>
                        </div>
                    </div>

                    <div class="message">
                        <a href=""><img src="{{asset('img/Utilisateurs/coachNoah.jpg')}}" ></a>
                        <div class="contenu">
                            <div class="utilisateur">
                                <a href=""><strong>Coach Noah</strong></a>
                            </div>
                            <div class="texteMessage">
                                Si t'as des questions hésite pas! Tu devrais m'envoyer une demande d'amis aussi m'as garder tes notifications activées.
                            </div>
                        </div>
                    </div>

                    <div class="message">
                        <a href=""><img src="{{asset('img/Utilisateurs/utilisateurParDefaut.jpg')}}" ></a>
                        <div class="contenu">
                            <div class="utilisateur">
                                <a><strong>Gymcord#654302</strong></a>
                            </div>
                            <div class="texteMessage">
                                Je vais le faire sans faute merci!
                            </div>
                        </div>
                    </div>

                    <div class="message">
                        <a href=""><img src="{{asset('img/Utilisateurs/utilisateurParDefaut.jpg')}}" ></a>
                        <div class="contenu">
                            <div class="utilisateur">
                                <a><strong>Gymcord#654302</strong></a>
                            </div>
                            <div class="texteMessage">
                                Je viens de te l'envoyer
                            </div>
                        </div>
                    </div>

                    <div class="message">
                        <a href=""><img src="{{asset('img/Utilisateurs/coachNoah.jpg')}}" ></a>
                        <div class="contenu">
                            <div class="utilisateur">
                                <a href=""><strong>Coach Noah</strong></a>
                            </div>
                            <div class="texteMessage">
                                Acceptée!
                            </div>
                        </div>
                    </div>



                </div>
                <div class="entreeFixe">
                    <input type="text" placeholder="Entrez votre message içi..." maxlength="1000">
                    <i class="fa-solid fa-play aly fa-xl"></i>
                </div>
            </div>
            <div class="col-md-2 colonneMembres">
                <div class="contenuScrollableMembres">
                    <div class="membre">
                        <a href="">
                            <img src="{{asset('img/Utilisateurs/utilisateur1.jpg')}}" > 
                            <div>
                                <strong>ADMIN</strong> - Tommy Jackson
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="">
                            <img src="{{asset('img/Utilisateurs/utilisateur2.jpg')}}" > 
                            <div>
                                AverageGymGoer
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="">
                            <img src="{{asset('img/Utilisateurs/utilisateur3.jpg')}}" > 
                            <div>
                                NotTheAverageGuy
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="">
                            <img src="{{asset('img/Utilisateurs/utilisateur4.jpg')}}" > 
                            <div>
                                Julie St-Aubin  
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="">
                            <img src="{{asset('img/Utilisateurs/utilisateur5.avif')}}" > 
                            <div>
                                Gnulons  
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="">
                            <img src="{{asset('img/Utilisateurs/utilisateur6.jpg')}}" > 
                            <div>
                                Jack Jacked
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="">
                            <img src="{{asset('img/Utilisateurs/utilisateur7.jpg')}}" > 
                            <div>
                                Sophie  
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="">
                            <img src="{{asset('img/Utilisateurs/utilisateur8.jpg')}}" > 
                            <div>
                                Lucia Percada
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="">
                            <img src="{{asset('img/Utilisateurs/utilisateur9.jpg')}}" > 
                            <div>
                                Stevie  
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="">
                            <img src="{{asset('img/Utilisateurs/utilisateur11.jpg')}}" > 
                            <div>
                                Tom  
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="">
                            <img src="{{asset('img/Utilisateurs/utilisateur12.jpg')}}" > 
                            <div>
                                Bluestack  
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="">
                            <img src="{{asset('img/Utilisateurs/utilisateur13.jpg')}}" > 
                            <div>
                                CoolCarl123
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="">
                            <img src="{{asset('img/Utilisateurs/utilisateur14.webp')}}" > 
                            <div>
                                Sylvain  
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="">
                            <img src="{{asset('img/Utilisateurs/utilisateur15.jpg')}}" > 
                            <div>
                                Ghost  
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="">
                            <img src="{{asset('img/Utilisateurs/utilisateur16.jpg')}}" > 
                            <div>
                                Coach Noah  
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="">
                            <img src="{{asset('img/Utilisateurs/utilisateur17.jpg')}}" > 
                            <div>
                                MotivationGuy  
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="">
                            <img src="{{asset('img/Utilisateurs/utilisateur18.jpg')}}" > 
                            <div>
                                xXDarkSlayerXx  
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="">
                            <img src="{{asset('img/Utilisateurs/utilisateur19.jpg')}}" > 
                            <div>
                                CalisthenicGod_1  
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="">
                            <img src="{{asset('img/Utilisateurs/utilisateur20.jpg')}}" > 
                            <div>
                                Gymcord#654302  
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="">
                            <img src="{{asset('img/Utilisateurs/utilisateur4.jpg')}}" > 
                            <div>
                                Julia Julia    
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="">
                            <img src="{{asset('img/Utilisateurs/utilisateur2.jpg')}}" > 
                            <div>
                                Dieu Poulet
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form id="formulaireClan" action="{{ route('canal.actions', ['id' => $id]) }}" method="POST">
        @csrf
        <input type="hidden" class="action" name="action">
        <input type="hidden" class="requete" name="requete">
    </form>

    <!-- Fenêtre contextuelle pour ajouter un canal -->
    <div id="ajoutCanal" class="fenetreCategorie">
        <div class="conteneurConfirmation">
            <div class="titreConfirmation">
                <div>Ajouter un canal</div>
            </div>
            <div class="texteConfirmation">
                <input type="text" name="entreeNomCanal" class="form-control entreeNomCanal" placeholder="ex.: Cardio">
                <span class="messageErreur"></span>
            </div>

            <div class="boutonsConfirmation">
                <button class="annuler" type="button">Annuler</button>
                <button id="confirmerAjout" type="button">Confirmer</button>
            </div>
        </div>
    </div>

    <!-- Fenêtre contextuelle pour renommer un canal -->
    <div id="renommerCanal" class="fenetreCategorie">
        <div class="conteneurConfirmation">
            <div class="titreConfirmation">
                <div>Renommer un canal</div>
            </div>
            <div class="texteConfirmation">
                <input type="text" name="entreeNomCanal" class="form-control entreeNomCanal" placeholder="ex.: Cardio">
                <span class="messageErreur"></span>
            </div>

            <div class="boutonsConfirmation">
                <button class="annuler" type="button">Annuler</button>
                <button id="confirmerRenommage" type="button">Confirmer</button>
            </div>
        </div>
    </div>

    <!-- Fenêtre contextuelle pour supprimer un canal -->
    <div id="confirmationSuppression" class="fenetreCategorie">
        <div class="conteneurConfirmation">
            <div class="titreConfirmation">
                <div>Supprimer le canal</div>
            </div>
            <div class="texteConfirmation">
                <div>Êtes-vous sur de vouloir supprimer ce canal?</div>
            </div>

            <div class="boutonsConfirmation">
                <button class="annuler" type="button">Annuler</button>
                <button id="confirmerSuppression" type="button">Supprimer</button>
            </div>
        </div>
    </div>


</div>

@endsection()


@section("scripts")
<script src="{{ asset('js/Clans/clans.js') }}" crossorigin="anonymous"> </script>
@endsection()