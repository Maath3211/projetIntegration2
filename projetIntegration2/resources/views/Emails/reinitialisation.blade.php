<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('emails.reinitialisation_mdp.subject') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .button {
            display: inline-block;
            background-color: #007bff;
            color: #ffffff;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            font-size: 16px;
        }

        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>{{ __('emails.reinitialisation_mdp.title') }}</h2>
        <p>{{ __('emails.reinitialisation_mdp.message') }}</p>
        <a href="{{ route('profil.reinitialisation.token', $token) }}" class="button">{{ __('emails.reinitialisation_mdp.button') }}</a>
        <p class="footer">{{ __('emails.reinitialisation_mdp.footer') }}</p>
    </div>
</body>

</html>