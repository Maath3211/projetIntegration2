@extends('Layouts.app')

@section('contenu')
    <link rel="stylesheet" href="{{ asset('css/Profil/modification.css') }}">
    @if (session('message'))
        <div class="alert alert-success">
            <p class="text-center msgErreur">{{ session('message') }}</p>
        </div>
    @endif

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8 profile-container-mod">
                <h1 class="mt-3 fs-1"><strong>{{ __('profile.parametres_profil') }}</strong></h1>
                <form action="{{ route('profil.updateModification') }}" method="POST" enctype="multipart/form-data">
                    @method('PATCH')
                    @csrf
                    <div class="form-scrollable-wrapper">
                        <div class="d-flex row justify-content-center">

                            <div class="row mb-4">
                                <div class="col-md-4 offset-md-2 d-flex align-items-center">
                                    <p class="greenText h5">{{ __('profile.image_profil') }}</p>
                                </div>
                                <div class="col-md-4 d-flex flex-column align-items-center">
                                    <img src="{{ asset(Auth::user()->imageProfil) }}" alt="Profile Picture"
                                        class="profile-pic me-3 profile-pic-mod">
                                </div>
                            </div>

                            <div class="row mb-1">
                                <div class="col-md-4 offset-md-2 d-flex align-items-center">
                                    <p class="greenText h5 ">{{ __('profile.image') }}</p>
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


                            <div class="row mb-1">
                                <div class="col-md-4 offset-md-2 d-flex align-items-center">
                                    <p class="greenText h5 ">{{ __('profile.prenom') }}</p>
                                </div>
                                <div class="col-md-4 d-flex flex-column align-items-center">
                                    <input type="text" class="inputModification form-control"
                                        value="{{ Auth::user()->prenom }}" placeholder="{{ __('profile.prenom') }}"
                                        name="prenom">
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

                            <div class="row mb-1">
                                <div class="col-md-4 offset-md-2 d-flex align-items-center">
                                    <p class="greenText h5 ">{{ __('profile.nom') }}</p>
                                </div>
                                <div class="col-md-4 d-flex flex-column align-items-center">
                                    <input type="text" class="inputModification form-control"
                                        value="{{ Auth::user()->nom }}" placeholder="{{ __('profile.nom') }}"
                                        name="nom">
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
                            <div class="row mb-1">
                                <div class="col-md-4 offset-md-2 d-flex align-items-center">
                                    <p class="greenText h5 ">{{ __('profile.a_propos') }}</p>
                                </div>
                                <div class="col-md-4 d-flex flex-column align-items-center">
                                    <textarea class="inputModification form-control" placeholder="{{ __('profile.a_propos_de_vous') }}" name="aPropos"
                                        rows="3">{{ Auth::user()->aPropos }}</textarea>
                                </div>
                                <div class="conteneurErreur col-md-4 offset-md-6">
                                    @error('aPropos')
                                        <span class="text-danger">{{ $message }}&ensp;</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#dc3545"
                                            class="bi bi-x-octagon-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M11.46.146A.5.5 0 0 0 11.107 0H4.893a.5.5 0 0 0-.353.146L.146 4.54A.5.5 0 0 0 0 4.893v6.214a.5.5 0 0 0 .146.353l4.394 4.394a.5.5 0 0 0 .353.146h6.214a.5.5 0 0 0 .353-.146l4.394-4.394a.5.5 0 0 0 .146-.353V4.893a.5.5 0 0 0-.146-.353zm-6.106 4.5L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708" />
                                        </svg>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-1">
                                <div class="col-md-4 offset-md-2 d-flex align-items-center">
                                    <p class="greenText h5">{{ __('profile.courriel') }}</p>
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

                            <div class="row mb-1">
                                <div class="col-md-4 offset-md-2 d-flex align-items-center">
                                    <p class="greenText h5">{{ __('profile.date_naissance') }}</p>
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

                            <div class="row mb-1">
                                <div class="col-md-4 offset-md-2 d-flex align-items-center">
                                    <p class="greenText h5">{{ __('profile.pays') }}</p>
                                </div>
                                <div class="col-md-4 d-flex flex-column align-items-center">
                                    <select class="form-select inputModification form-control" name="pays">
                                        <option>{{ __('profile.choisir') }}</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country['name'] }}"
                                                {{ Auth::user()->pays == $country['name'] ? 'selected' : '' }}>
                                                {{ $country['name'] }}
                                            </option>
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
                                    <p class="greenText h5">{{ __('profile.genre') }}</p>
                                </div>
                                <div class="col-md-4 d-flex flex-column align-items-center">
                                    <select class="form-select inputModification form-control" name="genre">
                                        <option>{{ __('profile.choisir') }}</option>
                                        <option value="Homme" {{ Auth::user()->genre == 'Homme' ? 'selected' : '' }}>
                                            {{ __('auth.homme') }}
                                        </option>
                                        <option value="Femme" {{ Auth::user()->genre == 'Femme' ? 'selected' : '' }}>
                                            {{ __('auth.femme') }}
                                        </option>
                                        <option value="Prefere ne pas dire"
                                            {{ Auth::user()->genre == 'Prefere ne pas dire' ? 'selected' : '' }}>
                                            {{ __('auth.pas_indiquer') }}
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
                            <div class="row mb-4 form-buttons">


                                <div class="col-md-4 offset-md-2 d-flex align-items-center">


                                    <div class="col-md-4 offset-md-2 align-items-center" id="divButton">

                                        <button type="submit" class="btn btn-save btn-green">Sauvegarder</button>

                                        <p class="btn btn-retour" id="btRetour">Retour</p>

                                        <p class="btn btn-suppression" data-bs-toggle="modal"
                                            data-bs-target="#deleteConfirmationModal">Suppression</p>

                                    </div>

                                </div>

                </form>



                <div class="modal fade" id="deleteConfirmationModal" tabindex="-1"
                    aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">

                    <div class="modal-dialog">

                        <div class="modal-content">

                            <div class="modal-header">

                                <h5 class="modal-title h3" id="deleteConfirmationModalLabel">Confirmation de suppression

                                </h5>

                                <button type="button" class="btn-close fermerModal" data-bs-dismiss="modal"
                                    aria-label="Close"></button>

                            </div>

                            <div class="modal-body">

                                Êtes-vous sûr de vouloir supprimer votre compte ? <strong>Cette action est

                                    irréversible.</strong>

                            </div>

                            <div class="modal-footer">

                                <button type="button" class="btn btn-save" data-bs-dismiss="modal">Annuler</button>

                                <form action="{{ route('profil.suppressionProfil') }}" method="POST">
                                    @method('delete')
                                    @csrf

                                    <button type="submit" class="btn btn-suppression">Supprimer mon compte</button>

                                </form>

                            </div>

                        </div>

                    </div>

                </div>
            </div>


        </div>
    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/Profil/modification.js') }}"></script>
@endsection
