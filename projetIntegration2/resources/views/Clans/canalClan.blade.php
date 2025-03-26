@extends('layouts.app')
@section('titre', 'Clans')



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
                        @if (isset($clan))
                        <div class="conteneurImage" style="background-image: url('{{ asset($clan->image) }}');">
                            <div class="texteSurImage">{{ $clan->nom }}</div>
                            @if ($utilisateur->id == $clan->adminId)
                            <div><a href="{{ route('clan.parametres', ['id' => $clan->id]) }}"><i
                                        class="fa-solid fa-ellipsis"></i></a></div>
                            @endif
                        </div>
                        @endif
                        <div class="conteneurCanaux">
                            @if (isset($categories))
                            @foreach ($categories as $categorie)
                            <div class="categorieCanal">
                                <div class="titreCategorieCanal categorie_{{ $categorie->id }}">
                                    <div>
                                        <i class="fa-solid fa-minus"></i>
                                        {{ $categorie->categorie }}
                                    </div>
                                    @if ($utilisateur->id == $clan->adminId)
                                        <i class="fa-solid fa-plus fa-xs"></i>
                                    @endif
                                </div>
                                @if (isset($canauxParCategorie[$categorie->id]))
                                @foreach ($canauxParCategorie[$categorie->id] as $canal)
                                <div class="canal">
                                    <a href="/clan/{{ $id }}/canal/{{ $canal->id }}"
                                        class="canal_{{ $canal->id }}">
                                        <div>
                                            <i class="fa-solid fa-hashtag"></i>
                                            {{ $canal->titre }}
                                        </div>
                                    </a>
                                    <div class="iconesModificationCanal">
                                        @if ($utilisateur->id == $clan->adminId)
                                        <i class="fa-solid fa-pen modifier"></i>
                                        <i class="fa-solid fa-x supprimer"></i>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                                @else
                                <p>{{__('clans.clan_sans_canal')}}</p>
                                @endif
                            </div>
                            @endforeach
                            @else
                            <p>{{__('clans.sans_categorie')}}</p>
                            @endif
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
                            {{__('clans.voir_message_precedent')}}
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
                                    {{__('clans.telecharger')}} {{ $message->fichier }}
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
                            {{__('clans.voir_message_suivant')}}
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
                                    <label for="fichierInput"
                                            class="btn btn-secondary me-2 file-upload-btn text-white">📁</label>
                                </div>
                                <div id="emoji-picker-container" class="emoji-picker-container"></div>
                                <button type="button" id="emoji-btn"
                                    name="emoji" class="btn btn-secondary me-2">😊</button>
                                <input id="message" type="textarea" class="message-input form-control flex-grow-1"
                                    name="content" placeholder="{{__('clans.entrer_message')}}">
                                <button class="btn btn-primary ms-2" id="BoutonSoumettre" type="submit">{{__('clans.envoyer')}}</button>
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
                    @if (isset($membres))
                    @foreach ($membres as $membre)
                    <div class="membre">
                        <a href="{{ route('profil.profilPublic', ['email' => $membre->email]) }}">
                            <img src="{{ asset($membre->imageProfil) }}">
                            <div>
                                @if ($membre->id == $clan->adminId)
                                                ADMIN -
                                            @endif{{ $membre->prenom }} {{ $membre->nom }}
                            </div>
                        </a>
                    </div>
                    @endforeach
                    @else
                    <p>{{ __('clans.invitation_vue')}}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <form id="formulaireClan" action="{{ route('canal.actions', ['id' => $id]) }}" method="POST">
        @csrf
        <input type="hidden" class="action" name="action">
        <input type="hidden" class="requete" name="requete">
    </form>

    <!-- Fenêtre contextuelle pour ajouter un canal -->
    <div id="ajoutCanal" class="fenetreCategorie">
        <div class="conteneurConfirmation">
            <div class="titreConfirmation">
                <div>{{ __('clans.ajout_canal')}}</div>
            </div>
            <div class="texteConfirmation">
                <input type="text" name="entreeNomCanal" class="form-control entreeNomCanal"
                    placeholder="ex.: Cardio">
                <span class="messageErreur"></span>
            </div>

            <div class="boutonsConfirmation">
                <button class="annuler" type="button">{{ __('clans.annuler')}}</button>
                <button id="confirmerAjout" type="button">{{ __('clans.confirmer')}}</button>
            </div>
        </div>
    </div>

    <!-- Fenêtre contextuelle pour renommer un canal -->
    <div id="renommerCanal" class="fenetreCategorie">
        <div class="conteneurConfirmation">
            <div class="titreConfirmation">
                <div>{{ __('clans.renommer_canal')}}</div>
            </div>
            <div class="texteConfirmation">
                <input type="text" name="entreeNomCanal" class="form-control entreeNomCanal"
                    placeholder="ex.: Cardio">
                <span class="messageErreur"></span>
            </div>

            <div class="boutonsConfirmation">
                <button class="annuler" type="button">{{ __('clans.annuler')}}</button>
                <button id="confirmerRenommage" type="button">{{ __('clans.confirmer')}}</button>
            </div>
        </div>
    </div>

    <!-- Fenêtre contextuelle pour supprimer un canal -->
    <div id="confirmationSuppression" class="fenetreCategorie">
        <div class="conteneurConfirmation">
            <div class="titreConfirmation">
                <div>{{ __('clans.supprimer_canal')}}</div>
            </div>
            <div class="texteConfirmation">
                <div>{{ __('clans.supprimer_canal_avertissement')}}</div>
            </div>

            <div class="boutonsConfirmation">
                <button class="annuler" type="button">{{ __('clans.annuler')}}</button>
                <button id="confirmerSuppression" type="button">{{ __('clans.confirmer')}}</button>
            </div>
        </div>
    </div>

    <div id="conteneurMessages">
        @if (session('message'))
        <div class="alert" id="messageSucces">
            <span>{{ session('message') }}</span>
            <button class="close-btn">X</button>
        </div>
        @endif

        <!--Obligé d'utiliser $errors ici c'est la facon que laravel gère ses erreurs-->
        @if ($errors->any() || session('erreur'))
        <div class="alert" id="messageErreur">
            <ul>
                @if ($errors->any())
                @foreach ($errors->all() as $erreur)
                <li>{{ $erreur }}</li>
                @endforeach
                @endif
                @if (session('erreur'))
                <li>{{ session('erreur') }}</li>
                @endif
            </ul>
            <button class="close-btn">X</button>
        </div>
        @endif
    </div>

