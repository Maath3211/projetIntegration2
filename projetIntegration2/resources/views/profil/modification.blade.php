@extends('Layouts.app')

@section('contenu')
    <link rel="stylesheet" href="{{ asset('css/Profil/profil.css') }}">
    @if (session('message'))
        <div class="alert alert-success">
            <p class="text-center msgErreur">{{ session('message') }}</p>
        </div>
    @endif
    <div class="container-fluid">
        <div class="d-flex row justify-content-center">
            <div class="col-md-8 profile-container-mod">
                <h1 class="mt-3 fs-1"><strong>Modifier le profil</strong></h1>

                <div class="row justify-content-center">

                    <div class="row mb-4">
                        <div class="col-md-4 offset-md-2 d-flex align-items-center">
                            <p class="greenText h5">Photo de profile</p>
                        </div>
                        <div class="col-md-4 d-flex flex-column align-items-center">
                            <img src="{{ asset('img/Utilisateurs/shrek.jpg') }}" alt="Profile Picture"
                                class="profile-pic me-3 profile-pic-mod">
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4 offset-md-2 d-flex align-items-center">
                            <p class="greenText h5 ">Nom complet</p>
                        </div>
                        <div class="col-md-4 d-flex flex-column align-items-center">
                            <input type="text" class="inputModification form-control"
                                value="Shany-Jonathan Carle" placeholder="Nom complet">
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-4 offset-md-2 d-flex align-items-center">
                            <p class="greenText h5">Adresse courriel</p>
                        </div>
                        <div class="col-md-4 d-flex flex-column align-items-center">
                            <input type="email" class="inputModification form-control"
                                value="shany.carle@cegeptr.qc.ca" placeholder="Adresse courriel">
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-4 offset-md-2 d-flex align-items-center">
                            <p class="greenText h5">Date de naissance</p>
                        </div>
                        <div class="col-md-4 d-flex flex-column align-items-center">
                            <input type="date" class="inputModification  form-control" value="1946-06-14" placeholder="Date de naissance">
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-4 offset-md-2 d-flex align-items-center">
                            <p class="greenText h5">Pays</p>
                        </div>
                        <div class="col-md-4 d-flex flex-column align-items-center">
                            <select class="form-select inputModification form-control"
                                aria-label="Default select example">
                                <option selected>Choisir</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country['code'] }}">{{ $country['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-4 offset-md-2 d-flex align-items-center">
                            <button class="btn btn-save btn-green">Sauvegarder</button>
                        </div>
                    </div>

                </div>




            </div>
        </div>
    </div>
@endsection
