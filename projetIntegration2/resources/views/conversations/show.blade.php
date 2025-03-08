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
                                                {{ substr($message->user->email, 0, 2) }}
                                            </div>
                                        @endif
    
                                        <div class="bubble">
                                            <strong>{{ $message->user->email }}</strong>
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






        <script src="{{ asset('js/Clans/clans.js') }}" crossorigin="anonymous"></script>
        <script src="{{ asset('js/Conversations/chat.js') }}"></script>

        <script>
            const userId = "{{ auth()->id() }}"; // ID de l'utilisateur connecté
            const friendId = "{{ $user->id }}"; // ID de l'ami avec qui il discute

            // Construire un canal unique basé sur les deux IDs (ex: "chat-3-7")
            const channelName = "chat-" + Math.min(userId, friendId) + "-" + Math.max(userId, friendId);

            console.log("Subscribing to:", channelName);

            const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
                cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
                encrypted: true
            });

            const channel = pusher.subscribe(channelName);
            console.log("Channel:", channel);

            // Recevoir les messages de la conversation privée
            channel.bind('mon-event', function(data) {
                console.log("Message reçu:", data.message);
                $("#chat-messages").append(`
        <div class="message">
            <div class="bubble">${data.message}</div>
        </div>
    `);
                $("#chat-messages").scrollTop($("#chat-messages")[0].scrollHeight);
            });

            // Envoyer un message via AJAX
            $("form").submit(function(e) {
                e.preventDefault();
                console.log("Formulaire envoyé!");

                $.ajax({
                    type: "POST",
                    url: "/broadcast",
                    headers: {
                        'X-Socket-Id': pusher.connection.socket_id
                    },
                    data: {
                        _token: "{{ csrf_token() }}",
                        message: $("input[name='content']").val(),
                        to: friendId // Ajoute l'ID du destinataire
                    }
                }).done(function(res) {
                    console.log("Message envoyé:", $("input[name='content']").val());
                    $("#chat-messages").append(`
            <div class="message own-message">
                <div class="bubble">${$("input[name='content']").val()}</div>
            </div>
        `);
                    $("input[name='content']").val("");
                    $("#chat-messages").scrollTop($("#chat-messages")[0].scrollHeight);
                });
            });
        </script>


                
@endsection
