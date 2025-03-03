@extends('layouts.app')

@section('contenu')

<link rel="stylesheet" style="text/css" href="\css\Objectif\editObjectif.css"> 


@if (!$objectif)
    <div class="alert alert-warning text-center">
        Aucun objectif trouvé. <a href="{{ route('objectif.index') }}">Retour à la liste</a>
    </div>
@else

    <div class="form-container">
        <form action="{{ route('objectif.update', $objectif->id) }}" method="POST">
            @csrf
            @method('PUT')

            <h1>Modifier l'Objectif</h1>

            <div class="mb-3">
                <label for="titre" class="form-label">Titre</label>
                <input type="text" class="form-control" id="titre" name="titre" value="{{ old('titre', $objectif->titre) }}" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4" required>{{ old('description', $objectif->description) }}</textarea>
            </div>
            <button type="submit" class="btn bouton mt-3">Mettre à jour</button>
        </form>

        <a href="{{ route('objectif.index') }}" class="btn btn-secondary mt-3 bouton-retour bouton">Retour</a>
    </div>
@endif

@endsection