@extends('layouts.app')
@section('titre', __('graphs.titre_creer'))

@section('style')
<!-- Importation du fichier CSS spécifique pour les graphiques -->
<link rel="stylesheet" style="text/css" href="{{asset('css/graphs/graphs.css')}}">
@endsection

@section('contenu')
<!-- Conteneur principal avec marge supérieure -->
<div class="container mt-4">
    <!-- Disposition en ligne avec centrage -->
    <div class="row justify-content-center">
        <!-- Colonne occupant 10/12 de la largeur sur les écrans moyens et plus grands -->
        <div class="col-md-10">
            <!-- Carte avec ombre légère -->
            <div class="card shadow-sm">
                <!-- En-tête de la carte contenant le titre du formulaire -->
                <div class="card-header">
                    <h1 class="h3 mb-0 text-white">{{ __('graphs.creer_graphique_personnalise') }}</h1>
                </div>
                <!-- Corps de la carte contenant le formulaire -->
                <div class="card-body">
                    <!-- Affichage des messages d'erreur de session -->
                    @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                    @endif

                    <!-- Formulaire de création de graphique -->
                    <form action="{{ route('graphs.store') }}" method="POST">
                        <!-- Token CSRF pour la protection contre les attaques -->
                        @csrf
                        <!-- Groupe pour le titre du graphique -->
                        <div class="form-group mb-4">
                            <label for="titre" class="form-label text-white fw-bold">{{ __('graphs.titre_du_graphique') }}</label>
                            <!-- Champ de saisie avec validation et conservation de la valeur précédente en cas d'erreur -->
                            <input type="text" class="form-control @error('titre') is-invalid @enderror" id="titre" name="titre" required value="{{ old('titre') }}">
                            <!-- Affichage du message d'erreur spécifique au champ titre -->
                            @error('titre')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Groupe pour le choix du type de graphique -->
                        <div class="form-group mb-4">
                            <label class="form-label text-white fw-bold">{{ __('graphs.type_de_graphique') }}</label>
                            <!-- Option pour les scores globaux (tous utilisateurs) -->
                            <div class="form-check mb-2">
                                <!-- Bouton radio avec état par défaut à "global" -->
                                <input class="form-check-input" type="radio" name="type" id="typeGlobal" value="global" {{ old('type', 'global') == 'global' ? 'checked' : '' }}>
                                <label class="form-check-label" for="typeGlobal">
                                    <span class="fw-medium">{{ __('graphs.scores_globaux') }}</span>
                                    <!-- Description explicative du type global -->
                                    <small class="text-muted d-block">{{ __('graphs.scores_globaux_desc') }}</small>
                                </label>
                            </div>
                            <!-- Option pour les scores d'un clan spécifique -->
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type" id="typeClan" value="clan" {{ old('type') == 'clan' ? 'checked' : '' }}>
                                <label class="form-check-label" for="typeClan">
                                    <span class="fw-medium">{{ __('graphs.scores_clan') }}</span>
                                    <!-- Description explicative du type clan -->
                                    <small class="text-muted d-block">{{ __('graphs.scores_clan_desc') }}</small>
                                </label>
                            </div>
                            <!-- Affichage des erreurs de validation pour le type -->
                            @error('type')
                            <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Groupe pour la sélection du clan (visible uniquement si le type "clan" est sélectionné) -->
                        <div class="form-group mb-4" id="clanSelectDiv" style="display: none;">
                            <label for="clan_id" class="form-label text-white fw-bold">{{ __('graphs.selectionner_clan') }}</label>
                            <!-- Liste déroulante des clans disponibles -->
                            <select class="form-control @error('clan_id') is-invalid @enderror" id="clan_id" name="clan_id">
                                <!-- Boucle sur les clans ou message si la liste est vide -->
                                @forelse($clans as $clan)
                                <option value="{{ $clan->id }}" {{ old('clan_id') == $clan->id ? 'selected' : '' }}>{{ $clan->nom }}</option>
                                @empty
                                <option disabled>{{ __('graphs.aucun_clan_disponible') }}</option>
                                @endforelse
                            </select>
                            <!-- Alerte si l'utilisateur n'appartient à aucun clan -->
                            @if($clans->isEmpty())
                            <div class="alert alert-warning mt-2">
                                <i class="fa-solid fa-circle-exclamation me-2"></i>
                                {{ __('graphs.aucun_clan') }}
                            </div>
                            @endif
                            <!-- Affichage des erreurs de validation pour clan_id -->
                            @error('clan_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Groupe pour la sélection des dates en deux colonnes -->
                        <div class="row mb-4">
                            <!-- Colonne pour la date de début -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_debut" class="form-label text-white fw-bold">{{ __('graphs.date_debut') }}</label>
                                    <!-- Sélecteur de date avec valeur par défaut (3 mois avant aujourd'hui) -->
                                    <input type="date" class="form-control @error('date_debut') is-invalid @enderror" id="date_debut" name="date_debut" required value="{{ old('date_debut', now()->subMonths(3)->format('Y-m-d')) }}">
                                    @error('date_debut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <!-- Colonne pour la date de fin -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_fin" class="form-label text-white fw-bold">{{ __('graphs.date_fin') }}</label>
                                    <!-- Sélecteur de date avec valeur par défaut (aujourd'hui) -->
                                    <input type="date" class="form-control @error('date_fin') is-invalid @enderror" id="date_fin" name="date_fin" required value="{{ old('date_fin', now()->format('Y-m-d')) }}">
                                    @error('date_fin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Groupe pour les boutons d'action avec espacement entre eux -->
                        <div class="form-group d-flex justify-content-between">
                            <!-- Bouton de retour à la liste des graphiques -->
                            <a href="{{ route('graphs.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>{{ __('graphs.retour') }}
                            </a>
                            <!-- Bouton de soumission du formulaire, désactivé si l'option clan est sélectionnée mais qu'aucun clan n'est disponible -->
                            <button type="submit" class="btn bouton" {{ $clans->isEmpty() && old('type') == 'clan' ? 'disabled' : '' }}>
                                <i class="fas fa-chart-line me-2"></i>{{ __('graphs.generer_graphique') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    // Attente du chargement complet du DOM avant d'exécuter le script
    document.addEventListener('DOMContentLoaded', function() {
        // Récupération des éléments du DOM
        const typeGlobal = document.getElementById('typeGlobal');
        const typeClan = document.getElementById('typeClan');
        const clanSelectDiv = document.getElementById('clanSelectDiv');

        // Fonction pour afficher/masquer le sélecteur de clan selon le type choisi
        function toggleClanSelect() {
            if (typeClan.checked) {
                // Affichage du sélecteur de clan avec animation si le type "clan" est sélectionné
                clanSelectDiv.style.display = 'block';
                clanSelectDiv.classList.add('animate__animated', 'animate__fadeIn');
            } else {
                // Masquage du sélecteur si le type "global" est sélectionné
                clanSelectDiv.style.display = 'none';
            }
        }

        // Application de l'état initial lors du chargement
        toggleClanSelect();

        // Ajout des écouteurs d'événements pour réagir aux changements de sélection
        typeGlobal.addEventListener('change', toggleClanSelect);
        typeClan.addEventListener('change', toggleClanSelect);
    });
</script>
@endsection
@endsection