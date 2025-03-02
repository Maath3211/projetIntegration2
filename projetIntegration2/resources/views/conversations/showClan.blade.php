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
            /* Take full screen height */
        }

        /* Message image styling */
        .message-image {
            text-align: center;
            margin: 8px 0;
            max-width: 300px;
            /* Limit maximum width */
        }

        .message-img {
            width: 100%;
            max-height: 100%;
            object-fit: cover;
            /* Maintain aspect ratio while covering area */

            /* Rounded corners */

            /* Subtle shadow */
            transition: transform 0.2s;
            /* Smooth hover effect */
        }

        .message-img:hover {
            transform: scale(1.02);
            /* Slight zoom on hover */
        }

        .message img {
            min-width: 60px;
            max-width: 60px;
            min-height: 60px;
            max-height: 60px;
            border-radius: 0px;
            margin: 10px;
        }

        /* File upload styling */
        .file-upload-input {
            position: absolute;
            opacity: 0;
            z-index: -1;
        }



        /* Changer la couleur du bouton au survol */
        .file-upload-label:hover {
            background-color: #0056b3;
            /* Couleur plus foncée au survol */
        }





        #preview-container {
            margin-bottom: 10px;
            text-align: center;
        }

        .preview-img {
            max-width: 100%;
            height: 250px;
            border-radius: 5px;
            margin-bottom: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }











        .emoji-picker-container {
    position: absolute;
    width: 300px;
    height: 400px;
    background: #fff;
    border: 1px solid #ccc;
    border-radius: 10px;
    display: none;
    flex-direction: column;
    padding: 10px;
    z-index: 1000;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    opacity: 0;
    transform: translateY(10px); /* Décalage initial */
    transition: opacity 0.2s ease, transform 0.2s ease;
}

.emoji-picker-container.active {
    display: flex;
    opacity: 1;
    transform: translateY(0); /* Retourne à sa position normale */
}


