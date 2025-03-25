@extends('layouts.app')
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
                                    <h1>{{ __('chat.amis') }}</h1>
                                    @include('conversations.utilisateurs', ['users' => $users])
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="col-md-10 colonneMessages2">
                        <!-- Contenu supprimé -->
                        <div class="chat-messages" id="chat-messages">

                            @if ($messages->hasMorePages())
                                <div class="div text-center">
                                    <a href="{{ $messages->nextPageUrl() }}" class="btn btn-light">
                                        {{ __('chat.voir_messages_precedents') }}
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
                                                {{ substr($message->from->nom, 0, 2)  }}
                                            </div>
                                        @endif

                                        <div class="bubble">
                                            <strong>{{ $message->from->email}}</strong>
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
                                            <div class="avatar ">
                                                <img src="{{ asset($message->from->imageProfil) }}" alt="Image de profil" class="w-32 h-32 object-cover">
                                            </div>
                                        @endif
                                    </div>
                                    <div class="separator"></div>
                                </div>
                            @endforeach




                            @if ($messages->previousPageUrl())
                                <div class="div text-center">
                                    <a href="{{ $messages->previousPageUrl() }}" class="btn btn-light">
                                        {{ __('chat.voir_messages_suivants') }}
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
                                            name="content" placeholder="{{ __('chat.ecrire_message') }}">
                                        <button class="btn btn-primary ms-2" id="BoutonSoumettre" type="submit">{{ __('chat.soumettre') }}</button>
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

    //*
    //**
    //** Avertissement : Ce code utilise Pusher pour la diffusion en temps réel.
    //** Pusher pour la raison qui m'echape ne fonctionne pas sur un js a pars
    //** l'interieur d'un script blade, donc je suis obligé de le mettre ici.
    //** Je ne sais pas si c'est la bonne pratique, mais je n'ai pas trouvé d'autre solution.
    //**
    //** Et malheureusement il y a beaucoup de code en englais
    //** Encore une fois
    //** Merci pusher
    //**
    //*


    const utilisateurId = "{{ auth()->id() }}"; // ID de l'utilisateur connecté
    const amiId = "{{ $user->id }}"; // ID de l'ami avec qui il discute

    // Construire un canal unique basé sur les deux IDs (ex: "chat-3-7")
    const nomCanal = "chat-" + Math.min(utilisateurId, amiId) + "-" + Math.max(utilisateurId, amiId);

    console.log("Abonnement au canal :", nomCanal);

    const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
        cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
        encrypted: true
    });

    pusher.connection.bind('connected', function() {
        console.log('Connecté avec succès à Pusher');
    });

    pusher.connection.bind('error', function(erreur) {
        console.error('Erreur de connexion :', erreur);
    });

    const canal = pusher.subscribe(nomCanal);
    console.log("Canal :", canal);

    // Fonction pour échapper les caractères spéciaux
    function echapperHtml(nonSecurise) {
        return nonSecurise
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    // Recevoir les messages de la conversation
    canal.bind('mon-event', function(donnees) {
        // Vérifier si le message a été supprimé
        if (donnees.supprimer === true) {
            // Si le message a été supprimé, le retirer du DOM
            $(`#message-${donnees.dernier_id}`).remove();
        } else {
            // Détermine le contenu du message (texte, image ou fichier)
            let contenuMessage = donnees.message ? `<p>${echapperHtml(donnees.message)}</p>` : "";
            console.log(donnees);
            // Déterminer si c'est une image ou un fichier à télécharger
            let extensionFichier = donnees.photo ? donnees.photo.split('.').pop().toLowerCase() : "";
            let estImage = ["jpg", "jpeg", "png", "gif"].includes(extensionFichier);
            let contenuFichier = "";

            if (donnees.photo) {
                if (estImage) {
                    contenuFichier = `<div class="message-image">
                        <img src="/img/conversations_photo/${echapperHtml(donnees.photo)}" alt="Image" class="message-img">
                    </div>`;
                } else {
                    const nomFichier = donnees.photo.split('/').pop(); // Récupérer le nom du fichier
                    contenuFichier = `<div class="message-file">
                        <a href="${echapperHtml(donnees.photo)}" target="_blank" download class="btn btn-sm btn-primary">
                            📎 Télécharger ${echapperHtml(nomFichier)}
                        </a>
                    </div>`;
                }
            }

            // Ajouter le message au chat
            $("#chat-messages").append(`
                <div class="messageTotal" id="message-${donnees.dernier_id}">
                    <div class="message received-message">
                        <div class="avatar bg-primary text-white rounded-circle p-2">
                            <!-- Affiche la première lettre de l'email -->
                            ${echapperHtml(donnees.email ? donnees.email.substring(0, 2) : '??')}
                        </div>
                        <div class="bubble">
                            <strong>${echapperHtml(donnees.email ?? 'Email inconnu')}</strong>
                            <span class="text-muted">{{ \Carbon\Carbon::now()->format('H:i') }}</span>
                            <br>
                            <div class="message-text">
                                ${contenuMessage}
                            </div>
                            ${contenuFichier}
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

        let donneesFormulaire = new FormData();
        donneesFormulaire.append("_token", "{{ csrf_token() }}");
        donneesFormulaire.append("message", $("input[name='content']").val());
        donneesFormulaire.append("from", utilisateurId);
        donneesFormulaire.append("to", amiId);

        let fichierInput = $("input[name='fichier']")[0]; // Assurez-vous que l'input file a name='fichier'
        if (fichierInput.files.length > 0) {
            donneesFormulaire.append("fichier", fichierInput.files[0]); // Ajoute l'image ou le fichier si présent
        }

        $.ajax({
            type: "POST",
            url: "/broadcast",
            headers: {
                "X-Socket-Id": pusher.connection.socket_id,
            },
            data: donneesFormulaire,
            processData: false, // Ne pas traiter les données
            contentType: false, // Ne pas définir de type de contenu
        }).done(function(res) {
            $("#preview-container").html("");
            $("input[name='fichier']").val(""); // Réinitialiser l'input file

            let texteAvatar = res.email.substring(0, 2);
            let contenuMessage = res.message ? `<p>${echapperHtml(res.message)}</p>` : "";

            // Déterminer si c'est une image ou un fichier à télécharger
            let extensionFichier = res.fichier ? res.fichier.split('.').pop().toLowerCase() : "";
            let estImage = ["jpg", "jpeg", "png", "gif"].includes(extensionFichier);
            let contenuFichier = "";

            if (res.fichier) {
                if (estImage) {
                    contenuFichier = `<img src="${res.fichier}" class="message-image" alt="Image envoyée">`;
                } else {
                    contenuFichier = `<a href="${res.fichier}" target="_blank" class="text-blue-500">
                        📄 Télécharger ${res.fichier.split('/').pop()}
                    </a>`;
                }
            }

            $("#chat-messages").append(`
                <div class="messageTotal" id="message-${res.dernier_id}">
                    <div class="message own-message">
                        <button class="delete-btn" data-id="${res.dernier_id}">🗑️</button>
                        <div class="bubble">
                            <strong>${res.email}</strong>
                            <span class="text-muted">{{ \Carbon\Carbon::now()->format('H:i') }}</span>
                            <br>
                            <div class="message-text">
                                ${contenuMessage}
                            </div>
                            ${contenuFichier}
                        </div>
                        <div class="ml-4 avatar bg-primary text-white rounded-circle p-2">${texteAvatar}</div>
                    </div>
                    <div class="separator"></div>
                </div>
            `);

            $("input[name='content']").val("");
            $("#chat-messages").scrollTop($("#chat-messages")[0].scrollHeight);
        }).fail(function(xhr, status, erreur) {
            console.error("Erreur d'envoi :", erreur);
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
    canal.bind('message-supprime-ami', function(donnees) {
        console.log("Message supprimé :", donnees); // Affiche l'ID du message supprimé pour le débogage
        // Supprime le message correspondant du DOM
        $(`#message-${donnees.idMessage}`).remove();
    });
</script>
@endsection

