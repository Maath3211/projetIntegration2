@extends('Layouts.app')
@section('contenu')

<link rel="stylesheet" style="text/css" href="\css\Statistique\statistiqueIndex.css"> 

<div class="container">

    <div class="flex justify-center mt-4">
        <div class="profileImage"><img src="{{ $usager->imageProfil }}" alt="image profil" id="image"/></div>
    </div>

    <div class="flex flex-wrap justify-center space-x-4 mt-4">
        <h1>{{ __('stats.statistics_of') }} {{ $usager->prenom }}</h1>
    </div>

    
    <div class="flex flex-wrap justify-center space-x-4 mt-4">
      <a href="/ajouterFoisGym">  <button class="bouton">ajouter compteur gym</button></a>
      <a href="/profil">  <button class="bouton">{{ __('stats.profile') }}</button></a>
      <a href="/objectif">  <button class="bouton">{{ __('stats.view_objectives') }}</button></a>
    </div>
    

    <div class="statContainer space-y-4">
        <div class="statRow">
            <span>{{ __('stats.your_statistics') }}</span>
            <a href="/thermique" class="text-gray-400">{{ __('stats.view_calendar') }}</a>
        </div>
        
        <div class="statRow">
            <span>{{ __('stats.gym_visits') }} {{ isset($foisGym) ? $foisGym->first()->score : 'N/A' }} {{ __('stats.times') }}</span>
        </div>
        
        <div class="statRow">
            <span id="poidsValue" data-lbs="{{ isset($poids) ? $poids : 'N/A' }}">
              {{ __('stats.lowest_weight') }} {{ isset($poids) ? $poids : 'N/A' }} lbs
            </span>
            <div class="flex space-x-2">
                <button class="bouton" onclick="convertWeight('lbs')">Lbs</button>
                <button class="bouton" onclick="convertWeight('kg')">Kg</button>
                <a href="/graphique" class="text-gray-400">{{ __('stats.view_chart') }}</a>
            </div>
        </div>
    
        <div class="flex justify-center mt-4">
            <button class="bouton" onclick="showAddExerciseForm()">{{ __('stats.add_exercise') }}</button>
        </div>

     
        <div id="addExerciseForm" class="statRow hidden">
            <input type="text" id="exerciseName" placeholder="{{ __('stats.exercise_name') }}" class="input" />
            <input type="number" id="exerciseScore" placeholder="{{ __('stats.score') }}" class="input" />
            <button class="bouton" onclick="saveExercise()">{{ __('stats.save') }}</button>
            <button type="button" class="bouton" onclick="cancelForm()">{{ __('stats.cancel') }}</button>
        </div>

        @foreach($statistiques as $stat)
            <div class="statRow" id="exercise-{{ $stat->id }}">
                <span>Score le plus haut pour {{$stat->nomStatistique}}: {{ $scoreHaut->firstWhere('statistique_id', $stat->id)->max_score ?? 'N/A' }}
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
                    <a href="{{route('statistique.graphiqueExercice', [$stat->id])}}" class="text-gray-400">{{ __('stats.view_chart') }}</a>
                </div>
            </div>
        @endforeach
    </div>
</div>

<script src="{{ asset('js/Statistiques/index.js') }}" crossorigin="anonymous"> </script>



@endsection
