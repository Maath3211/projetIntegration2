@extends('layouts.app')

@section('contenu')
<link rel="stylesheet" style="text/css" href="\css\Objectif\createObjectif.css">
<div class="container">
    <div class="form-box">
        <h1>{{ __('objectives.create_title') }}</h1>

        <form action="{{ route('objectif.store') }}" method="POST">
            @csrf

            <label for="titre" class="label">{{ __('objectives.objective_title') }}</label>
            <input type="text" id="titre" name="titre" class="input" placeholder="{{ __('objectives.title_placeholder') }}" required>

            <label for="description" class="label">{{ __('objectives.objective_description') }}</label>
            <textarea id="description" name="description" class="input textarea" placeholder="{{ __('objectives.description_placeholder') }}" required></textarea>

            <button type="submit" class="bouton">{{ __('objectives.add_button') }}</button>
        </form>

        <a href="{{ route('objectif.index') }}" class="bouton bouton-retour">{{ __('objectives.back') }}</a>
    </div>
</div>
@endsection