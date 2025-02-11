<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
        .message-input {
            background: pink;
            padding: 10px;
            border-radius: 20px;
            width: 100%;
            border: none;
            outline: none;
        }
    </style>
</head>
<body>

    <div class="container chat-container mt-4">
        <div class="chat-header d-flex justify-content-between">
            <h4>{{$user->email}}</h4>
            <div>1/25</div>
        </div>

        <div class="row mt-3">
            <div class="col-md-3 chat-sidebar">
                <p>Liste des amis / groupes d'amis</p>
                @include('conversations.utilisateurs',['users'=>$users])

            </div>
            <div class="col-md-9">
                <div class="chat-messages" id="chat-messages">
                    @if ($messages->hasMorePages())
                        <div class="div text-center">
                            <a href="{{$messages->nextPageUrl()}}" class="btn btn-light">
                                Voir les messages précédent
                            </a>
                        </div>
                    @endif
                    @foreach ($messages as $message)
                    <div class="message {{ $message->from->id !== $user->id ? 'offset-md-9 text-right' : '' }}">
                        <div class="avatar bg-primary text-white rounded-circle p-2">SS</div>
                        <div class="bubble">
                            <strong>{{$message->from->email}}</strong> 
                            <span class="text-muted">{{ substr($message->created_at, 11, 5) }}
                            </span>
                            <br>
                            <p>
                                {!! nl2br(e($message->message)) !!}
                                {{//dd($message)
                                ;}}
                            </p>
                        </div>
                    </div>
                    <hr>
                    @endforeach
                    @include('conversations.receive')

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
            </div>
            <script>
                // Scroll to the bottom of the chat messages
                document.addEventListener("DOMContentLoaded", function() {
                    var chatMessages = document.getElementById("chat-messages");
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                });

            </script>
            </div>
        </div>
    </div>

</body>

<script>
    
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
            from: userId, // Ajoute l'ID de l'utilisateur connecté
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
</html>
