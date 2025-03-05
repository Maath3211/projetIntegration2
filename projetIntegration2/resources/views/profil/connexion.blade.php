<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>GymCord</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" style="text/css" href="\css\GabaritCss.css">

</head>

<body>
    <link rel="stylesheet" href="{{ asset('css/Profil/connexion.css') }}">

    <img src="{{ asset('img/backgroundConnexion.png') }}" alt="Background" id="imgBackground">

    <div class="container-fluid">
        <div class="d-flex row justify-content-center">
            <div class="col-md-10">

                <form action="{{ route('profil.connexion') }}" method="post" id="formConnexion">
                    <h1 class="h1" id="titreConnexion">{{ __('auth.connexion_title') }}</h1>
                    <div class="conteneurForm">
                        @csrf
                        <label for="email" class="text-vert">{{ __('auth.adresse_courriel') }}</label>
                        <input type="email" class="form-control inputConnexion" id="email"
                            placeholder="{{ __('auth.adresse_courriel') }}" name="email" value="{{ old('email') }}">
                        <div class="conteneurErreur">
                            @error('email')
                            @if($message !== __('auth.compte_non_verifie'))
                            <span class="text-danger">{{ $message }}&ensp;</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#dc3545"
                                class="bi bi-x-octagon-fill" viewBox="0 0 16 16">
                                <path
                                    d="M11.46.146A.5.5 0 0 0 11.107 0H4.893a.5.5 0 0 0-.353.146L.146 4.54A.5.5 0 0 0 0 4.893v6.214a.5.5 0 0 0 .146.353l4.394 4.394a.5.5 0 0 0 .353.146h6.214a.5.5 0 0 0 .353-.146l4.394-4.394a.5.5 0 0 0 .146-.353V4.893a.5.5 0 0 0-.146-.353zm-6.106 4.5L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708" />
                            </svg>
                            @endif
                            @enderror
                        </div>
                    </div>
                    <div class="conteneurForm">
                        <label for="password" class="text-vert">{{ __('auth.mot_de_passe') }}</label>
                        <input type="password" class="form-control inputConnexion" id="password"
                            placeholder="{{ __('auth.mot_de_passe') }}" name="password">
                        <div class="conteneurErreur">
                            @error('password')
                            <span class="text-danger">{{ $message }}&ensp;</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#dc3545"
                                class="bi bi-x-octagon-fill" viewBox="0 0 16 16">
                                <path
                                    d="M11.46.146A.5.5 0 0 0 11.107 0H4.893a.5.5 0 0 0-.353.146L.146 4.54A.5.5 0 0 0 0 4.893v6.214a.5.5 0 0 0 .146.353l4.394 4.394a.5.5 0 0 0 .353.146h6.214a.5.5 0 0 0 .353-.146l4.394-4.394a.5.5 0 0 0 .146-.353V4.893a.5.5 0 0 0-.146-.353zm-6.106 4.5L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708" />
                            </svg>
                            @enderror
                        </div>
                    </div>
                    <br>
                    @if (session('message'))
                    <div class="alert" id="divMessage">
                        <p class="text-center" id="pMessage">
                            {{ session('message') }}
                        </p>
                    </div>
                    @endif
                    @if (session('errors'))
                    @foreach ($errors->all() as $error)
                    @if ($error !== __('validation.required', ['attribute' => __('auth.adresse_courriel')]) && $error !== __('validation.required', ['attribute' => __('auth.mot_de_passe')]))
                    <div class="alert alert-erreur">
                        <p>{{ $error }}</p>
                    </div>
                    @endif
                    @endforeach
                    @endif
                    <a href="{{ route('profil.connexionGoogle') }}" class="google-btn">
                        <img src="https://developers.google.com/identity/images/g-logo.png" alt="Google logo">
                        {{ __('auth.continuer_avec_google') }}
                    </a>
                    <a href='{{ route('profil.reinitialisation') }}' class="text-vert" id="btMotDePasseOublie">{{ __('auth.motdepasse_perdu') }}</a>
                    <a href="{{ route('profil.creerCompte') }}" class="text-vert" id="btCreerCompte">{{ __('auth.creation_account') }}</a>
                    <button type="submit" class="btn btn-connexion">{{ __('auth.connexion') }}</button>
                </form>

            </div>
        </div>
    </div>
</body>
<script defer src="https://use.fontawesome.com/releases/v5.1.0/js/all.js"
    integrity="sha384-3LK/3kTpDE/Pkp8gTNp2gR/2gOiwQ6QaO7Td0zV76UFJVhqLl4Vl3KL1We6q6wR9" crossorigin="anonymous">
</script>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://kit.fontawesome.com/55ec8bd5f8.js" crossorigin="anonymous"></script>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
    integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"
    integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"
    integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
</script>

</html>