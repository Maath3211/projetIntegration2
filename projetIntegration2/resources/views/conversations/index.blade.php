@extends('layouts.app')
@section('titre', __('chat.titre_index'))


@section('style')
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" style="text/css" href="{{ asset('css/Clans/clans.css') }}">
    <link rel="stylesheet" style="text/css" href="{{ asset('css/Clans/canalClan.css') }}">
@endsection()


@section('contenu')

    <style>
        .intro-text {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            color: black;
        }
    </style>

    <div class="contenuPrincipal">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-2 colonneCanaux">
                    <div class="container">
                        <div class="column">
                            <!-- column est en anglais parce que c'est le nom de la classe bootstrap c'est pas mon choix -->
                            <div class="conteneurImage">
                                <div class="col-md-12 d-flex gap-4 justify-content-center">
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="texteSurImage">{{__('chat.ajouter_ami')}}</div>
                                        <a href="{{ route('amis.index') }}">
                                            <div class="w-16 h-16 rounded-full overflow-hidden bullePersonnalisee">
                                                <i class="fa-solid fa-user-plus fa-xl"></i>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="d-flex flex-column align-items-center">
                                        <div class="texteSurImage">{{__('chat.rejoindre_clan')}}</div>
                                        <a href="{{ route('clans.recherche') }}">
                                            <div class="w-16 h-16 rounded-full overflow-hidden bullePersonnalisee">
                                                <i class="fa-solid fa-users fa-xl"></i>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="conteneurCanaux">
                                <!-- Afficher amis  -->
                                <h1>{{__('chat.amis')}}</h1>
                                @include('conversations.utilisateurs', ['users' => $users])

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-10 colonneMessages2">

                    <!-- Contenu supprimé -->
                    <div class="chat-messages" id="chat-messages">
                        <div class="presentation"
                            style="display: flex; justify-content: center; align-items: center; height: 100%;">


                            <div class="intro-text">{{__('chat.bienvenue')}}</div>
                        </div>
                    </div>

                </div>
            </div>
        </div>










        </body>

@endsection()
