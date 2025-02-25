@extends('Layouts.app')

@section('contenu')
    <link rel="stylesheet" href="{{ asset('css/Profil/profil.css') }}">

    <div class="container mt-5">
        <div class="profile-container text-center">
            <div class="d-flex align-items-center">
                <img src="{{ asset($utilisateur->imageProfil) }}" alt="Profile Picture" class="profile-pic me-3">
                <div class="text-start">
                    <h2 class="greenText">{{ $utilisateur->prenom . ' ' . $utilisateur->nom }}</h2>
                    <p><strong>A propos: </strong>{{ $utilisateur->aPropos }}</p>
                    <p><strong>Membre depuis:</strong> {{ $utilisateur->created_at->format('d/m/Y') }}</p>
                </div>
            </div>

            <button class="btn bouton-ami">Ajouter en ami</button>
            
            <div class="mt-3">
                <p>Ami(s) en commun: <span class="badge bg-purple">P</span></p>
            </div>
            <div class=" row">
                <h4 class="mb-3"><strong>Groupe</strong> <small>(1 en commun)</small></h4>

                <div class="col-md-2 text-center">
                    <img src="{{ asset('img/workoutMasterLogo.jpg') }}" alt="Gym Picture"
                        class="imgGroupe img-fluid mx-auto d-block">
                    <h3 class="greenText">Gym supérieur</h3>
                </div>
                <div class="col-md-2 text-center">
                    <img src="{{ asset('img/workoutMasterLogo.jpg') }}" alt="Gym Picture"
                        class="imgGroupe img-fluid mx-auto d-block">
                    <h3>Gym supérieur</h3>
                </div>
                <div class="col-md-2 text-center">
                    <img src="{{ asset('img/workoutMasterLogo.jpg') }}" alt="Gym Picture"
                        class="imgGroupe img-fluid mx-auto d-block">
                    <h3>Gym supérieur</h3>
                </div>
                <div class="col-md-2 text-center">
                    <img src="{{ asset('img/workoutMasterLogo.jpg') }}" alt="Gym Picture"
                        class="imgGroupe img-fluid mx-auto d-block">
                    <h3>Gym supérieur</h3>
                </div>
                <div class="col-md-2 text-center">
                    <img src="{{ asset('img/workoutMasterLogo.jpg') }}" alt="Gym Picture"
                        class="imgGroupe img-fluid mx-auto d-block">
                    <h3>Gym supérieur</h3>
                </div>
                <div class="col-md-2 text-center">
                    <img src="{{ asset('img/workoutMasterLogo.jpg') }}" alt="Gym Picture"
                        class="imgGroupe img-fluid mx-auto d-block">
                    <h3>Gym supérieur</h3>
                </div>
            </div>
        </div>
    </div>
@endsection
