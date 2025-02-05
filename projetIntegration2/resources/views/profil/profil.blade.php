@extends('Layouts.app')

@section('contenu')
    <link rel="stylesheet" href="{{ asset('css/Profil/profil.css') }}">

    <div class="container mt-5">
        <div class="profile-container text-center">
            <div class="d-flex align-items-center">
                <img src="{{ asset('img/Utilisateurs/shrek.jpg') }}" alt="Profile Picture" class="profile-pic me-3">
                <div class="text-start">
                    <h2 class="greenText">Shrek Evergreen</h2>
                    <p><strong>A propos:</strong></p>
                    <p><strong>Membre depuis:</strong> 25 mai 2022</p>
                </div>
            </div>

            <form action="{{ route('profil.deconnexion') }}" method="post">
                @csrf
                <button type="submit" class="btn btn-green">Déconnexion</button>
            </form>


            <span id="engrenage">&#9881;</span> 
            <div class="d-flex justify-content-between mt-3">
                <button class="btn btn-green">Statistique</button>
                <button class="btn btn-green">Ajouter en ami</button>
            </div>
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
