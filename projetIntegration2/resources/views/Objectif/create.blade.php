@extends('layouts.app')

@section('contenu')
<link rel="stylesheet" style="text/css" href="\css\Objectif\createObjectif.css"> 
<div class="container">
    <div class="form-box">

        <form action="{{ route('objectif.store') }}" method="POST">
            @csrf

     
            <label for="titre" class="label">Titre :</label>
            <input type="text" id="titre" name="titre" class="input" placeholder="Titre de l'objectif" required>


            <label for="description" class="label">Description :</label>
            <textarea id="description" name="description" class="input textarea" placeholder="Décris ton objectif..." required></textarea>

 
            <button type="submit" class="bouton">Ajouter</button>
        </form>


        <a href="{{ route('objectif.index') }}" class="bouton bouton-retour">Retour</a>
    </div>
</div>
@endsection