</div>

@endsection()




























@section('scripts')

<script src="{{ asset('js/Clans/clans.js') }}" crossorigin="anonymous"></script>
<script src="{{ asset('js/Conversations/chat.js') }}"></script>


    <!-- Script directement dans la page car on doit référencer une librairie Laravel depuis la page (config) -->

    <script>

    //*
    //**
    //** Avertissement : Ce code utilise Pusher pour la diffusion en temps réel.
    //** Pusher pour la raison qui m'echape ne fonctionne pas sur un js a pars
    //** donc je suis obligé de le mettre ici.
    //** Je ne sais pas si c'est la bonne pratique, mais je n'ai pas trouvé d'autre solution.
    //**
    //** Et malheureusement il y a beaucoup de code en englais
    //** Encore une fois
    //** Merci pusher
    //**
    //*


    // Fonction pour échapper les caractères spéciaux
    function escapeHtml(unsafe) {
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

        // ---------------------------
        // Gestion de l'envoi du formulaire
        // ---------------------------
        const utilisateurId = "{{ auth()->id() }}"; // ID de l'utilisateur connecté
        const clanId = "{{ request()->id }}"; // ID du clan
        const canalId = "{{ request()->canal }}";
        const nomCanal = "chat-" + clanId + "-" + canalId; // Assurez-vous que clanId (idGroupe) vient avant canalId (idCanal)
        console.log("Nom du canal:", nomCanal); // Ajout pour le débogage

        const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
        cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
        encrypted: true
    });
        //console.log('Pusher:', pusher); // Ajout pour le débogage

        pusher.connection.bind('connected', function() {
            console.log('Connecté avec succès à Pusher');
        });

        pusher.connection.bind('error', function(erreur) {
            console.error('Erreur de connexion Pusher:', erreur);
        });

        // Ajout d'un gestionnaire pour vérifier l'état de connexion
        pusher.connection.bind('state_change', function(etats) {
            //console.log('État de Pusher modifié:', etats); // Affiche les changements d'état
        });

        pusher.connection.bind('error', function(erreur) {});

    const canal = pusher.subscribe(nomCanal);



        // ---------------------------
        // Recevoir les messages de la conversation
        // ---------------------------
        canal.bind('event-group', function(data) {
            console.log("Données reçues de Pusher:", data); // Debugging

            // Vérifier si le message a été supprimé
            if (data.supprime === true) {
                // Si le message a été supprimé, le retirer du DOM
                $(`#message-${data.dernier_id}`).remove();
            } else {
                // Détermine le contenu du message (texte, image ou fichier)
                let messageContent = data.message ? `<p>${escapeHtml(data.message)}</p>` : "";
                let fileContent = "";

                if (data.photo) {
                    let fileExtension = data.photo.split('.').pop().toLowerCase();
                    let isImage = ["jpg", "jpeg", "png", "gif"].includes(fileExtension);

                    if (isImage) {
                        fileContent = `<div class="message-image">
                            <img src="/img/conversations_photo/${data.photo}" alt="Image envoyée" class="message-img">
                        </div>`;
                    } else {
                        const fileName = data.photo.split('/').pop();
                        fileContent = `<div class="message-file">
                            <a href="/fichier/conversations_fichier/${data.photo}" target="_blank" download class="btn btn-sm btn-primary">
                                📎 Télécharger ${fileName}
                            </a>
                        </div>`;
                    }
                }

                // Ajouter le message au chat
                $("#chat-messages").append(`
                    <div class="messageTotal" id="message-${data.dernier_id}">
                        <div class="message received-message">
                            <div class="avatar bg-primary text-white rounded-circle p-2">
                                ${data.email ? data.email.substring(0, 2) : '??'}
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





        // ---------------------------
        // Gestion envoi du formulaire
        // ---------------------------
        let envoiEnCours = false; // Variable pour vérifier si un envoi est en cours

        $("form").submit(function(e) {
            e.preventDefault();

            if (envoiEnCours) {
                //console.log("Un message est déjà en cours d'envoi.");
                return; // Empêche l'envoi multiple
            }

            envoiEnCours = true; // Bloque l'envoi jusqu'à la fin de la requête

            let donneesFormulaire = new FormData();
            donneesFormulaire.append("_token", "{{ csrf_token() }}");
            donneesFormulaire.append("message", $("input[name='content']").val());
            donneesFormulaire.append("de", utilisateurId);
            donneesFormulaire.append("vers", clanId);
            donneesFormulaire.append("canal", canalId);

            let fichierInput = $("input[name='fichier']")[0]; // Assurez-vous que l'input file a name='fichier'
            if (fichierInput.files.length > 0) {
                donneesFormulaire.append("fichier", fichierInput.files[0]); // Ajoute l'image ou le fichier si présent
            }

            $.ajax({
                type: "POST",
                url: "/broadcastClan",
                headers: {
                    "X-Socket-Id": pusher.connection.socket_id,
                },
                data: donneesFormulaire,
                processData: false, // Ne pas traiter les données
                contentType: false, // Ne pas définir de type de contenu
            }).done(function(reponse) {

                $("#preview-container").html("");
                $("input[name='fichier']").val(""); // Réinitialiser l'input file

                let texteAvatar = reponse.email_expediteur.substring(0, 2);
                let contenuMessage = reponse.message ? `<p>${escapeHtml(reponse.message)}</p>` : "";

                // Déterminer si c'est une image ou un fichier à télécharger
                let extensionFichier = reponse.fichier ? reponse.fichier.split('.').pop().toLowerCase() : "";
                let estImage = ["jpg", "jpeg", "png", "gif"].includes(extensionFichier);
                let contenuFichier = "";

                if (reponse.fichier) {
                    if (estImage) {
                        contenuFichier =
                            `<img src="${reponse.fichier}" class="message-image" alt="Image envoyée">`;
                    } else {
                        contenuFichier = `<a href="${reponse.fichier}" target="_blank" class="text-blue-500">
                📄 Télécharger ${reponse.fichier.split('/').pop()}
            </a>`;
                    }
                }

                $("#chat-messages").append(`
        <div class="messageTotal" id="message-${reponse.dernier_id}">
            <div class="message own-message">
                <button class="delete-btn" data-id="${reponse.dernier_id}">🗑️</button>
                <div class="bubble">
                    <strong>${reponse.email_expediteur}</strong>
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
                //console.error("Erreur d'envoi :", erreur);
            }).always(function() {
                envoiEnCours = false; // Réinitialise la variable après la requête
            });
        });





        // ---------------------------
        // Gestion de la suppression des messages
        // ---------------------------

        // Gestion de la suppression des messages
        $(document).on('click', '.delete-btn', function(e) {
            e.preventDefault();
            let messageId = $(this).data('id');
            //console.log("Suppression du message avec l'ID:", messageId); // Ajout pour le débogage

            $.ajax({
                type: "DELETE",
                url: `/messagesClan/${messageId}`,
                data: {
                    _token: "{{ csrf_token() }}"
                }
            }).done(function(res) {
                //console.log("Réponse de l'API:", res); // Ajout pour le débogage
                if (res.success) {
                    // Supprime le message du DOM
                    $(`#message-${messageId}`).remove();
                    //console.log("Message supprimé du DOM:", messageId);
                } else {
                    alert("Erreur lors de la suppression du message.");
                }
            }).fail(function(xhr, status, error) {
                //console.error("Erreur lors de la requête de suppression:", error); // Ajout pour le débogage
                alert("Erreur lors de la suppression du message.");
            });
        });

        // Écouter l'événement de suppression spécifique diffusé par Pusher
        canal.bind('message-supression', function(data) {
            //console.log("Message supprimé via Pusher:", data); // Affiche l'ID du message supprimé pour le débogage
            // Supprime le message correspondant du DOM
            $(`#message-${data.idMessage}`).remove();
            //console.log("Message supprimé du DOM via Pusher:", data.idMessage);
        });
    </script>


@endsection()