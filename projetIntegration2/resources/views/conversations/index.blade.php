<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                <div class="chat-messages">
                    <div class="message">
                        <div class="avatar bg-primary text-white rounded-circle p-2">SS</div>
                        <div class="bubble">
                            <strong>Sam Sulek</strong> <span class="text-muted">14:34</span><br>
                            🔊 Audio
                        </div>
                    </div>
                    <div class="message">
                        <div class="avatar bg-success text-white rounded-circle p-2">X</div>
                        <div class="bubble">
                            <strong></strong> <span class="text-muted">14:35</span><br>
                            <strong></strong>
                        </div>
                    </div>
                    <div class="message own-message">
                        <div class="bubble">
                            <strong>Yoan</strong> <span class="text-muted">14:36</span><br>
                            Nice body
                        </div>
                        <div class="avatar bg-danger text-white rounded-circle p-2">Y</div>
                    </div>
                </div>

                <div class="d-flex align-items-center mt-3">
                    <button class="btn btn-secondary me-2">➕</button>
                    <button class="btn btn-secondary me-2">😊</button>
                    <input type="text" class="message-input" placeholder="Écris un message...">
                    <button class="btn btn-secondary ms-2">🎤</button>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
