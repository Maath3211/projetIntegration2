<!DOCTYPE html>
<html lang="fr-CA">
<head>
<meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
<title> GymCord - Paramètres de clan </title>
<meta name="viewport" content="width=device-width, initial-scale=1">
        <script defer src="https://use.fontawesome.com/releases/v5.1.0/js/all.js" integrity="sha384-3LK/3kTpDE/Pkp8gTNp2gR/2gOiwQ6QaO7Td0zV76UFJVhqLl4Vl3KL1We6q6wR9" crossorigin="anonymous"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" style="text/css" href="\css\gabaritCss.css">
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://kit.fontawesome.com/55ec8bd5f8.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" style="text/css" href="{{asset('css/Clans/parametresMembres.css')}}">
<meta charset="UTF-8">
</head>

<body class=" flex h-screen" id="background">
    <main id="main">
    <div class="container-fluid">
        <div class="row">
        <div class="col-md-2 colonneNavigationParametres">
            <div class="conteneurNavigation">
            <div class="titreNavigation">Paramètres</div>
            <div class="navigationParametres">
                <div class="categorieParametre general" >Général</div>
                <div class="categorieParametre canaux" >Canaux</div>
                <div class="categorieParametre membres actif" >Membres</div>
                <div class="categorieParametre supprimer">Supprimer le clan</div>
            </div>
            </div>
        </div>

        <div class="col-md-10 colonneParametres">
            <div class="conteneurParametres ">

            <div class="titreParametre">Membres du clan</div>
            <a href="{{ route('clan.montrer', ['id' => $id]) }}">
                <div class="boutonRetour">
                <i class="fa-regular fa-circle-xmark fa-3x"></i>
                <div>QUITTER</div>
                </div>
            </a>
            </div>
            <!-- TODO - CHANGER L'IMAGE QUI APPARAIT POUR CELLE DU CLAN -->
            <form action="{{ route('clan.miseAJour.membres', ['id' => $id]) }}" method="POST" enctype="multipart/form-data" id="formulaireSoumission">
            @csrf
                <div class="row">
                    <div class="col-md-12 parametresCanal">
                        <div class="membre membre_1">
                            <i class="fa-solid fa-x supprimer"></i>
                            <img src="{{asset('img/Utilisateurs/utilisateur1.jpg')}}" > 
                            <div>
                                Tommy Jackson
                            </div>
                        </div>
                        <div class="membre membre_2">
                            <i class="fa-solid fa-x supprimer"></i>
                            <img src="{{asset('img/Utilisateurs/utilisateur2.jpg')}}" > 
                            <div>
                                AverageGymGoer
                            </div>
                        </div>
                        <div class="membre membre_3">
                            <i class="fa-solid fa-x supprimer"></i>
                            <img src="{{asset('img/Utilisateurs/utilisateur3.jpg')}}" > 
                            <div>
                                NotTheAverageGuy
                            </div>
                        </div>
                        <div class="membre membre_4">
                            <i class="fa-solid fa-x supprimer"></i>
                            <img src="{{asset('img/Utilisateurs/utilisateur4.jpg')}}" > 
                            <div>
                                Julie St-Aubin
                            </div>
                        </div>
                        <div class="membre membre_5">
                            <i class="fa-solid fa-x supprimer"></i>
                            <img src="{{asset('img/Utilisateurs/utilisateur20.jpg')}}" > 
                            <div>
                                Gnulons
                            </div>
                        </div>
                        <div class="membre membre_6">
                            <i class="fa-solid fa-x supprimer"></i>
                            <img src="{{asset('img/Utilisateurs/utilisateur6.jpg')}}" > 
                            <div>
                                Jack Jacked
                            </div>
                        </div>
                        <div class="membre membre_7">
                            <i class="fa-solid fa-x supprimer"></i>
                            <img src="{{asset('img/Utilisateurs/utilisateur7.jpg')}}" > 
                            <div>
                                Sophie
                            </div>
                        </div>
                        <div class="membre membre_8">
                            <i class="fa-solid fa-x supprimer"></i>
                            <img src="{{asset('img/Utilisateurs/utilisateur8.jpg')}}" > 
                            <div>
                                Lucia Percada
                            </div>
                        </div>
                        <div class="membre membre_9">
                            <i class="fa-solid fa-x supprimer"></i>
                            <img src="{{asset('img/Utilisateurs/utilisateur9.jpg')}}" > 
                            <div>
                                Stevie
                            </div>
                        </div>
                        <div class="membre membre_10">
                            <i class="fa-solid fa-x supprimer"></i>
                            <img src="{{asset('img/Utilisateurs/utilisateur19.jpg')}}" > 
                            <div>
                                Tom
                            </div>
                        </div>
                        <div class="membre membre_11">
                            <i class="fa-solid fa-x supprimer"></i>
                            <img src="{{asset('img/Utilisateurs/utilisateur11.jpg')}}" > 
                            <div>
                                Bluestack
                            </div>
                        </div>
                        <div class="membre membre_12">
                            <i class="fa-solid fa-x supprimer"></i>
                            <img src="{{asset('img/Utilisateurs/utilisateur12.jpg')}}" > 
                            <div>
                                CoolCarl123
                            </div>
                        </div>
                        <div class="membre membre_13">
                            <i class="fa-solid fa-x supprimer"></i>
                            <img src="{{asset('img/Utilisateurs/utilisateur13.jpg')}}" > 
                            <div>
                                Sylvain
                            </div>
                        </div>
                        <div class="membre membre_14">
                            <i class="fa-solid fa-x supprimer"></i>
                            <img src="{{asset('img/Utilisateurs/utilisateur21.jpg')}}" > 
                            <div>
                                Ghost
                            </div>
                        </div>
                        <div class="membre membre_15">
                            <i class="fa-solid fa-x supprimer"></i>
                            <img src="{{asset('img/Utilisateurs/utilisateur15.jpg')}}" > 
                            <div>
                                Coach Noah
                            </div>
                        </div>
                        <div class="membre membre_16">
                            <i class="fa-solid fa-x supprimer"></i>
                            <img src="{{asset('img/Utilisateurs/utilisateur16.jpg')}}" > 
                            <div>
                                MotivationGuy
                            </div>
                        </div>
                        <div class="membre membre_17">
                            <i class="fa-solid fa-x supprimer"></i>
                            <img src="{{asset('img/Utilisateurs/utilisateur17.jpg')}}" > 
                            <div>
                                xXDarkSlayerXx
                            </div>
                        </div>
                        <div class="membre membre_18">
                            <i class="fa-solid fa-x supprimer"></i>
                            <img src="{{asset('img/Utilisateurs/utilisateur18.jpg')}}" > 
                            <div>
                                CalisthenicGod_1
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Input caché pour enregistrer les modifications à faire. -->
                <input type="hidden" name="membresASupprimer" id="membresASupprimer">

                
                <div class="row barreEnregistrerConteneur">
                    <div class="col-md-10 rangeeInviter">
                        <div>Lien d'invitation: </div>
                        <div>https://gymcord.com/invite/213E092hdjasvgFAEWd214#210e9!43$#1% <i class="fa-regular fa-copy copier"></i></div>
                    </div>
                    <div class="col-md-10 rangeeEnregistrer">
                        <div>N'oubliez pas d'enregistrer vos modifications avant de quitter!</div>
                        <button type="submit" class="btn btn-success">Enregistrer</button>
                    </div>
                </div>
            </form>

            <!-- Fenêtre contextuelle pour confirmer l'expulsion d'un membre -->
            <div id="confirmationSuppressionMembre" class="fenetreCategorie">
                <div class="conteneurConfirmation">
                    <div class="titreConfirmation">
                        <div>Expulser le membre</div>
                    </div>
                    <div class="texteConfirmation">
                        <div>Êtes-vous sur de vouloir expulser ce membre?</div>
                    </div>

                    <div class="boutonsConfirmation">
                        <button class="annuler" type="button">Annuler</button>
                        <button id="confirmerSuppression" type="button">Expulser</button>
                    </div>
                </div>
            </div>

            <!-- Fenêtre contextuelle pour confirmer la suppression du clan -->
            <div id="confirmationSuppressionClan" class="fenetreCategorie">
                <div class="conteneurConfirmation">
                    <div class="titreConfirmation">
                        <div>Supprimer le clan</div>
                    </div>
                    <div class="texteConfirmation">
                        <div>Êtes-vous sur de vouloir supprimer le clan? Cette action est irréversible.</div>
                    </div>

                    <div class="boutonsConfirmation">
                        <button class="annuler" type="button">Annuler</button>
                        <button id="confirmerSuppressionClan" type="button">Supprimer</button>
                    </div>
                </div>
            </div>
            <form action="{{ route('clan.supprimer', ['id' => $id]) }}" method="POST" enctype="multipart/form-data" id="formulaireSuppressionClan"></form>

        </div>
        </div>
    </div>
    </main>
<footer>
</footer>
</body>
<script src="{{ asset('js/Clans/parametresClanMembres.js') }}" crossorigin="anonymous"> </script>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</html>
