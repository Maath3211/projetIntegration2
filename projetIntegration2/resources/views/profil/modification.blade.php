@extends('Layouts.app')

@section('contenu')
    <link rel="stylesheet" href="{{ asset('css/Profil/profil.css') }}">
    @if (session('message'))
        <div class="alert alert-success">
            <p class="text-center msgErreur">{{ session('message') }}</p>
        </div>
    @endif
    <div class="container-fluid">
        <div class="d-flex row justify-content-center ">
            <div class="col-md-8 profile-container">
                <h1 class="mt-3 fs-1"><strong>Modifier le profil</strong></h1>
                <div class="mt-4">
                    <p class="text-start greenText">Photo de profile</p>
                    <img src="{{ asset('img/Utilisateurs/shrek.jpg') }}" alt="Profile Picture"
                        class="profile-pic me-3 profile-pic-mod">
                </div>
                
                <h2 class="greenText">Nom complet</h2>
                <input type="text" class="profile-field" value="Shany-Jonathan Carle">
                <h2 class="greenText">Adresse courriel</h2>
                <input type="email" class="profile-field" value="shany.carle@cegeptr.qc.ca">
                <h2 class="greenText">Date de naissance</h2>
                <input type="date" class="profile-field" value="1946-06-14">
                <h2 class="greenText">Pays</h2>
                <input type="text" class="profile-field" value="Canada">
                <button class="btn-save">Sauvegarder</button>


            </div>
        </div>
    </div>
@endsection
