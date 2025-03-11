@extends('layouts.app')

@section('contenu')
<link rel="stylesheet" style="text/css" href="\css\Objectif\createObjectif.css">
<div class="container">
    <div class="form-box">
        <h1>{{ __('objectives.creer_titre') }}</h1>

        <form action="{{ route('objectif.store') }}" method="POST">
            @csrf

            <label for="titre" class="label">{{ __('objectives.titre_objectif') }}</label>
            <input type="text" id="titre" name="titre" class="input" placeholder="{{ __('objectives.titre_exemple') }}" required>

            <label for="description" class="label">{{ __('objectives.description_objectif') }}</label>
            <textarea id="description" name="description" class="input textarea" placeholder="{{ __('objectives.description_exemple') }}" required></textarea>

            <button type="submit" class="bouton">{{ __('objectives.ajout_bouton') }}</button>
        </form>

        <a href="{{ route('objectif.index') }}" class="bouton bouton-retour">{{ __('objectives.retour') }}</a>
    </div>
</div>
@endsection