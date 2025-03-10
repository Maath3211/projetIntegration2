@extends('Layouts.app')
@section('titre', 'Conversation avec ' . $user->email)



@section('style')
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" style="text/css" href="{{ asset('css/Clans/clans.css') }}">
    <link rel="stylesheet" style="text/css" href="{{ asset('css/Clans/canalClan.css') }}">
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
                                                {{ substr($message->user->email, 0, 2)  }}
                                            </div>
                                        @endif
    
                                        <div class="bubble">
                                            <strong>{{ $message->user->email ?? 'Email inconnu' }}</strong>
                                            <span class="text-muted">{{ substr($message->created_at, 11, 5) }}</span>
                                            <br>
                                            <div class="message-text">
                                                <p>{!! nl2br(e($message->message)) !!}</p>
                                            </div>
    
                                            @if ($message->fichier)
                                                @php
                                                    $extension = pathinfo($message->fichier, PATHINFO_EXTENSION);
                                                    $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif']);
                                                    $dossier = $isImage
                                                        ? 'img/conversations_photo/'
                                                        : 'fichier/conversations_fichier/';
                                                @endphp
    
                                                @if ($isImage)
                                                    <img src="{{ asset($dossier . $message->fichier) }}" alt="Image envoyée"
                                                        class="w-32 h-32 object-cover">
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
                                                {{ substr($message->user->email ?? 'Email inconnu', 0, 2) }}
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
                                            <input type="file" class="file-upload-input" name="fichier" id="fichierInput" />
                                            <label for="fichierInput" class="btn btn-secondary me-2 file-upload-btn text-white">📁</label>
                                        </div>
                                        <div id="emoji-picker-container" class="emoji-picker-container"></div>
                                        <button type="button" id="emoji-btn"
                                            name="emoji"class="btn btn-secondary me-2">😊</button>
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
    


                </div>
            </div>
        </div>

@endsection()



@section('scripts')
        <script src="{{ asset('js/Clans/clans.js') }}" crossorigin="anonymous"></script>
        <script src="{{ asset('js/Conversations/chat.js') }}"></script>

        <script>

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
            const friendId = "{{ $user->id }}"; // ID de l'ami avec qui il discute

            // Construire un canal unique basé sur les deux IDs (ex: "chat-3-7")
            const channelName = "chat-" + Math.min(userId, friendId) + "-" + Math.max(userId, friendId);


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
            console.log("Channel:", channel);













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
                    {{ isset($message) ? substr($message->user->email ?? 'Email inconnu', 0, 2) : 'rien' }}
                </div>
                <div class="bubble">
                    <strong>${data.email ?? 'Email inconnu'}</strong>
                    <span class="text-muted">{{ \Carbon\Carbon::now()->format('H:i') }}</span>
                    <br>
                    <div class="message-text">
                        ${messageContent}
                    </div>
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
                    url: "/broadcast",
                    headers: {
                        "X-Socket-Id": pusher.connection.socket_id,
                    },
                    data: formData,
                    processData: false, // Ne pas traiter les données
                    contentType: false, // Ne pas définir de type de contenu
                }).done(function(res) {


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
                    <div class="message-text">
                        ${messageContent}
                    </div>
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
                console.log("Suppression du message avec ID:", messageId); // Ajout de console
                $.ajax({
                    type: "DELETE",
                    url: `/messages/${messageId}`,
                    data: {
                        _token: "{{ csrf_token() }}"
                    }
                }).done(function(res) {
                    console.log("Réponse de suppression:", res); // Ajout de console
                    if (res.success) {
                        // Supprime le message du DOM
                        $(`#message-${messageId}`).remove();
                    } else {
                        alert("Erreur lors de la suppression du message.");
                    }
                }).fail(function() {
                    console.error("Erreur lors de la suppression du message."); // Ajout de console
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


                
@endsection
