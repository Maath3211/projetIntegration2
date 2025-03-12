@extends('Layouts.app')
@section('titre', {{ __('chat.titre_index') }})


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
                    <div class="column"> <!-- column est en anglais parce que c'est le nom de la classe bootstrap c'est pas mon choix -->
                        <div class="conteneurImage">
                            <div class="texteSurImage">Workout Master</div>
                            {{-- <div><a href="{{ route('clan.parametres', ['id' => $clan->id]) }}"><i class="fa-solid fa-ellipsis"></i></a></div> --}}
                            {{-- Test pour tester la route clan A CHANGER --}}
                            <div></div>
                        </div>
                        <div class="conteneurCanaux">
                            <!-- Afficher amis  -->
                            <h1>Amis</h1>
                            @include('conversations.utilisateurs',['users'=>$users])

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-10 colonneMessages2">

                <!-- Contenu supprimé -->
                <div class="chat-messages" id="chat-messages">
                    <div class="presentation" style="display: flex; justify-content: center; align-items: center; height: 100%;">


                    <div class="intro-text">Bienvenue sur la page de conversation. Ici, vous pouvez discuter avec vos amis et échanger des messages en temps réel.</div>
                    </div>
                </div>

            </div>
    </div>
</div>










</body>

@endsection()
