@extends('layouts.app')
@section('contenu')

<link rel="stylesheet" style="text/css" href="\css\Statistique\statistiqueIndex.css"> 

<div class="container">

    <div class="flex justify-center mt-4">
        <div class="profileImage"><img src="{{ $usager->imageProfil }}" alt="image profil" id="image"/></div>
    </div>

    <div class="flex flex-wrap justify-center space-x-4 mt-4">
        <h1>{{ __('stats.statistique_de') }} {{ $usager->prenom }}</h1>
    </div>

    
    <div class="flex flex-wrap justify-center space-x-4 mt-4">
      <a href="/profil">  <button class="bouton">{{ __('stats.profil') }}</button></a>
      <a href="/objectif">  <button class="bouton">{{ __('stats.voir_objectifs') }}</button></a>
    </div>
    

    <div class="statContainer space-y-4">
        <div class="statRow">
            <span>{{ __('stats.vos_statistiques') }}</span>
            <a href="/thermique" class="text-gray-400">{{ __('stats.voir_calendrier') }}</a>
        </div>
        
        <div class="statRow">
            <span>{{ __('stats.objectif_completer') }} {{ isset($foisGym) ? $foisGym : 'N/A' }}</span>
        </div>
        
        <div class="statRow">
            <span id="poidsValue" data-lbs="{{ isset($poids) ? $poids : 'N/A' }}">
              {{ __('stats.poids_min') }} {{ isset($poids) ? $poids : 'N/A' }} lbs
            </span>
            <div class="flex space-x-2">
                <button class="bouton" onclick="convertirPoids('lbs')">Lbs</button>
                <button class="bouton" onclick="convertirPoids('kg')">Kg</button>
                <a href="/graphique" class="text-gray-400">{{ __('stats.voir_graphique') }}</a>
            </div>
        </div>
    
        <div class="flex justify-center mt-4">
            <button class="bouton" onclick="afficherAjouterExerciceFormulaire()">{{ __('stats.ajout_exercice') }}</button>
        </div>

     
        <div id="ajouterExerciceFormulaire" class="statRow hidden">
            <input type="text" id="exerciseNom" placeholder="{{ __('stats.nom_exercice') }}" class="input" />
            <input type="number" id="exerciseScore" placeholder="{{ __('stats.score') }}" class="input" />
            <button class="bouton" onclick="sauvegarderExercice()">{{ __('stats.sauvegarde') }}</button>
            <button type="button" class="bouton" onclick="annulerFormulaire()">{{ __('stats.annuler') }}</button>
        </div>

        @foreach($statistiques as $stat)
            <div class="statRow" id="exercise-{{ $stat->id }}">
                <span>{{__('stats.score_plus_haut')}} {{$stat->nomStatistique}}: {{ $scoreHaut->firstWhere('statistique_id', $stat->id)->max_score ?? 'N/A' }}
                {{ in_array($stat->nomStatistique, ['course', 'run', 'marathon', 'marche', 'sprint', 'jogging', 'trail', 'velo', 'bike', 'cycling']) ? 'km' : 'lbs' }}
                </span>
                <div class="flex space-x-2">
                    @if(in_array($stat->nomStatistique, ['course', 'run', 'marathon', 'marche', 'sprint', 'jogging', 'trail', 'velo', 'bike', 'cycling']))
                        <button class="bouton" onclick="convertirCourseUnite(this, 'km')">Km</button>
                        <button class="bouton" onclick="convertirCourseUnite(this, 'miles')">Miles</button>
                    @else
                        <button class="bouton" onclick="convertirPoidsUnite(this, 'lbs')">Lbs</button>
                        <button class="bouton" onclick="convertirPoidsUnite(this, 'kg')">Kg</button>
                    @endif
                    <button class="bouton"  onclick="supprimerExercise({{ $stat->id }})">🗑️</button>
                    <a href="{{route('Statistique.graphiqueExercice', [$stat->id])}}" class="text-gray-400">{{ __('stats.voir_graphique') }}</a>
                </div>
            </div>
        @endforeach
    </div>
</div>

<script src="{{ asset('js/Statistiques/index.js') }}" crossorigin="anonymous"> </script>



@endsection
