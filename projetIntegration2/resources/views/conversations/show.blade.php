@extends('Layouts.app')
@section('titre', 'Conversation-Ami')
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
            background: #2E2222;
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
            background: pink;
        }
        .received-message {
            justify-content: flex-start;
        }
        .received-message .bubble {
            background: lightblue;
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
                            <div><a href="{{ route('clan.parametres', ['id' => 1]) }}"><i class="fa-solid fa-ellipsis"></i></a></div>
                        </div>
                        <div class="conteneurCanaux">
                            <!-- Afficher amis  -->
                            <h1>Amis</h1>
                            @include('conversations.utilisateurs',['users'=>$users])
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8 colonneMessages2">




                <!-- Contenu supprimé -->
                    <div class="chat-messages" id="chat-messages">
    
                        @if ($messages->hasMorePages())
                            <div class="div text-center">
                                <a href="{{$messages->nextPageUrl()}}" class="btn btn-light">
                                    Voir les messages précédent
                                </a>
                            </div>
                        @endif
    
                        @foreach ($messages as $message)
                        <div class="message {{ $message->idEnvoyer == auth()->id() ? 'own-message' : 'received-message' }}">
                            <div class="avatar bg-primary text-white rounded-circle p-2">{{ substr($user->email, 0, 2) }}</div>
                            <div class="bubble">
                                <strong>{{$message->email}}</strong> 
                                <span class="text-muted">{{ substr($message->created_at, 11, 5) }}</span>
                                <br>
                                <p>{!! nl2br(e($message->message)) !!}</p>
                            </div>
                        </div>
                        <hr>
                        @endforeach
    
                        @if ($messages->previousPageUrl())
                        <div class="div text-center">
                            <a href="{{$messages->previousPageUrl()}}" class="btn btn-light">
                                Voir les messages suivant
                            </a>
                        </div>
                        @endif
    
                    </div>
    
                    <div class="d-flex align-items-center mt-3">
                        <button class="btn btn-secondary me-2">➕</button>
                        <button class="btn btn-secondary me-2">😊</button>
                        <form action="" method="post" class="d-flex flex-grow-1">
                            @csrf
                            <div class="form-group d-flex align-items-center w-100">
                                <input type="textarea" class="message-input form-control flex-grow-1" name="content" placeholder="Écris un message...">
                                <button class="btn btn-primary ms-2" type="submit">Submit</button>
                            </div>
                        </form>
                    </div>
                    <u>
                        @foreach ($errors->all() as $error)
                        <li>{{$error}}</li>
                        @endforeach
                    </u>



















            </div>
            <div class="col-md-2 colonneMembres">
                <div class="contenuScrollableMembres">
                    <div class="membre">
                        <a href="#">
                            <img src="{{asset('img/Utilisateurs/utilisateur1.jpg')}}" > 
                            <div>
                                <strong>ADMIN</strong> - Tommy Jackson
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="#">
                            <img src="{{asset('img/Utilisateurs/utilisateur2.jpg')}}" > 
                            <div>
                                AverageGymGoer
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="#">
                            <img src="{{asset('img/Utilisateurs/utilisateur3.jpg')}}" > 
                            <div>
                                NotTheAverageGuy
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="#">
                            <img src="{{asset('img/Utilisateurs/utilisateur4.jpg')}}" > 
                            <div>
                                Julie St-Aubin  
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="#">
                            <img src="{{asset('img/Utilisateurs/utilisateur5.avif')}}" > 
                            <div>
                                Gnulons  
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="#">
                            <img src="{{asset('img/Utilisateurs/utilisateur6.jpg')}}" > 
                            <div>
                                Jack Jacked
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="#">
                            <img src="{{asset('img/Utilisateurs/utilisateur7.jpg')}}" > 
                            <div>
                                Sophie  
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="#">
                            <img src="{{asset('img/Utilisateurs/utilisateur8.jpg')}}" > 
                            <div>
                                Lucia Percada
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="#">
                            <img src="{{asset('img/Utilisateurs/utilisateur9.jpg')}}" > 
                            <div>
                                Stevie  
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="#">
                            <img src="{{asset('img/Utilisateurs/utilisateur11.jpg')}}" > 
                            <div>
                                Tom  
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="#">
                            <img src="{{asset('img/Utilisateurs/utilisateur12.jpg')}}" > 
                            <div>
                                Bluestack  
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="#">
                            <img src="{{asset('img/Utilisateurs/utilisateur13.jpg')}}" > 
                            <div>
                                CoolCarl123
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="#">
                            <img src="{{asset('img/Utilisateurs/utilisateur14.webp')}}" > 
                            <div>
                                Sylvain  
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="#">
                            <img src="{{asset('img/Utilisateurs/utilisateur15.jpg')}}" > 
                            <div>
                                Ghost  
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="#">
                            <img src="{{asset('img/Utilisateurs/utilisateur16.jpg')}}" > 
                            <div>
                                Coach Noah  
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="#">
                            <img src="{{asset('img/Utilisateurs/utilisateur17.jpg')}}" > 
                            <div>
                                MotivationGuy  
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="#">
                            <img src="{{asset('img/Utilisateurs/utilisateur18.jpg')}}" > 
                            <div>
                                xXDarkSlayerXx  
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="#">
                            <img src="{{asset('img/Utilisateurs/utilisateur19.jpg')}}" > 
                            <div>
                                CalisthenicGod_1  
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="#">
                            <img src="{{asset('img/Utilisateurs/utilisateur20.jpg')}}" > 
                            <div>
                                Gymcord#654302  
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="#">
                            <img src="{{asset('img/Utilisateurs/utilisateur4.jpg')}}" > 
                            <div>
                                Julia Julia    
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="#">
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
</div>












    <script>
        // Scroll to the bottom of the chat messages
        document.addEventListener("DOMContentLoaded", function() {
            var chatMessages = document.getElementById("chat-messages");
            chatMessages.scrollTop = chatMessages.scrollHeight;
        });

        const userId = "{{ auth()->id() }}"; // ID de l'utilisateur connecté
        const friendId = "{{ $user->id }}";  // ID de l'ami avec qui il discute

        const channelName = "chat-" + Math.min(userId, friendId) + "-" + Math.max(userId, friendId);

        console.log("Subscribing to:", channelName);

        const pusher = new Pusher('{{config('broadcasting.connections.pusher.key')}}', {
            cluster: '{{config('broadcasting.connections.pusher.options.cluster')}}',
            encrypted: true
        });

        pusher.connection.bind('connected', function() {
            console.log('Successfully connected to Pusher');
        });

        pusher.connection.bind('error', function(err) {
            console.error('Connection error:', err);
        });

        const channel = pusher.subscribe(channelName);

        // Recevoir les messages de la conversation privée
        channel.bind('mon-event', function(data) {
            //console.log("Message reçu:", data);
            $("#chat-messages").append(`
            <div class="message received-message">
                <div class="avatar bg-primary text-white rounded-circle p-2">{{ substr($user->email, 0, 2) }}</div>
                <div class="bubble">
                    <span class="text-muted">{{ \Carbon\Carbon::now()->format('H:i') }}</span>
                    <br>
                    <p>${data.message}</p>
                </div>
            </div>
            <hr>

            `);
            $("#chat-messages").scrollTop($("#chat-messages")[0].scrollHeight);
        });

        // Envoyer un message via AJAX
        $("form").submit(function(e) {
            e.preventDefault();
            //console.log("Formulaire envoyé!");

            $.ajax({
                type: "POST",
                url: "/broadcast",
                headers: {
                    'X-Socket-Id': pusher.connection.socket_id
                },
                data: {
                    _token: "{{ csrf_token() }}",
                    message: $("input[name='content']").val(),
                    from: userId, // Ajoute l'ID de l'utilisateur connecté
                    to: friendId // Ajoute l'ID du destinataire
                }
            }).done(function(res) {
                //console.log("Message envoyé:", $("input[name='content']").val());
                $("#chat-messages").append(`
                
                    <div class="message own-message">
                                        <div class="avatar bg-primary text-white rounded-circle p-2">{{ substr($user->email, 0, 2) }}</div>
                        <div class="bubble">
                            <span class="text-muted">{{ \Carbon\Carbon::now()->format('H:i') }}</span>
                            <br>
                            <p>${$("input[name='content']").val()}</p>
                        </div>
                        
                        
                    </div>
                    <hr>
                `);
                $("input[name='content']").val("");
                $("#chat-messages").scrollTop($("#chat-messages")[0].scrollHeight);
            });
        });
    </script>
</body>

@endsection()