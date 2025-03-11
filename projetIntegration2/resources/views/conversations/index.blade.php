@extends('Layouts.app')
@section('titre', 'Conversation-Ami/index')
<body>

    <style>
        body {
            background-color: #222;
            color: white;
        }
        .chat-container {
            max-width: 900px;
            margin: auto;
            border-radius: 10px;
            background: #333;
            padding: 20px;
        }
        .chat-header {
            background: #aaa;
            padding: 10px;
            border-radius: 10px;
        }
        .chat-sidebar {
            background: #544C4C;
            padding: 15px;
            border-radius: 10px;
            color: white;
        }
        .chat-messages {
            flex-grow: 1;
            min-height: 0; /* Permet au flexbox de bien fonctionner */
            height: auto;
            background: #414141;
            padding: 15px;
            height: 400px;
            overflow-y: auto;
            border-radius: 10px;
        }
        .message {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .message img {
            max-width: 100px;
            border-radius: 5px;
        }
        .bubble {
            background: white;
            padding: 10px;
            border-radius: 10px;
            margin-left: 10px;
            color: black;
        }
        .own-message {
            justify-content: flex-end;
        }
        .own-message .bubble {
            background: #A9FE77;
        }
        .received-message {
            justify-content: flex-start;
        }
        .received-message .bubble {
            background: #A9FE77;
        }
        .message-input {
            background: pink;
            padding: 10px;
            border-radius: 20px;
            width: 100%;
            border: none;
            outline: none;
        }
        .colonneMessages2 {
    display: flex;
    flex-direction: column;
    height: 100vh; /* Prendre toute la hauteur de l'écran */
}
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

@section('style')
<link rel="stylesheet" style="text/css" href="{{asset('css/Leaderboard.css')}}">
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
<style>
    .conteneurImage {
    background-image: url('{{ asset('images/ui/leaderboard.png') }}');
    background-size: cover;
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
