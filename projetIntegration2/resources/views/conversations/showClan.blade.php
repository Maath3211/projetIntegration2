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
            min-height: 0;
            /* Permet au flexbox de bien fonctionner */
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
            / max-width: 100px;
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

        .separator {
            display: block;
            /* Pour qu'il se comporte comme un bloc */
            flex-basis: 100%;
            /* Dans un conteneur flex, occupe toute la ligne */
            width: 100%;
            /* S'assure qu'il prend toute la largeur disponible */
            border-top: 1px solid #ccc;
            /* Définit la ligne */
            margin: 20px 0;
            /* Espacement avant et après */
        }



        .colonneMessages2 {
            display: flex;
            flex-direction: column;
            height: 100vh;
            /* Prendre toute la hauteur de l'écran */
        }

        /* Stylisation des images dans les messages */
        .message-image {
            text-align: center;
            margin-top: 10px;
        }

        .message-img {
            max-width: 100%;
            /* Limite la largeur de l'image pour s'adapter à l'écran */
            height: auto;
            /* Garde le ratio de l'image */
            border-radius: 8px;
            /* Bordure arrondie pour l'image */
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            /* Ombre légère autour de l'image */
            margin-top: 10px;
        }
    </style>

    @section('style')
        <link rel="stylesheet" style="text/css" href="{{ asset('css/Leaderboard.css') }}">
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
                            <div class="column">
                                <!-- column est en anglais parce que c'est le nom de la classe bootstrap c'est pas mon choix -->
                                <div class="conteneurImage">
                                    <div class="texteSurImage">Workout Master</div>
                                    {{-- <div><a href="{{ route('clan.parametres', ['id' => $clan->id]) }}"><i class="fa-solid fa-ellipsis"></i></a></div> --}}
                                    <div><a href="{{ route('clan.parametres', ['id' => 1]) }}"><i
                                                class="fa-solid fa-ellipsis"></i></a></div>
                                </div>
                                <div class="conteneurCanaux">
                                    <!-- Afficher amis  -->
                                    <h1>Amis</h1>
                                    @include('conversations.utilisateurs', ['users' => $users])
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 colonneMessages2">




                        <!-- Contenu supprimé -->
                        <div class="chat-messages" id="chat-messages">

                            @if ($messages->hasMorePages())
                                <div class="div text-center">
                                    <a href="{{ $messages->nextPageUrl() }}" class="btn btn-light">
                                        Voir les messages précédent
                                    </a>
                                </div>
                            @endif

                            @foreach ($messages as $message)
                                <div class="messageTotal" id="message-{{ $message->id }}">
                                    <div
                                        class="message {{ $message->idEnvoyer == auth()->id() ? 'own-message' : 'received-message' }}">
                                        @if ($message->idEnvoyer == auth()->id())
                                            <!-- Bouton de suppression visible uniquement pour l'auteur -->
                                            <button class="delete-btn" data-id="{{ $message->id }}">🗑️</button>
                                        @else
                                            <div class="avatar bg-primary text-white rounded-circle p-2">
                                                {{ substr($message->user->email, 0, 2) }}
                                            </div>
                                        @endif

                                        <div class="bubble">
                                            <strong>{{ $message->user->email }}</strong>
                                            <span class="text-muted">{{ substr($message->created_at, 11, 5) }}</span>
                                            <br>
                                            <p>{!! nl2br(e($message->message)) !!}</p>

                                            <!-- Vérifier si une image est attachée au message -->
                                            @if ($message->photo)
                                                <div class="message-image">
                                                    <img src="{{ $message->photo }}" alt="Image" class="message-img">

                                                </div>
                                            @endif

                                        </div>


                                        @if ($message->idEnvoyer != auth()->id())
                                            <!-- Pas de bouton de suppression pour les messages reçus -->
                                        @else
                                            <div class="avatar bg-primary text-white rounded-circle p-2">
                                                {{ substr($message->user->email, 0, 2) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="separator"></div>
                                </div>
                            @endforeach




                            @if ($messages->previousPageUrl())
                                <div class="div text-center">
                                    <a href="{{ $messages->previousPageUrl() }}" class="btn btn-light">
                                        Voir les messages suivant
                                    </a>
                                </div>
                            @endif

                        </div>

                        <div class="d-flex align-items-center mt-3">
                            <form action="" method="post" enctype="multipart/form-data" class="d-flex flex-grow-1">
                                @csrf
                                <div class="form-group d-flex align-items-center w-100">
                                    <input type="file" class="btn btn-secondary" name="photo" accept="image/*"
                                        placeholder="➕">
                                    <button class="btn btn-secondary me-2">😊</button>


                                    <input type="textarea" class="message-input form-control flex-grow-1" name="content"
                                        placeholder="Écris un message...">
                                    <button class="btn btn-primary ms-2" type="submit">Submit</button>
                                </div>
                            </form>
                        </div>
                        <u>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </u>



















                    </div>
                    <div class="col-md-2 colonneMembres">
                        <div class="contenuScrollableMembres">
                            <div class="membre">
                                <a href="#">
                                    <img src="{{ asset('img/Utilisateurs/utilisateur1.jpg') }}">
                                    <div>
                                        <strong>ADMIN</strong> - Tommy Jackson
                                    </div>
                                </a>
                            </div>
                            <div class="membre">
                                <a href="#">
                                    <img src="{{ asset('img/Utilisateurs/utilisateur2.jpg') }}">
                                    <div>
                                        AverageGymGoer
                                    </div>
                                </a>
                            </div>
                            <div class="membre">
                                <a href="#">
                                    <img src="{{ asset('img/Utilisateurs/utilisateur3.jpg') }}">
                                    <div>
                                        NotTheAverageGuy
                                    </div>
                                </a>
                            </div>
                            <div class="membre">
                                <a href="#">
                                    <img src="{{ asset('img/Utilisateurs/utilisateur4.jpg') }}">
                                    <div>
                                        Julie St-Aubin
                                    </div>
                                </a>
                            </div>
                            <div class="membre">
                                <a href="#">
                                    <img src="{{ asset('img/Utilisateurs/utilisateur5.avif') }}">
                                    <div>
                                        Gnulons
                                    </div>
                                </a>
                            </div>
                            <div class="membre">
                                <a href="#">
                                    <img src="{{ asset('img/Utilisateurs/utilisateur6.jpg') }}">
                                    <div>
                                        Jack Jacked
                                    </div>
                                </a>
                            </div>
                            <div class="membre">
                                <a href="#">
                                    <img src="{{ asset('img/Utilisateurs/utilisateur7.jpg') }}">
                                    <div>
                                        Sophie
                                    </div>
                                </a>
                            </div>
                            <div class="membre">
                                <a href="#">
                                    <img src="{{ asset('img/Utilisateurs/utilisateur8.jpg') }}">
                                    <div>
                                        Lucia Percada
                                    </div>
                                </a>
                            </div>
                            <div class="membre">
                                <a href="#">
                                    <img src="{{ asset('img/Utilisateurs/utilisateur9.jpg') }}">
                                    <div>
                                        Stevie
                                    </div>
                                </a>
                            </div>
                            <div class="membre">
                                <a href="#">
                                    <img src="{{ asset('img/Utilisateurs/utilisateur11.jpg') }}">
                                    <div>
                                        Tom
                                    </div>
                                </a>
                            </div>
                            <div class="membre">
                                <a href="#">
                                    <img src="{{ asset('img/Utilisateurs/utilisateur12.jpg') }}">
                                    <div>
                                        Bluestack
                                    </div>
                                </a>
                            </div>
                            <div class="membre">
                                <a href="#">
                                    <img src="{{ asset('img/Utilisateurs/utilisateur13.jpg') }}">
                                    <div>
                                        CoolCarl123
                                    </div>
                                </a>
                            </div>
                            <div class="membre">
                                <a href="#">
                                    <img src="{{ asset('img/Utilisateurs/utilisateur14.webp') }}">
                                    <div>
                                        Sylvain
                                    </div>
                                </a>
                            </div>
                            <div class="membre">
                                <a href="#">
                                    <img src="{{ asset('img/Utilisateurs/utilisateur15.jpg') }}">
                                    <div>
                                        Ghost
                                    </div>
                                </a>
                            </div>
                            <div class="membre">
                                <a href="#">
                                    <img src="{{ asset('img/Utilisateurs/utilisateur16.jpg') }}">
                                    <div>
                                        Coach Noah
                                    </div>
                                </a>
                            </div>
                            <div class="membre">
                                <a href="#">
                                    <img src="{{ asset('img/Utilisateurs/utilisateur17.jpg') }}">
                                    <div>
                                        MotivationGuy
                                    </div>
                                </a>
                            </div>
                            <div class="membre">
                                <a href="#">
                                    <img src="{{ asset('img/Utilisateurs/utilisateur18.jpg') }}">
                                    <div>
                                        xXDarkSlayerXx
                                    </div>
                                </a>
                            </div>
                            <div class="membre">
                                <a href="#">
                                    <img src="{{ asset('img/Utilisateurs/utilisateur19.jpg') }}">
                                    <div>
                                        CalisthenicGod_1
                                    </div>
                                </a>
                            </div>
                            <div class="membre">
                                <a href="#">
                                    <img src="{{ asset('img/Utilisateurs/utilisateur20.jpg') }}">
                                    <div>
                                        Gymcord#654302
                                    </div>
                                </a>
                            </div>
                            <div class="membre">
                                <a href="#">
                                    <img src="{{ asset('img/Utilisateurs/utilisateur4.jpg') }}">
                                    <div>
                                        Julia Julia
                                    </div>
                                </a>
                            </div>
                            <div class="membre">
                                <a href="#">
                                    <img src="{{ asset('img/Utilisateurs/utilisateur2.jpg') }}">
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
            const friendId = "{{ $user->id }}"; // ID de l'ami avec qui il discute :: Plutot le clan avec qui il discute

            const channelName = "chat-" + friendId;

            console.log("Subscribing to:", channelName);

            const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
                cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
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
            channel.bind('event-group', function(data) {
                // Vérifier si le message a été supprimé
                if (data.deleted === true) {
                    // Si le message a été supprimé, le retirer du DOM
                    $(`#message-${data.last_id}`).remove();
                } else {
                    // Sinon, ajouter le message normalement
                    $("#chat-messages").append(`
            <div class="messageTotal" id="message-${data.last_id}">
                <div class="message received-message">
                    <div class="avatar bg-primary text-white rounded-circle p-2">
                        <!-- Affiche la première lettre de l'email -->
                        {{ isset($message) ? substr($message->user->email, 0, 2) : 'rien' }}
                    </div>
                    <div class="bubble">
                        <strong>{{ isset($message) ? $message->user->email : 'rien' }}</strong>
                        <span class="text-muted">{{ \Carbon\Carbon::now()->format('H:i') }}</span>
                        <br>
                        <p>${data.message}</p>
                        ${data.photo ? `<div class="message-image"><img src="${data.photo}" alt="Image" class="message-img"></div>` : ''}
                    </div>
                </div>
                <div class="separator"></div>
            </div>
        `);
                    // Scroll au bas des messages
                    $("#chat-messages").scrollTop($("#chat-messages")[0].scrollHeight);
                }
            });






            // Envoyer un message via AJAX
            $("form").submit(function(e) {
                e.preventDefault();

                let formData = new FormData();
                formData.append("_token", "{{ csrf_token() }}");
                formData.append("message", $("input[name='content']").val());
                formData.append("from", userId);
                formData.append("to", friendId);

                let fileInput = $("input[name='photo']")[0]; // Assurez-vous que l'input file a name='image'
                if (fileInput.files.length > 0) {
                    formData.append("photo", fileInput.files[0]); // Ajoute l'image si présente
                }

                $.ajax({
                    type: "POST",
                    url: "/broadcastClan",
                    headers: {
                        "X-Socket-Id": pusher.connection.socket_id,
                    },
                    data: formData,
                    processData: false, // Ne pas traiter les données
                    contentType: false, // Ne pas définir de type de contenu
                }).done(function(res) {
                    console.log(res);
                    $("input[name='photo']").val(""); // Réinitialiser l'input file 'photo'

                    

                    let avatarText = res.sender_email.substring(0, 2);
                    let messageContent = res.message ? `<p>${res.message}</p>` : "";
                    let imageContent = res.photo ?
                        `<img src="${res.photo}" class="message-image" alt="Image envoyée">` : "";

                    $("#chat-messages").append(`
            <div class="messageTotal" id="message-${res.last_id}">
                <div class="message own-message">
                    <button class="delete-btn" data-id="${res.last_id}">🗑️</button>
                    <div class="bubble">
                        <strong>${res.sender_email}</strong>
                        <span class="text-muted">{{ \Carbon\Carbon::now()->format('H:i') }}</span>
                        <br>
                        ${messageContent}
                        ${imageContent}
                    </div>
                    <div class="ml-4 avatar bg-primary text-white rounded-circle p-2">${avatarText}</div>
                </div>
                <div class="separator"></div>
            </div>
        `);

                    $("input[name='content']").val("");
                    $("input[name='image']").val(""); // Réinitialise l'input file
                    $("#chat-messages").scrollTop($("#chat-messages")[0].scrollHeight);
                });
            });




            // ---------------------------
            // Gestion de la suppression des messages
            // ---------------------------

            // Lorsqu'un utilisateur clique sur le bouton de suppression
            $(document).on('click', '.delete-btn', function(e) {
                e.preventDefault();
                let messageId = $(this).data('id');
                $.ajax({
                    type: "DELETE",
                    url: `/messages/${messageId}`,
                    data: {
                        _token: "{{ csrf_token() }}"
                    }
                }).done(function(res) {
                    if (res.success) {
                        // Supprime le message du DOM
                        $(`#message-${messageId}`).remove();
                    } else {
                        alert("Erreur lors de la suppression du message.");
                    }
                }).fail(function() {
                    alert("Erreur lors de la suppression du message.");
                });
            });

            // Écouter l'événement de suppression spécifique diffusé par Pusher
            channel.bind('message-deleted', function(data) {
                console.log("Message supprimé:", data); // Affiche l'ID du message supprimé pour le débogage
                // Supprime le message correspondant du DOM
                $(`#message-${data.messageId}`).remove();
            });
        </script>
    </body>

@endsection()
