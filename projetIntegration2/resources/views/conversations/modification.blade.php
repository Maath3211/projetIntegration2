<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ __('messages.modify_conversations') }}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .message-content {
            max-height: 100px;
            overflow-y: auto;
        }

        .list-group-item {
            margin-bottom: 10px;
            background-color: #e9ecef;
            border: 1px solid #dee2e6;
        }

        .form-control {
            width: auto;
        }

        .btn-primary {
            margin-left: 10px;
        }

        h1 {
            color: #007bff;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">{{ __('messages.modify_conversations') }}</h1>
        <ul class="list-group">
            @foreach($messages as $message)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div class="col-3 message-content">
                    <span>{{ $message->message }}</span>
                </div>
                <div class="col-2 message-content">
                    <span>{{ $message->fichier }}</span>
                </div>
                <div class="col-3">
                    <span>{{ $message->created_at }}</span>
                </div>
                <form action="{{ route('messages.update', $message->id) }}" method="POST" class="d-flex align-items-center">
                    @csrf
                    @method('PUT')
                    <input type="text" name="new_message" class="form-control" placeholder="Nouveau message">
                    <button type="submit" class="btn btn-primary btn-sm">{{ __('messages.edit') }}</button>
                </form>
            </li>
            @endforeach
        </ul>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>