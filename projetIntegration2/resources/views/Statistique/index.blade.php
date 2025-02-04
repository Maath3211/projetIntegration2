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
    .profile-image {
        width: 6rem;
        height: 6rem;
        background-color: #666;
        border-radius: 50%;
    }
    .nav-button {
        background-color: #a9fe77;
        padding: 0.5rem 1rem;
        color: black;
        border-radius: 0.25rem;
    }
    .stat-container {
        margin-top: 1.5rem;
        width: 100%;
        max-width: 75%;
    }
    .stat-row {
        display: flex;
        justify-content: space-between;
        border-bottom: 1px solid #666;
        padding-bottom: 0.5rem;
        flex-wrap: wrap;
    }
    .gray-button {
        background-color: #a9fe77;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        color:black
    }
    .add-workout {
        background-color: #a9fe77;
        padding: 0.5rem 1rem;
        color: black;
        border-radius: 0.25rem;
        margin-top: 1rem;
    }
</style>

<div class="container">
    <!-- Profil Image -->
    <div class="flex justify-center mt-4">
        <div class="profile-image"></div>
    </div>
    
    <!-- Navigation -->
    <div class="flex flex-wrap justify-center space-x-4 mt-4">
        <button class="nav-button">Profil</button>
        <button class="nav-button">Statistiques</button>
    </div>
    
    <!-- Statistiques -->
    <div class="stat-container space-y-4">
        <div class="stat-row">
            <span>Vos statistiques :</span>
            <a href="#" class="text-gray-400">Voir mon calendrier</a>
        </div>
        
        <div class="stat-row">
            <span>Nombre de fois au gym: N/A</span>
        </div>
        
        <div class="stat-row">
            <span>Meilleur suite : N/A</span>
        </div>
        
        <div class="stat-row">
            <span>Poids : N/A lbs</span>
            <div class="flex space-x-2">
                <button class="gray-button">Lbs</button>
                <button class="gray-button">Kg</button>
                <a href="#" class="text-gray-400">Voir mon  graphique</a>
            </div>
        </div>
        
        <!-- Bouton Ajouter un workout -->
        <div class="flex justify-center mt-4">
            <button class="add-workout">Ajouter un workout</button>
        </div>
        
        <div class="stat-row">
            <span>Développé coucher: N/A kg</span>
            <div class="flex space-x-2">
                <button class="gray-button">Lbs</button>
                <button class="gray-button">Kg</button>
                <button class="gray-button">🗑️</button>
                <a href="#" class="text-gray-400">Voir mon  graphique</a>
            </div>
        </div>
        
        <div class="stat-row">
            <span>Nouveau exercice :</span>
            <div class="flex space-x-2">
 
                <button class="gray-button">🗑️</button>
                <a href="#" class="text-gray-400">Voir mon  graphique</a>
            </div>
        </div>
    </div>
</div>
@endsection
