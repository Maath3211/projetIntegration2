@extends('layouts.app')
@section('contenu')
<link rel="stylesheet" style="text/css" href="\css\Objectif\ObjectifIndex.css"> 

<div class="container">

    <h1 class="mb-4">Liste de vos objectifs</h1>

       <a href="{{ route('objectif.create') }}"> <button type="button" class="bouton margin-bottom">Ajouter un Objectif</button> </a>

 
    <ul class="list-group">
    @foreach ($objectifs as $objectif)
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <div>
                <strong>{{ $objectif->titre }}</strong>
                <p class="text-muted mb-0">{{ $objectif->description }}</p>
            </div>
     
            <div>
        <a href="{{ route('objectif.edit', $objectif->id) }}" class="btn">
            <i class="fas fa-cog" style="color: #a9fe77;"></i>
        </a>

                <form action="{{ route('objectif.destroy', $objectif->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn">
                        <i class="fas fa-trash-alt " style="color: red;"></i>
                    </button>
                </form>
            </div>
        </li>
    @endforeach
</ul>
</div>
@endsection