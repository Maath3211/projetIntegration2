@extends('layouts.app')
@section('titre', 'Créer un graphique')

@section('style')
<link rel="stylesheet" style="text/css" href="{{asset('css/graphs/graphs.css')}}">
@endsection()

@section('contenu')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h1 class="h3 mb-0 text-white">Créer un graphique personnalisé</h1>
                </div>
                <div class="card-body">
                    @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                    @endif

                    <form action="{{ route('graphs.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-4">
                            <label for="titre" class="form-label text-white fw-bold">Titre du graphique:</label>
                            <input type="text" class="form-control @error('titre') is-invalid @enderror" id="titre" name="titre" required value="{{ old('titre') }}">
                            @error('titre')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label text-white fw-bold">Type de graphique:</label>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="type" id="typeGlobal" value="global" {{ old('type', 'global') == 'global' ? 'checked' : '' }}>
                                <label class="form-check-label" for="typeGlobal">
                                    <span class="fw-medium">Scores globaux</span>
                                    <small class="text-muted d-block">Affiche les scores de tous les utilisateurs</small>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type" id="typeClan" value="clan" {{ old('type') == 'clan' ? 'checked' : '' }}>
                                <label class="form-check-label" for="typeClan">
                                    <span class="fw-medium">Scores de clan</span>
                                    <small class="text-muted d-block">Affiche les scores d'un clan spécifique</small>
                                </label>
                            </div>
                            @error('type')
                            <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-4" id="clanSelectDiv" style="display: none;">
                            <label for="clan_id" class="form-label text-white fw-bold">Sélectionnez un clan:</label>
                            <select class="form-control @error('clan_id') is-invalid @enderror" id="clan_id" name="clan_id">
                                @forelse($clans as $clan)
                                <option value="{{ $clan->id }}" {{ old('clan_id') == $clan->id ? 'selected' : '' }}>{{ $clan->nom }}</option>
                                @empty
                                <option disabled>Aucun clan disponible</option>
                                @endforelse
                            </select>
                            @if($clans->isEmpty())
                            <div class="alert alert-warning mt-2">
                                <i class="fa-solid fa-circle-exclamation me-2"></i>
                                Vous n'êtes pas membre d'un clan
                            </div>
                            @endif
                            @error('clan_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_debut" class="form-label text-white fw-bold">Date de début:</label>
                                    <input type="date" class="form-control @error('date_debut') is-invalid @enderror" id="date_debut" name="date_debut" required value="{{ old('date_debut', now()->subMonths(3)->format('Y-m-d')) }}">
                                    @error('date_debut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_fin" class="form-label text-white fw-bold">Date de fin:</label>
                                    <input type="date" class="form-control @error('date_fin') is-invalid @enderror" id="date_fin" name="date_fin" required value="{{ old('date_fin', now()->format('Y-m-d')) }}">
                                    @error('date_fin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group d-flex justify-content-between">
                            <a href="{{ route('graphs.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Retour
                            </a>
                            <button type="submit" class="btn bouton" {{ $clans->isEmpty() && old('type') == 'clan' ? 'disabled' : '' }}>
                                <i class="fas fa-chart-line me-2"></i>Générer le graphique
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeGlobal = document.getElementById('typeGlobal');
        const typeClan = document.getElementById('typeClan');
        const clanSelectDiv = document.getElementById('clanSelectDiv');

        function toggleClanSelect() {
            if (typeClan.checked) {
                clanSelectDiv.style.display = 'block';
                clanSelectDiv.classList.add('animate__animated', 'animate__fadeIn');
            } else {
                clanSelectDiv.style.display = 'none';
            }
        }

        toggleClanSelect(); // Initial state

        typeGlobal.addEventListener('change', toggleClanSelect);
        typeClan.addEventListener('change', toggleClanSelect);
    });
</script>
@endsection
@endsection