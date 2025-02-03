@extends('Layouts.app')
@section('titre', 'Clans')
@section('style')
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
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
        aside {
            background-color: #303030;
        }

        .colonneCanaux {
            background-color: #3A3A3A;
            border-left: 2px solid rgba(255, 255, 255, 0.5);
            border-right: 2px solid rgba(255, 255, 255, 0.5);
            display: flex;
            flex-direction: column;
            height: 100vh;
            padding-right: 0px !important;
            padding-left: 0px !important;
        }

        .container {
            display: flex;
            justify-content: center;
            padding-right: 0px !important;
            padding-left: 0px !important;
        }
        
        .column {
            width: 100%;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .texteSurImage {
            color: white;
            font-family: 'Open Sans', sans-serif;
        }
        
        .conteneurCanaux {
            margin: 15px;
            margin-right:5px;
            padding-right: 10px;
            flex-grow: 1;
            overflow-y: auto;
            overflow-x: hidden;
            max-height: calc(100vh - 150px - 30px);
            scrollbar-width: thin;
            scrollbar-color: #5a5a5a transparent;
        }

        .canal {
            margin: 10px;
            color: black;
            background-color: #A9FE77;
            border-radius: 10px;
            padding: 5px;
            padding-left: 20px;
            padding-right: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            white-space: nowrap;
            font-size: 15px;
        }

        .canal div:first-child {
            overflow: hidden;
            white-space: nowrap;
            margin-right: 10px;
        }

        .canal div:last-child i {
            opacity: 0;
            transition: opacity 0.2s ease-in-out;
        }

        .canal:hover {
            background-color: #6EFF18;   
            transition: background-color 0.3s ease-in-out;
        }

        .canal:hover div:last-child i {
            opacity: 1;
        }

        .canal.active {
            background-color: #6EFF18;   
        }

        .titreCategorieCanal {
            margin-top: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: bold;
            color: #D9D9D9;
            margin-right: 10px;
        }

    </style>
@endsection()

@section('contenu')

<div class="contenuPrincipal flex-1">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 colonneCanaux">
                <div class="container">
                    <div class="column">
                        <div class="conteneurImage">
                            <div class="texteSurImage">Workout Master</div>
                            <div><a href="#"><i class="fa-solid fa-ellipsis"></i></a></div>
                        </div>
                        <div class="conteneurCanaux">
                            <div class="categorieCanal">
                                <div class="titreCategorieCanal">
                                    <div>
                                        <i class="fa-solid fa-minus"></i>
                                        Général
                                    </div>
                                    <i class="fa-solid fa-plus fa-2xs"></i>
                                </div>

                                <div class="canal">
                                    <div>
                                        <i class="fa-solid fa-hashtag"></i>
                                        bienvenue
                                    </div>
                                    <div>
                                        <a href="#"><i class="fa-solid fa-pen fa-xs "></i></a>
                                        <a href="#"><i class="fa-solid fa-x fa-xs"></i></a>
                                    </div>
                                </div>
                                <div class="canal">
                                    <div>
                                        <i class="fa-solid fa-hashtag"></i>
                                        annonces
                                    </div>
                                    <div>
                                        <a href="#"><i class="fa-solid fa-pen fa-xs "></i></a>
                                        <a href="#"><i class="fa-solid fa-x fa-xs"></i></a>
                                    </div>
                                </div>
                                <div class="canal">
                                    <div>
                                        <i class="fa-solid fa-hashtag"></i>
                                        règles-et-informations
                                    </div>
                                    <div>
                                        <a href="#"><i class="fa-solid fa-pen fa-xs "></i></a>
                                        <a href="#"><i class="fa-solid fa-x fa-xs"></i></a>
                                    </div>
                                </div>
                                <div class="canal">
                                    <div>
                                        <i class="fa-solid fa-hashtag"></i>
                                        introductions
                                    </div>
                                    <div>
                                        <a href="#"><i class="fa-solid fa-pen fa-xs "></i></a>
                                        <a href="#"><i class="fa-solid fa-x fa-xs"></i></a>
                                    </div>
                                </div>
                            </div>

                            <div class="categorieCanal">
                                <div class="titreCategorieCanal">
                                    <div>
                                        <i class="fa-solid fa-minus"></i>
                                        Aide
                                    </div>
                                    <i class="fa-solid fa-plus fa-2xs"></i>
                                </div>

                                <div class="canal">
                                    <div>
                                        <i class="fa-solid fa-hashtag"></i>
                                        trucs-et-astuces
                                    </div>
                                    <div>
                                        <a href="#"><i class="fa-solid fa-pen fa-xs "></i></a>
                                        <a href="#"><i class="fa-solid fa-x fa-xs"></i></a>
                                    </div>
                                </div>
                                <div class="canal">
                                    <div>
                                        <i class="fa-solid fa-hashtag"></i>
                                        plan-entrainement
                                    </div>
                                    <div>
                                        <a href="#"><i class="fa-solid fa-pen fa-xs "></i></a>
                                        <a href="#"><i class="fa-solid fa-x fa-xs"></i></a>
                                    </div>
                                </div>
                                <div class="canal">
                                    <div>
                                        <i class="fa-solid fa-hashtag"></i>
                                        images-progrès
                                    </div>
                                    <div>
                                        <a href="#"><i class="fa-solid fa-pen fa-xs "></i></a>
                                        <a href="#"><i class="fa-solid fa-x fa-xs"></i></a>
                                    </div>
                                </div>
                                <div class="canal">
                                    <div>
                                        <i class="fa-solid fa-hashtag"></i>
                                        nutrition
                                    </div>
                                    <div>
                                        <a href="#"><i class="fa-solid fa-pen fa-xs "></i></a>
                                        <a href="#"><i class="fa-solid fa-x fa-xs"></i></a>
                                    </div>
                                </div>
                                <div class="canal">
                                    <div>
                                        <i class="fa-solid fa-hashtag"></i>
                                        efforts-journaliers
                                    </div>
                                    <div>
                                        <a href="#"><i class="fa-solid fa-pen fa-xs "></i></a>
                                        <a href="#"><i class="fa-solid fa-x fa-xs"></i></a>
                                    </div>
                                </div>
                                <div class="canal">
                                    <div>
                                        <i class="fa-solid fa-hashtag"></i>
                                        zone-de-récupération
                                    </div>
                                    <div>
                                        <a href="#"><i class="fa-solid fa-pen fa-xs "></i></a>
                                        <a href="#"><i class="fa-solid fa-x fa-xs"></i></a>
                                    </div>
                                </div>
                                <div class="canal">
                                    <div>
                                        <i class="fa-solid fa-hashtag"></i>
                                        musculation
                                    </div>
                                    <div>
                                        <a href="#"><i class="fa-solid fa-pen fa-xs "></i></a>
                                        <a href="#"><i class="fa-solid fa-x fa-xs"></i></a>
                                    </div>
                                </div>
                                <div class="canal">
                                    <div>
                                        <i class="fa-solid fa-hashtag"></i>
                                        cardio
                                    </div>
                                    <div>
                                        <a href="#"><i class="fa-solid fa-pen fa-xs "></i></a>
                                        <a href="#"><i class="fa-solid fa-x fa-xs"></i></a>
                                    </div>
                                </div>
                                <div class="canal">
                                    <div>
                                        <i class="fa-solid fa-hashtag"></i>
                                        entrainements-maison
                                    </div>
                                    <div>
                                        <a href="#"><i class="fa-solid fa-pen fa-xs "></i></a>
                                        <a href="#"><i class="fa-solid fa-x fa-xs"></i></a>
                                    </div>
                                </div>
                                <div class="canal">
                                    <div>
                                        <i class="fa-solid fa-hashtag"></i>
                                        discussion
                                    </div>
                                    <div>
                                        <a href="#"><i class="fa-solid fa-pen fa-xs "></i></a>
                                        <a href="#"><i class="fa-solid fa-x fa-xs"></i></a>
                                    </div>
                                </div>
                            </div>

                            <div class="categorieCanal">
                                <div class="titreCategorieCanal">
                                    <div>
                                        <i class="fa-solid fa-minus"></i>
                                        Compétition
                                    </div>
                                    <i class="fa-solid fa-plus fa-2xs"></i>
                                </div>

                                <div class="canal">
                                    <div>
                                        <i class="fa-solid fa-hashtag"></i>
                                        défis-hebdomadaires
                                    </div>
                                    <div>
                                        <a href="#"><i class="fa-solid fa-pen fa-xs "></i></a>
                                        <a href="#"><i class="fa-solid fa-x fa-xs"></i></a>
                                    </div>
                                </div>
                                <div class="canal">
                                    <div>
                                        <i class="fa-solid fa-hashtag"></i>
                                        combats-de-clans
                                    </div>
                                    <div>
                                        <a href="#"><i class="fa-solid fa-pen fa-xs "></i></a>
                                        <a href="#"><i class="fa-solid fa-x fa-xs"></i></a>
                                    </div>
                                </div>
                                <div class="canal">
                                    <div>
                                        <i class="fa-solid fa-hashtag"></i>
                                        mur-de-motivation
                                    </div>
                                    <div>
                                        <a href="#"><i class="fa-solid fa-pen fa-xs "></i></a>
                                        <a href="#"><i class="fa-solid fa-x fa-xs"></i></a>
                                    </div>
                                </div>
                                <div class="canal">
                                    <div>
                                        <i class="fa-solid fa-hashtag"></i>
                                        victoires
                                    </div>
                                    <div>
                                        <a href="#"><i class="fa-solid fa-pen fa-xs "></i></a>
                                        <a href="#"><i class="fa-solid fa-x fa-xs"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div>
                    <h3>Column 2</h3>
                    <p>Content for column 2 goes here.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div>
                    <h3>Column 3</h3>
                    <p>Content for column 3 goes here.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const canals = document.querySelectorAll('.canal');

        canals.forEach(canal => {
            canal.addEventListener('click', function() {
                canals.forEach(c => c.classList.remove('active'));
                canal.classList.add('active');
            });
        });
    });
</script>
@endsection()