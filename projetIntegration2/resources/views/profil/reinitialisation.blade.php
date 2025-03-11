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
    <link rel="stylesheet" style="text/css" href="\css\GabaritCss.css">

</head>

<body>
    <link rel="stylesheet" href="{{ asset('css/Profil/reinitialisation.css') }}">

    <img src="{{ asset('img/backgroundConnexion.png') }}" alt="Background" id="imgBackground">

    <div class="container-fluid">
        <div class="d-flex row justify-content-center">
            <div class="col-md-10">

                <form action="{{ route('profil.motDePasseOublieEmail') }}" method="post" id="formConnexion">
                    <h1 class="h1" id="titreConnexion">{{ __('profile.password_reset') }}</h1>
                    <div class="conteneurForm">
                        @csrf
                        <label for="email" class="text-vert">{{ __('profile.adresse_courriel') }}</label>
                        <input type="email" class="form-control inputReinitialisation" id="email"
                            placeholder="Adresse courriel" name="email">
                    </div>
                    <br>
                    @if (session('message'))
                        <div class="alert alert-succes">
                            <p class="text-center">{{ session('message') }}</p>
                        </div>
                    @endif
                    @if (session('errors'))
                        @foreach ($errors->all() as $error)
                            @if ($error !== __('validation.required', ['attribute' => __('profile.adresse_courriel')]) && $error !== __('validation.required', ['attribute' => __('profile.password')]))
                                <div class="alert alert-erreur">
                                    <p>{{ $error }}</p>
                                </div>
                            @endif
                        @endforeach
                    @endif
                    <button type="submit" class="btn btn-confirmation">{{ __('profile.reset') }}</button>
                    <a href="{{ route('profil.connexion') }}" class="btn btn-retour">{{ __('profile.back') }}</a>
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
