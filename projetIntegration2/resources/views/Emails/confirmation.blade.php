<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ __('emails.confirmation.subject') }}</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #2d3748;">{{ __('emails.confirmation.welcome') }}</h2>
        
        <p>{{ __('emails.confirmation.greeting', ['name' => $utilisateur->prenom . ' ' . $utilisateur->nom]) }}</p>

        <p>{{ __('emails.confirmation.thanks') }}</p>
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('profil.confirmation', $utilisateur->codeVerification) }}" 
               style="background-color: #4CAF50; 
                      color: white; 
                      padding: 12px 25px; 
                      text-decoration: none; 
                      border-radius: 4px;">
                {{ __('emails.confirmation.confirm_button') }}
            </a>
        </div>

        <p>{{ __('emails.confirmation.ignore') }}</p>

        <p style="margin-top: 30px;">
            {{ __('emails.confirmation.regards') }}<br>
            {{ __('emails.confirmation.team', ['app_name' => config('app.name')]) }}
        </p>
    </div>
</body>
</html>