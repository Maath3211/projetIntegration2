@extends('layouts.app')
@section('contenu')
<link rel="stylesheet" style="text/css" href="\css\Objectif\ObjectifIndex.css"> 

<div class="container">
    <h1 class="mb-4">{{ __('objectives.titre_liste') }}</h1>
    
    <div class="d-flex justify-content-between mb-4">
        <a href="{{ route('statistique.index') }}">
            <button type="button" class="bouton me-2">{{ __('objectives.retour') }}</button>
        </a>
        <a href="{{ route('objectif.create') }}">
            <button type="button" class="bouton ms-2">{{ __('objectives.ajout_objective') }}</button>
        </a>
    </div>
    
    <h2 class="mt-4">{{ __('objectives.objectifs_non_complets') }}</h2>
    <ul class="list-group">
        @foreach ($objectifNonCompleter as $objectif)
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <div>
                <strong>{{ $objectif->titre }}</strong>
                <p class="text-muted mb-0">{{ $objectif->description }}</p>
            </div>
            
            <div class="d-flex align-items-center">
                <form action="{{ route('objectif.updateComplet', $objectif->id) }}" method="POST" class="d-inline me-2">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="completer" value="0">
                    <input type="checkbox" name="completer" value="1" onchange="this.form.submit()" {{ $objectif->completer ? 'checked' : '' }} />
                </form>
                
                <a href="{{ route('objectif.edit', $objectif->id) }}" class="btn">
                    <i class="fas fa-cog" style="color: #a9fe77;"></i>
                </a>
                
                <form action="{{ route('objectif.destroy', $objectif->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn">
                        <i class="fas fa-trash-alt" style="color: red;"></i>
                    </button>
                </form>
            </div>
        </li>
        @endforeach
    </ul>
    
    <h2 class="mt-4">{{ __('objectives.objectifs_complets') }}</h2>
    <ul class="list-group">
        @foreach ($objectifCompleter as $objectif)
        <li class="list-group-item d-flex justify-content-between align-items-center completer">
            <div>
                <strong>{{ $objectif->titre }}</strong>
                <p class="text-muted mb-0">{{ $objectif->description }}</p>
            </div>
            
            <div class="d-flex align-items-center">
                <form action="{{ route('objectif.updateComplet', $objectif->id) }}" method="POST" class="d-inline me-2">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="completer" value="0">
                    <input type="checkbox" name="completer" value="1" onchange="this.form.submit()" {{ $objectif->completer ? 'checked' : '' }} />
                </form>
                
                <a href="{{ route('objectif.edit', $objectif->id) }}" class="btn">
                    <i class="fas fa-cog" style="color: #a9fe77;"></i>
                </a>
                
                <form action="{{ route('objectif.destroy', $objectif->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn">
                        <i class="fas fa-trash-alt" style="color: red;"></i>
                    </button>
                </form>
            </div>
        </li>
        @endforeach
    </ul>
</div>
@endsection