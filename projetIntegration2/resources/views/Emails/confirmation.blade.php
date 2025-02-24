<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Confirmation de compte</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #2d3748;">Bienvenue sur notre plateforme!</h2>
        
        <p>Bonjour {{ $utilisateur->prenom . ' ' . $utilisateur->nom }},</p>

        <p>Nous vous remercions de votre inscription. Pour activer votre compte, veuillez cliquer sur le bouton ci-dessous :</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('profil.confirmation', $utilisateur->token) }}" 
               style="background-color: #4CAF50; 
                      color: white; 
                      padding: 12px 25px; 
                      text-decoration: none; 
                      border-radius: 4px;">
                Confirmer mon compte
            </a>
        </div>

        <p>Si vous n'avez pas créé de compte, vous pouvez ignorer cet email.</p>

        <p style="margin-top: 30px;">
            Cordialement,<br>
            L'équipe {{ config('app.name') }}
        </p>
    </div>
</body>
</html>