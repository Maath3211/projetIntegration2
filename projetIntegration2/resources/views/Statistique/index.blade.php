@extends('Layouts.app')
@section('contenu')

<style>
    body {
        background-color: #1a1a1a;
    }
    .container {
        width: 100%;
        min-height: 100vh;
        background-color: #1a1a1a;
        padding: 1.5rem;
        border-radius: 0.5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .profileImage {
        width: 6rem;
        height: 6rem;
        background-color: #666;
        border-radius: 50%;
    }
    .navBouton {
        background-color: #a9fe77;
        padding: 0.5rem 1rem;
        color: black;
        border-radius: 0.25rem;
    }
    .statContainer {
        margin-top: 1.5rem;
        width: 100%;
        max-width: 75%;
    }
    .statRow {
        display: flex;
        justify-content: space-between;
        border-bottom: 1px solid #666;
        padding-bottom: 0.5rem;
        flex-wrap: wrap;
    }
    h1 {
        font-size: 48px;
        font-weight: bold; 
        color: #a9fe77; 
        text-transform: uppercase; 
        letter-spacing: 2px; 
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3); 
        margin-bottom: 20px;
    }
</style>

<div class="container">
    <!-- Profil Image -->
    <div class="flex justify-center mt-4">
        <div class="profileImage"><img src="{{ $usager->imageProfil }}" alt="image profil" /></div>
    </div>

    <div class="flex flex-wrap justify-center space-x-4 mt-4">
        <h1>Statistiques de Loick</h1>
    </div>
    
    <!-- Navigation -->
    <div class="flex flex-wrap justify-center space-x-4 mt-4">
        <button class="bouton">Profil</button>
    </div>
    
    <!-- Statistiques -->
    <div class="statContainer space-y-4">
        <div class="statRow">
            <span>Vos statistiques :</span>
            <a href="/thermique" class="text-gray-400">Voir mon calendrier</a>
        </div>
        
        <div class="statRow">
            <span>Nombre de fois au gym: N/A</span>
        </div>
        
        <div class="statRow">
            <span>Meilleur suite : N/A</span>
        </div>
        
        <div class="statRow">
            <span>Poids : N/A lbs</span>
            <div class="flex space-x-2">
                <button class="bouton">Lbs</button>
                <button class="bouton">Kg</button>
                <a href="/graphique" class="text-gray-400">Voir mon  graphique</a>
            </div>
        </div>
        
        <!-- Bouton Ajouter un workout -->
        <div class="flex justify-center mt-4">
            <button class="bouton">Ajouter un exercice</button>
        </div>
        
        <div class="statRow">
            <span>Développé coucher: N/A kg</span>
            <div class="flex space-x-2">
                <button class="bouton">Lbs</button>
                <button class="bouton">Kg</button>
                <button class="bouton">🗑️</button>
                <a href="#" class="text-gray-400">Voir mon  graphique</a>
            </div>
        </div>
        
        <div class="statRow">
            <span>Nouveau exercice :</span>
            <div class="flex space-x-2">
 
                <button class="bouton">🗑️</button>
                <a href="#" class="text-gray-400">Voir mon  graphique</a>
            </div>
        </div>
    </div>
</div>
@endsection
