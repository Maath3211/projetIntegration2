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
        const friendId = "{{ request()->id }}"; // ID de l'ami avec qui il discute :: Plutot le clan avec qui il discute
        const canal = "{{ request()->canal }}";



        const channelName = "chat-" + friendId + "-" + canal;

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
                <strong>{{ isset($message) ? $message->user->email : 'rien' }}</strong>
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
            formData.append("canal", canal);


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