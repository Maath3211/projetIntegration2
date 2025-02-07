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
            <h4>Nom Groupe</h4>
            <div>1/25</div>
        </div>

        <div class="row mt-3">
            <div class="col-md-3 chat-sidebar">
                <p>Liste des amis / groupes d'amis</p>
                @include('conversations.utilisateurs',['users'=>$users])
                
            </div>
            <div class="col-md-9">
                <div class="messages">
                    @include('conversations.receive',['messages'=>"Hey !"])
                </div>
                
                <div class="d-flex align-items-center mt-3">
                    <button class="btn btn-secondary me-2">➕</button>
                    <button class="btn btn-secondary me-2">😊</button>
                    <form action="/broadcast" method="POST">
                        <input id="message" name="message" type="text" class="message-input" placeholder="Écris un message...">
                        <button type="submit" class="btn btn-secondary ms-2">Submit</button>
                    </form>

                </div>
            </div>
        </div>
    </div>

</body>
<script>
    console.log("Pusher key:", '{{ config('broadcasting.connections.pusher.key') }}');
    
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

    const channel = pusher.subscribe('mon-channel');

    // Receive
    channel.bind('mon-event', function(data) {
        console.log("Message received on index page:", data.message); // Debug
        $(".messages").append('<div class="message"><div class="bubble">' + data.message + '</div></div>');
        $(document).scrollTop($(document).height());
    });

    $("form").on('submit', function(e) {
        e.preventDefault();
        console.log("Form submitted!"); // Debug
        $.ajax({
            type: "POST",
            url: "/broadcast",
            headers: {
                'X-Socket-Id': pusher.connection.socket_id,
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            data: {
                message: $("#message").val()
            },
            success: function(res) {
                console.log("Message sent:", $("#message").val()); // Debug
                $(".messages").append('<div class="message own-message"><div class="bubble">' + $("#message").val() + '</div></div>');
                $("#message").val("");
                $(document).scrollTop($(document).height());
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error sending message:", textStatus, errorThrown); // Debug
            }
        });
    });

</script> 
</html>
