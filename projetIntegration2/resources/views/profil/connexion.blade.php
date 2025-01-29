@extends('Layouts.app')
@section('contenu')
    @if (session('message'))
        <div class="alert alert-success">
            <p class="text-center msgErreur">{{ session('message') }}</p>
        </div>
    @endif
    <div class="container-fluid bordure">
        <div class="d-flex row justify-content-center">
            <div class="col-md-6">

                <form action="{{ route('profil.connexion') }}" method="post">
                    @csrf
                    <label for="email" class="titreForm">Adresse courriel</label>
                    <input type="email" class="form-control" id="email" placeholder="Adresse courriel" name="email">

                    <label for="password" class="titreForm">Mot de passe</label>
                    <input type="password" class="form-control" id="password" placeholder="Mot de passe" name="password">

                    <button type="submit" class="btn btn-secondary">Connexion</button>
                </form>








            </div>
        </div>
    </div>
@endsection
