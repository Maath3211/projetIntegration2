@extends('Layouts.app')
@section('contenu')

<link rel="stylesheet" style="text/css" href="\css\Statistique\statistiqueIndex.css"> 

<div class="container">

    <div class="flex justify-center mt-4">
        <div class="profileImage"><img src="{{ $usager->imageProfil }}" alt="image profil" id="image"/></div>
    </div>

    <div class="flex flex-wrap justify-center space-x-4 mt-4">
        <h1>Statistiques de {{ $usager->prenom }}</h1>
    </div>

    
    <div class="flex flex-wrap justify-center space-x-4 mt-4">
      <a href="/profil">  <button class="bouton">Profil</button></a>
      <a href="/objectif">  <button class="bouton">Voir mes objectifs</button></a>
    </div>
    

    <div class="statContainer space-y-4">
        <div class="statRow">
            <span>Vos statistiques :</span>
            <a href="/thermique" class="text-gray-400">Voir mon calendrier</a>
        </div>
        
        <div class="statRow">
            <span>Nombre de fois au gym: {{ isset($foisGym) ? $foisGym->first()->score : 'N/A' }} fois</span>
        </div>
        
        <div class="statRow">
            <span id="poidsValue" data-lbs="{{ isset($poids) ? $poids : 'N/A' }}">
              Votre poids le plus bas : {{ isset($poids) ? $poids : 'N/A' }} lbs
            </span>
            <div class="flex space-x-2">
                <button class="bouton" onclick="convertWeight('lbs')">Lbs</button>
                <button class="bouton" onclick="convertWeight('kg')">Kg</button>
                <a href="/graphique" class="text-gray-400">Voir mon graphique</a>
            </div>
        </div>
    
        <div class="flex justify-center mt-4">
            <button class="bouton" onclick="showAddExerciseForm()">Ajouter un exercice</button>
        </div>

     
        <div id="addExerciseForm" class="statRow hidden">
            <input type="text" id="exerciseName" placeholder="Nom de l'exercice" class="input" />
            <input type="number" id="exerciseScore" placeholder="Score lbs ou km" class="input" />
            <button class="bouton" onclick="saveExercise()">Sauvegarder</button>
            <button type="button" class="bouton" onclick="cancelForm()">Annuler</button>
        </div>

        @foreach($statistiques as $stat)
            <div class="statRow" id="exercise-{{ $stat->id }}">

             <!-- Recuperer les plus haut stat (A FAIRE!!!!!) -->
                <span>{{ $stat->nomStatistique }} : {{ $stat->score }} 
                {{ in_array($stat->nomStatistique, ['course', 'run', 'marathon', 'marche', 'sprint', 'jogging', 'trail', 'velo', 'bike', 'cycling']) ? 'km' : 'lbs' }}
                </span>
                <div class="flex space-x-2">
                    @if(in_array($stat->nomStatistique, ['course', 'run', 'marathon', 'marche', 'sprint', 'jogging', 'trail', 'velo', 'bike', 'cycling']))
                        <button class="bouton" onclick="convertRunUnit(this, 'km')">Km</button>
                        <button class="bouton" onclick="convertRunUnit(this, 'miles')">Miles</button>
                    @else
                        <button class="bouton" onclick="convertWeightUnit(this, 'lbs')">Lbs</button>
                        <button class="bouton" onclick="convertWeightUnit(this, 'kg')">Kg</button>
                    @endif
                    <button class="bouton"  onclick="deleteExercise({{ $stat->id }})">🗑️</button>
                    <a href="{{route('statistique.graphiqueExercice', [$stat->id])}}" class="text-gray-400">Voir mon graphique</a>
                </div>
            </div>
        @endforeach
    </div>
</div>

<script src="{{ asset('js/Statistiques/index.js') }}" crossorigin="anonymous"> </script>



@endsection
