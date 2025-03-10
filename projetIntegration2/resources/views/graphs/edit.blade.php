@extends('layouts.app')
@section('titre', 'Modifier un graphique')
@section('style')
<link rel="stylesheet" style="text/css" href="{{asset('css/graphs/graphs.css')}}">
@endsection()
@section('contenu')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h1 class="h3 mb-0 text-white">Modifier le graphique</h1>
                </div>
                <div class="card-body">
                    @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                    @endif
                    <form action="{{ route('graphs.update', $graph->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group mb-4">
                            <label for="titre" class="form-label text-white fw-bold">Titre du graphique:</label>
                            <input type="text" class="form-control @error('titre') is-invalid @enderror" id="titre" name="titre" required value="{{ old('titre', $graph->titre) }}">
                            @error('titre')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label text-white fw-bold">Type de graphique:</label>
                            <div class="p-3 border rounded ">
                                @if($graph->type == 'global')
                                    <span class="fw-medium">Scores globaux</span>
                                    <small class="d-block text-muted">Affiche les scores de tous les utilisateurs</small>
                                @else
                                    <span class="fw-medium">Scores de clan: {{ $graph->clan->nom ?? 'Non spécifié' }}</span>
                                    <small class="d-block text-muted">Affiche les scores d'un clan spécifique</small>
                                @endif
                            </div>
                            <!-- Hidden input to preserve the value -->
                            <input type="hidden" name="type" value="{{ $graph->type }}">
                            @if($graph->type == 'clan')
                                <input type="hidden" name="clan_id" value="{{ $graph->clan_id }}">
                            @endif
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_debut" class="form-label text-white fw-bold">Date de début:</label>
                                    <input type="date" class="form-control @error('date_debut') is-invalid @enderror" id="date_debut" name="date_debut" required value="{{ old('date_debut', $graph->date_debut->format('Y-m-d')) }}">
                                    @error('date_debut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_fin" class="form-label text-white fw-bold">Date de fin:</label>
                                    <input type="date" class="form-control @error('date_fin') is-invalid @enderror" id="date_fin" name="date_fin" required value="{{ old('date_fin', $graph->date_fin->format('Y-m-d')) }}">
                                    @error('date_fin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group d-flex justify-content-between">
                            <a href="{{ route('graphs.show', $graph->id) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Annuler
                            </a>
                            <button type="submit" class="btn bouton">
                                <i class="fas fa-save me-2"></i>Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection