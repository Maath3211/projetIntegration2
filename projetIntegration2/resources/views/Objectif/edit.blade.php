@extends('layouts.app')

@section('contenu')

<link rel="stylesheet" style="text/css" href="\css\Objectif\editObjectif.css"> 


@if (!$objectif)
    <div class="alert alert-warning text-center">
        {{ __('objectives.not_found') }} <a href="{{ route('objectif.index') }}">{{ __('objectives.back_to_list') }}</a>
    </div>
@else

    <div class="form-container">
        <form action="{{ route('objectif.update', $objectif->id) }}" method="POST">
            @csrf
            @method('PUT')

            <h1>{{ __('objectives.edit_title') }}</h1>

            <div class="mb-3">
                <label for="titre" class="form-label">{{ __('objectives.title') }}</label>
                <input type="text" class="form-control" id="titre" name="titre" value="{{ old('titre', $objectif->titre) }}" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">{{ __('objectives.description') }}</label>
                <textarea class="form-control" id="description" name="description" rows="4" required>{{ old('description', $objectif->description) }}</textarea>
            </div>
            <button type="submit" class="btn bouton mt-3">{{ __('objectives.update_button') }}</button>
        </form>

        <a href="{{ route('objectif.index') }}" class="btn btn-secondary mt-3 bouton-retour bouton">{{ __('objectives.back') }}</a>
    </div>
@endif

@endsection