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
            @if (Auth::user()->id == $utilisateur->id)
                <form action="{{ route('profil.modification') }}" method="get">
                    @csrf
                    <button><span id="engrenage">&#9881;</span></button>
                </form>
            @endif

            <div class="d-flex justify-content-between mt-3">
                <form action="{{ route('statistique.index') }}" method="get">
                    @csrf
                    <button class="btn bouton">Statistique</button>
                </form>

            </div>
            <div class="mt-3">
                <p>Ami(s) en commun: <span class="badge bg-purple">P</span></p>
            </div>
            <div class="row">
                <h4 class="mb-3"><strong>Groupes</strong></h4>
                @if (Auth::user()->id !== $utilisateur->id)
                @foreach ($clansAway as $clan)
                    <div class="col-md-2 text-center mb-5">
                        <img src="{{ asset($clan->image) }}" alt="Clan Picture"
                            class="imgGroupe img-fluid mx-auto d-block">
                        <h3 class="greenText">{{ $clan->name }}</h3>
                    </div>
                @endforeach
                @else
                @foreach ($clans as $clan)
                    <div class="col-md-2 text-center mb-5">
                        <img src="{{ asset($clan->image) }}" alt="Clan Picture"
                            class="imgGroupe img-fluid mx-auto d-block">
                        <h3 class="greenText">{{ $clan->name }}</h3>
                    </div>
                @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection
