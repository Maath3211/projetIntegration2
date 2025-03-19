<!DOCTYPE html>
<html lang="fr-CA">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> GymCord - {{ __('clans.parametres_clan') }} </title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script defer src="https://use.fontawesome.com/releases/v5.1.0/js/all.js" integrity="sha384-3LK/3kTpDE/Pkp8gTNp2gR/2gOiwQ6QaO7Td0zV76UFJVhqLl4Vl3KL1We6q6wR9" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" style="text/css" href="\css\gabaritCss.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/55ec8bd5f8.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" style="text/css" href="{{asset('css/Clans/parametres.css')}}">
    <meta charset="UTF-8">
</head>

<body class="flex h-screen" id="background">
    <main id="main">
        <div class="container-fluid">
            <div class="row">
                <!-- Navigation -->
                <div class="col-md-2 colonneNavigationParametres">
                    <div class="conteneurNavigation">
                        <div class="titreNavigation">{{ __('clans.parametres') }}</div>
                        <div class="navigationParametres">
                            <div class="categorieParametre general">{{ __('clans.general') }}</div>
                            <div class="categorieParametre canaux actif">{{ __('clans.canaux') }}</div>
                            <div class="categorieParametre membres">{{ __('clans.membres') }}</div>
                            <div class="categorieParametre supprimer">{{ __('clans.supprimer_clan') }}</div>
                        </div>
                    </div>
                </div>

                <!-- Paramètres de catégories de canaux -->
                <div class="col-md-10 colonneParametres">
                    <div class="conteneurParametres ">

                        <div class="titreParametre">{{ __('clans.renommer_categorie_canal') }}</div>
                        <a href="{{ route('clan.montrer', ['id' => $id]) }}">
                            <div class="boutonRetour">
                                <i class="fa-regular fa-circle-xmark fa-3x"></i>
                                <div>{{ __('clans.quitter') }}</div>
                            </div>
                        </a>
                    </div>
                    <form action="{{ route('clan.miseajour.canaux', ['id' => $id]) }}" method="POST" enctype="multipart/form-data" id="formulaireSoumission">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 parametresCanal">
                                <!-- Montrer les catégories de canaux -->
                                @if(isset($categories))
                                @foreach($categories as $cat)
                                <div class="categorie {{$cat->categorie}}">
                                    <i class="fa-solid fa-x supprimer"></i>
                                    <i class="fa-solid fa-pen renommer"></i>
                                    <div>{{$cat->categorie}}</div>
                                </div>
                                @endforeach
                                @endif
                            </div>
                            <div>
                                <button type="button" class="ajouterCategorie">{{ __('clans.ajout_categorie') }}</button>
                            </div>
                        </div>
                        <!-- entrée caché pour enregistrer les modifications à faire. -->
                        <input type="hidden" name="categoriesASupprimer" id="categoriesASupprimer">
                        <input type="hidden" name="categoriesAAjouter" id="categoriesAAjouter">
                        <input type="hidden" name="categoriesARenommer" id="categoriesARenommer">

                        <div class="row barreEnregistrerConteneur">
                            <div class="col-md-10 rangeeEnregistrer">
                                <div>{{ __('clans.modification_sauvegarde') }}</div>
                                <button type="submit" class="btn btn-success">{{ __('clans.sauvegarde') }}</button>
                            </div>
                        </div>
                    </form>

                    <!-- Fenêtre contextuelle pour confirmer la suppression d'une catégorie de canal -->
                    <div id="confirmationSuppression" class="fenetreCategorie">
                        <div class="conteneurConfirmation">
                            <div class="titreConfirmation">
                                <div>{{ __('clans.supression_categorie_canal') }}</div>
                            </div>
                            <div class="texteConfirmation">
                                <div>{{ __('clans.supression_categorie_canal_avertissement') }}</div>
                            </div>

                            <div class="boutonsConfirmation">
                                <button class="annuler" type="button">{{ __('clans.annuler') }}</button>
                                <button id="confirmerSuppression" type="button">{{ __('clans.supprimer') }}</button>
                            </div>
                        </div>
                    </div>

                    <!-- Fenêtre contextuelle pour renommer une catégorie de canal -->
                    <div id="modificationNomCategorie" class="fenetreCategorie">
                        <div class="conteneurConfirmation">
                            <div class="titreConfirmation">
                                <div>{{__('clans.renommer_categorie_canal')}}</div>
                            </div>
                            <div class="texteConfirmation">
                                <input type="text" name="entreeNomCategorie" class="form-control entreeNomCategorie">
                                <span class="messageErreur"></span>
                            </div>

                            <div class="boutonsConfirmation">
                                <button class="annuler" type="button">{{ __('clans.annuler') }}</button>
                                <button id="confirmerRenommage" type="button">{{ __('clans.confirmer') }}</button>
                            </div>
                        </div>
                    </div>


                    <!-- Fenêtre contextuelle pour ajouter une catégorie de canal -->
                    <div id="ajoutCategorie" class="fenetreCategorie">
                        <div class="conteneurConfirmation">
                            <div class="titreConfirmation">
                                <div>{{ __('clans.ajout_canal_categorie') }}</div>
                            </div>
                            <div class="texteConfirmation">
                                <input type="text" name="entreeNomCategorie" class="form-control entreeNomCategorie" placeholder="ex.: Cardio">
                                <span class="messageErreur"></span>
                            </div>

                            <div class="boutonsConfirmation">
                                <button class="annuler" type="button">{{ __('clans.annuler') }}</button>
                                <button id="confirmerAjout" type="button">{{ __('clans.supprimer') }}</button>
                            </div>
                        </div>
                    </div>

                    <!-- Fenêtre contextuelle pour confirmer la suppression du clan -->
                    <div id="confirmationSuppressionClan" class="fenetreCategorie">
                        <div class="conteneurConfirmation">
                            <div class="titreConfirmation">
                                <div>{{ __('clans.suppression_clan')}}</div>
                            </div>
                            <div class="texteConfirmation">
                                <div>{{ __('clans.avertissement_suppression_clan')}}</div>
                            </div>

                            <div class="boutonsConfirmation">
                                <button class="annuler" type="button">{{ __('clans.annuler')}}</button>
                                <button id="confirmerSuppressionClan" type="button">{{ __('clans.supprimer')}}</button>
                            </div>
                        </div>
                    </div>
                    <form action="{{ route('clan.supprimer', ['id' => $id]) }}" method="POST" enctype="multipart/form-data" id="formulaireSuppressionClan">@csrf</form>
                </div>
            </div>
        </div>
        <!-- Affichage des erreurs -->
        <div id="conteneurMessages">
            @if(session('message'))
            <div class="alert" id="messageSucces">
                <span>{{session('message')}}</span>
                <button class="close-btn">X</button>
            </div>
            @endif

            <!--Obligé d'utiliser $errors ici c'est la facon que laravel gère ses erreurs-->
            @if($errors->any() || session('erreur'))
            <div class="alert" id="messageErreur">
                <ul>
                    @if($errors->any())
                    @foreach($errors->all() as $erreur)
                    <li>{{ $erreur }}</li>
                    @endforeach
                    @endif
                    @if(session('erreur'))
                    <li>{{ session('erreur') }}</li>
                    @endif
                </ul>
                <button class="close-btn">X</button>
            </div>
            @endif
        </div>
    </main>
    <footer>
    </footer>
</body>
<script src="{{ asset('js/Clans/parametresClanCanaux.js') }}" crossorigin="anonymous"> </script>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</html>