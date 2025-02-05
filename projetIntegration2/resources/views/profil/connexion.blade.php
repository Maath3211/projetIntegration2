@extends('Layouts.app')

@section('contenu')
    <link rel="stylesheet" href="{{ asset('css/Profil/connexion.css') }}">

    <div class="container-fluid">
        <div class="d-flex row justify-content-center">
            <div class="col-md-6">

                <form action="{{ route('profil.connexion') }}" method="post">
                    <div>
                        @csrf
                        <label for="email" class="titreForm">Adresse courriel</label>
                        <input type="email" class="form-control" id="email" placeholder="Adresse courriel"
                            name="email">
                        @error('email')
                            <span class="text-danger">{{ $message }}
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                    class="bi bi-x-octagon-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M11.46.146A.5.5 0 0 0 11.107 0H4.893a.5.5 0 0 0-.353.146L.146 4.54A.5.5 0 0 0 0 4.893v6.214a.5.5 0 0 0 .146.353l4.394 4.394a.5.5 0 0 0 .353.146h6.214a.5.5 0 0 0 .353-.146l4.394-4.394a.5.5 0 0 0 .146-.353V4.893a.5.5 0 0 0-.146-.353zm-6.106 4.5L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708" />
                                </svg>
                            </span>
                        @enderror
                    </div>
                    <div>
                        <label for="password" class="titreForm">Mot de passe</label>
                        <input type="password" class="form-control" id="password" placeholder="Mot de passe"
                            name="password">
                        @error('password')
                            <span class="text-danger">{{ $message }}
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                    class="bi bi-x-octagon-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M11.46.146A.5.5 0 0 0 11.107 0H4.893a.5.5 0 0 0-.353.146L.146 4.54A.5.5 0 0 0 0 4.893v6.214a.5.5 0 0 0 .146.353l4.394 4.394a.5.5 0 0 0 .353.146h6.214a.5.5 0 0 0 .353-.146l4.394-4.394a.5.5 0 0 0 .146-.353V4.893a.5.5 0 0 0-.146-.353zm-6.106 4.5L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708" />
                                </svg>
                            </span>
                        @enderror
                    </div>
                    <br>
                    @if (session('message'))
                        <div class="alert alert-success">
                            <p class="text-center msgErreur">{{ session('message') }}</p>
                        </div>
                    @endif
                    @if (session('errors'))
                        @foreach ($errors->all() as $error)
                            @if ($error == 'Informations invalides')
                                <div class="alert alert-danger">
                                    <p>{{ $error }}</p>
                                </div>
                            @endif
                        @endforeach
                    @endif
                    <button type="submit" class="btn btn-gymCord">Connexion</button>
                </form>








            </div>
        </div>
    </div>
@endsection
