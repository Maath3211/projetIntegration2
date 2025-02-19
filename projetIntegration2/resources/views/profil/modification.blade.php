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
                <form action="{{ route('profil.updateModification') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row justify-content-center">

                        <div class="row mb-4">
                            <div class="col-md-4 offset-md-2 d-flex align-items-center">
                                <p class="greenText h5">Photo de profile</p>
                            </div>
                            <div class="col-md-4 d-flex flex-column align-items-center">
                                <img src="{{ asset(Auth::user()->imageProfil) }}" alt="Profile Picture"
                                    class="profile-pic me-3 profile-pic-mod">
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4 offset-md-2 d-flex align-items-center">
                                <p class="greenText h5 ">Image</p>
                            </div>
                            <div class="col-md-4 d-flex flex-column align-items-center">
                                <input type="file" class="form-control" name="imageProfil" accept="image/*">
                            </div>
                            <div class="conteneurErreur col-md-4 offset-md-6">
                                @error('imageProfil')
                                    <span class="text-danger">{{ $message }}&ensp;</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#dc3545"
                                        class="bi bi-x-octagon-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M11.46.146A.5.5 0 0 0 11.107 0H4.893a.5.5 0 0 0-.353.146L.146 4.54A.5.5 0 0 0 0 4.893v6.214a.5.5 0 0 0 .146.353l4.394 4.394a.5.5 0 0 0 .353.146h6.214a.5.5 0 0 0 .353-.146l4.394-4.394a.5.5 0 0 0 .146-.353V4.893a.5.5 0 0 0-.146-.353zm-6.106 4.5L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708" />
                                    </svg>
                                @enderror
                            </div>
                        </div>


                        <div class="row mb-4">
                            <div class="col-md-4 offset-md-2 d-flex align-items-center">
                                <p class="greenText h5 ">Prénom</p>
                            </div>
                            <div class="col-md-4 d-flex flex-column align-items-center">
                                <input type="text" class="inputModification form-control"
                                    value="{{ Auth::user()->prenom }}" placeholder="Prénom" name="prenom">
                            </div>
                            <div class="conteneurErreur col-md-4 offset-md-6">
                                @error('prenom')
                                    <span class="text-danger">{{ $message }}&ensp;</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#dc3545"
                                        class="bi bi-x-octagon-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M11.46.146A.5.5 0 0 0 11.107 0H4.893a.5.5 0 0 0-.353.146L.146 4.54A.5.5 0 0 0 0 4.893v6.214a.5.5 0 0 0 .146.353l4.394 4.394a.5.5 0 0 0 .353.146h6.214a.5.5 0 0 0 .353-.146l4.394-4.394a.5.5 0 0 0 .146-.353V4.893a.5.5 0 0 0-.146-.353zm-6.106 4.5L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708" />
                                    </svg>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4 offset-md-2 d-flex align-items-center">
                                <p class="greenText h5 ">Nom</p>
                            </div>
                            <div class="col-md-4 d-flex flex-column align-items-center">
                                <input type="text" class="inputModification form-control" value="{{ Auth::user()->nom }}"
                                    placeholder="Nom" name="nom">
                            </div>
                            <div class="conteneurErreur col-md-4 offset-md-6">
                                @error('nom')
                                    <span class="text-danger">{{ $message }}&ensp;</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#dc3545"
                                        class="bi bi-x-octagon-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M11.46.146A.5.5 0 0 0 11.107 0H4.893a.5.5 0 0 0-.353.146L.146 4.54A.5.5 0 0 0 0 4.893v6.214a.5.5 0 0 0 .146.353l4.394 4.394a.5.5 0 0 0 .353.146h6.214a.5.5 0 0 0 .353-.146l4.394-4.394a.5.5 0 0 0 .146-.353V4.893a.5.5 0 0 0-.146-.353zm-6.106 4.5L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708" />
                                    </svg>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4 offset-md-2 d-flex align-items-center">
                                <p class="greenText h5">Adresse courriel</p>
                            </div>
                            <div class="col-md-4 d-flex flex-column align-items-center">
                                <input type="email" class="inputModification form-control"
                                    value="{{ Auth::user()->email }}" placeholder="Adresse courriel" name="email">
                            </div>
                            <div class="conteneurErreur col-md-4 offset-md-6">
                                @error('email')
                                    <span class="text-danger">{{ $message }}&ensp;</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#dc3545"
                                        class="bi bi-x-octagon-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M11.46.146A.5.5 0 0 0 11.107 0H4.893a.5.5 0 0 0-.353.146L.146 4.54A.5.5 0 0 0 0 4.893v6.214a.5.5 0 0 0 .146.353l4.394 4.394a.5.5 0 0 0 .353.146h6.214a.5.5 0 0 0 .353-.146l4.394-4.394a.5.5 0 0 0 .146-.353V4.893a.5.5 0 0 0-.146-.353zm-6.106 4.5L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708" />
                                    </svg>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4 offset-md-2 d-flex align-items-center">
                                <p class="greenText h5">Date de naissance</p>
                            </div>
                            <div class="col-md-4 d-flex flex-column align-items-center">
                                <input type="date" class="inputModification form-control"
                                    value="{{ Auth::user()->dateNaissance }}" placeholder="Date de naissance"
                                    name="dateNaissance" max="{{ date('Y-m-d') }}" min="1900-01-01">
                            </div>
                            <div class="conteneurErreur col-md-4 offset-md-6">
                                @error('dateNaissance')
                                    <span class="text-danger">{{ $message }}&ensp;</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#dc3545"
                                        class="bi bi-x-octagon-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M11.46.146A.5.5 0 0 0 11.107 0H4.893a.5.5 0 0 0-.353.146L.146 4.54A.5.5 0 0 0 0 4.893v6.214a.5.5 0 0 0 .146.353l4.394 4.394a.5.5 0 0 0 .353.146h6.214a.5.5 0 0 0 .353-.146l4.394-4.394a.5.5 0 0 0 .146-.353V4.893a.5.5 0 0 0-.146-.353zm-6.106 4.5L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708" />
                                    </svg>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4 offset-md-2 d-flex align-items-center">
                                <p class="greenText h5">Pays</p>
                            </div>
                            <div class="col-md-4 d-flex flex-column align-items-center">
                                <select class="form-select inputModification form-control" name="pays">
                                    <option selected>Choisir</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country['name'] }}"
                                            {{ Auth::user()->pays == $country['name'] ? 'selected' : '' }}>
                                            {{ $country['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="conteneurErreur col-md-4 offset-md-6">
                                @error('pays')
                                    <span class="text-danger">{{ $message }}&ensp;</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#dc3545"
                                        class="bi bi-x-octagon-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M11.46.146A.5.5 0 0 0 11.107 0H4.893a.5.5 0 0 0-.353.146L.146 4.54A.5.5 0 0 0 0 4.893v6.214a.5.5 0 0 0 .146.353l4.394 4.394a.5.5 0 0 0 .353.146h6.214a.5.5 0 0 0 .353-.146l4.394-4.394a.5.5 0 0 0 .146-.353V4.893a.5.5 0 0 0-.146-.353zm-6.106 4.5L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708" />
                                    </svg>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4 offset-md-2 d-flex align-items-center">
                                <p class="greenText h5">Genre</p>
                            </div>
                            <div class="col-md-4 d-flex flex-column align-items-center">
                                <select class="form-select inputModification form-control" name="genre">
                                    <option>Choisir</option>
                                    <option value="Homme" {{ Auth::user()->genre == 'Homme' ? 'selected' : '' }}>Homme
                                    </option>
                                    <option value="Femme" {{ Auth::user()->genre == 'Femme' ? 'selected' : '' }}>Femme
                                    </option>
                                    <option value="Prefere ne pas dire"
                                        {{ Auth::user()->genre == 'Prefere ne pas dire' ? 'selected' : '' }}>Préfère ne pas
                                        dire
                                    </option>
                                </select>
                            </div>
                            <div class="conteneurErreur col-md-4 offset-md-6">
                                @error('genre')
                                    <span class="text-danger">{{ $message }}&ensp;</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#dc3545"
                                        class="bi bi-x-octagon-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M11.46.146A.5.5 0 0 0 11.107 0H4.893a.5.5 0 0 0-.353.146L.146 4.54A.5.5 0 0 0 0 4.893v6.214a.5.5 0 0 0 .146.353l4.394 4.394a.5.5 0 0 0 .353.146h6.214a.5.5 0 0 0 .353-.146l4.394-4.394a.5.5 0 0 0 .146-.353V4.893a.5.5 0 0 0-.146-.353zm-6.106 4.5L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708" />
                                    </svg>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-4 offset-md-2 d-flex align-items-center">
                                <button class="btn btn-save btn-green">Sauvegarder</button>
                            </div>
                        </div>
                </form>
            </div>


        </div>
    </div>
    </div>
@endsection