#emoji-picker-container {
    position: absolute;
    bottom: 60px; /* Ajuste selon la hauteur de ton champ texte */
    left: 15%;
    background: white;
    border-radius: 8px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    width: 250px;
    max-height: 300px;
    overflow: hidden;
    z-index: 1000;
}
                .emoji-search-container {
                    margin-bottom: 10px;
                }
            
                .emoji-search-container input {
                    width: 100%;
                    padding: 5px;
                    border: 1px solid #ccc;
                    border-radius: 5px;
                }
            
                #emoji-list {
                    flex-grow: 1;
                    overflow-y: auto;
                }
            
                .emoji-item {
                    display: inline-block;
                    padding: 5px;
                    font-size: 24px;
                    cursor: pointer;
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

                                            @if ($message->fichier)
                                                @php
                                                    $extension = pathinfo($message->fichier, PATHINFO_EXTENSION);
                                                    $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif']);
                                                    $dossier = $isImage
                                                        ? 'img/conversations_photo/'
                                                        : 'fichier/conversations_fichier/';
                                                @endphp

                                                @if ($isImage)
                                                    <img src="{{ asset($dossier . $message->fichier) }}"
                                                        alt="Image envoyée" class="w-32 h-32 object-cover">
                                                @else
                                                    <a href="{{ asset($dossier . $message->fichier) }}" target="_blank"
                                                        class="text-blue-500">
                                                        📄 Télécharger {{ $message->fichier }}
                                                    </a>
                                                @endif
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
                                <div class="form-group d-flex flex-column w-100">
                                    <!-- Conteneur pour afficher l'aperçu de l'image -->
                                    <div id="preview-container"></div>

                                    <!-- Autres contrôles du formulaire -->
                                    <div class="d-flex align-items-center mt-3">
                                        <div class="file-upload-wrapper me-2">
                                            <input type="file" class="file-upload-input" name="fichier"
                                                id="fichierInput" />
                                            <label for="fichierInput" class="file-upload-btn text-white">📁</label>
                                        </div>
                                        <div id="emoji-picker-container" class="emoji-picker-container"></div>
                                        <button type="button" id="emoji-btn" name="emoji"class="btn btn-secondary me-2">😊</button>
                                        <input id="message" type="textarea" class="message-input form-control flex-grow-1"
                                            name="content" placeholder="Écris un message...">
                                        <button class="btn btn-primary ms-2" type="submit">Submit</button>
                                    </div>
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




    <script src="{{ asset('js/Conversations/chat.js') }}"></script>

        <script>
            function formatMessage(message) {
                const maxLength = 50; // Maximum length before adding a newline
                let formattedMessage = '';
                for (let i = 0; i < message.length; i += maxLength) {
                    formattedMessage += message.substring(i, i + maxLength) + '\n';
                }
                return formattedMessage;
            }








            // ---------------------------
            // Lorsqu'un fichier est sélectionné
            $('#fichierInput').on('change', function() {
                var input = this;
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        // Crée un conteneur avec l'image et un bouton "X" pour annuler
                        $('#preview-container').html(
                            '<div style="position: relative; display: inline-block;">' +
                            '<img src="' + e.target.result +
                            '" alt="Aperçu de l\'image sélectionnée" class="preview-img">' +
                            '<button id="cancel-preview" ' +
                            'style="position: absolute; top: 5px; right: 5px; background: rgba(0,0,0,0.7); border: none; color: white; font-size: 20px; line-height: 20px; width: 25px; height: 25px; border-radius: 50%; cursor: pointer;">' +
                            '&times;' +
                            '</button>' +
                            '</div>'
                        );
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            });

            // Lorsqu'on clique sur le bouton "X"
            $(document).on('click', '#cancel-preview', function() {
                $('#preview-container').empty();
                $('#fichierInput').val('');
            });








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




            // Recevoir les messages de la conversation
            channel.bind('event-group', function(data) {
                // Vérifier si le message a été supprimé
                if (data.deleted === true) {
                    // Si le message a été supprimé, le retirer du DOM
                    $(`#message-${data.last_id}`).remove();
                } else {
                    // Détermine le contenu du message (texte, image ou fichier)
                    let messageContent = data.message ? `<p>${data.message}</p>` : "";
                    console.log(data);
                    // Déterminer si c'est une image ou un fichier à télécharger
                    let fileExtension = data.photo ? data.photo.split('.').pop().toLowerCase() : "";
                    let isImage = ["jpg", "jpeg", "png", "gif"].includes(fileExtension);
                    let fileContent = "";

                    if (data.photo) {
                        if (isImage) {
                            fileContent = `<div class="message-image">
                    <img src="/img/conversations_photo/${data.photo}" alt="Image" class="message-img">
                </div>`;
                        } else {
                            const fileName = data.photo.split('/').pop(); // Récupérer le nom du fichier
                            fileContent = `<div class="message-file">
                    <a href="${data.photo}" target="_blank" download class="btn btn-sm btn-primary">
                        📎 Télécharger ${fileName}
                    </a>
                </div>`;
                        }
                    }

                    // Ajouter le message au chat
                    $("#chat-messages").append(`
            <div class="messageTotal" id="message-${data.last_id}">
                <div class="message received-message">
                    <div class="avatar bg-primary text-white rounded-circle p-2">
                        <!-- Affiche la première lettre de l'email -->
                        {{ isset($message) ? substr($message->user->email, 0, 2) : 'rien' }}
                    </div>
                    <div class="bubble">
                        <strong>${data.sender_email ? data.sender_email : ''}</strong>
                        <span class="text-muted">{{ \Carbon\Carbon::now()->format('H:i') }}</span>
                        <br>
                        ${messageContent}
                        ${fileContent}
                    </div>
                </div>
                <div class="separator"></div>
            </div>
        `);

                    // Scroll au bas des messages
                    $("#chat-messages").scrollTop($("#chat-messages")[0].scrollHeight);
                }
            });






            $("form").submit(function(e) {
                e.preventDefault();

                let formData = new FormData();
                formData.append("_token", "{{ csrf_token() }}");
                formData.append("message", $("input[name='content']").val());
                formData.append("from", userId);
                formData.append("to", friendId);

                let fileInput = $("input[name='fichier']")[0]; // Assurez-vous que l'input file a name='fichier'
                if (fileInput.files.length > 0) {
                    formData.append("fichier", fileInput.files[0]); // Ajoute l'image ou le fichier si présent
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

                    $("#preview-container").html("");
                    $("input[name='fichier']").val(""); // Réinitialiser l'input file

                    let avatarText = res.sender_email.substring(0, 2);
                    let messageContent = res.message ? `<p>${res.message}</p>` : "";

                    // Déterminer si c'est une image ou un fichier à télécharger
                    let fileExtension = res.fichier ? res.fichier.split('.').pop().toLowerCase() : "";
                    let isImage = ["jpg", "jpeg", "png", "gif"].includes(fileExtension);
                    let fileContent = "";

                    if (res.fichier) {
                        if (isImage) {
                            fileContent =
                                `<img src="${res.fichier}" class="message-image" alt="Image envoyée">`;
                        } else {
                            fileContent = `<a href="../${res.fichier}" target="_blank" class="text-blue-500">
                    📄 Télécharger ${res.fichier.split('/').pop()}
                </a>`;
                        }
                    }

                    $("#chat-messages").append(`
            <div class="messageTotal" id="message-${res.last_id}">
                <div class="message own-message">
                    <button class="delete-btn" data-id="${res.last_id}">🗑️</button>
                    <div class="bubble">
                        <strong>${res.sender_email}</strong>
                        <span class="text-muted">{{ \Carbon\Carbon::now()->format('H:i') }}</span>
                        <br>
                        ${messageContent}
                        ${fileContent}
                    </div>
                    <div class="ml-4 avatar bg-primary text-white rounded-circle p-2">${avatarText}</div>
                </div>
                <div class="separator"></div>
            </div>
        `);

                    $("input[name='content']").val("");
                    $("#chat-messages").scrollTop($("#chat-messages")[0].scrollHeight);
                }).fail(function(xhr, status, error) {
                    console.error("Erreur d'envoi :", error);
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
