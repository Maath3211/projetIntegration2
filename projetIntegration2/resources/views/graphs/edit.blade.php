@extends('layouts.app')
@section('titre', __('graphs.titre_modifier'))
@section('style')
<!-- Importation de la feuille de style spécifique aux graphiques -->
<link rel="stylesheet" style="text/css" href="{{asset('css/graphs/graphs.css')}}">
@endsection
@section('contenu')
<!-- Conteneur principal avec marge supérieure -->
<div class="container mt-4">
    <!-- Ligne avec centrage du contenu -->
    <div class="row justify-content-center">
        <!-- Colonne occupant 10/12 de la largeur sur écrans moyens et grands -->
        <div class="col-md-10">
            <!-- Carte avec ombre légère -->
            <div class="card shadow-sm">
                <!-- En-tête de la carte avec le titre du formulaire -->
                <div class="card-header">
                    <h1 class="h3 mb-0 text-white">{{ __('graphs.modifier_graphique') }}</h1>
                </div>
                <!-- Corps de la carte contenant le formulaire -->
                <div class="card-body">
                    <!-- Affichage des messages d'erreur de la session -->
                    @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                    @endif
                    <!-- Formulaire de modification avec méthode PUT -->
                    <form action="{{ route('graphs.update', $graph->id) }}" method="POST">
                        <!-- Token CSRF pour la protection contre les attaques -->
                        @csrf
                        <!-- Méthode HTTP PUT simulée (HTML ne supporte que GET et POST) -->
                        @method('PUT')
                        <!-- Groupe pour le titre du graphique -->
                        <div class="form-group mb-4">
                            <label for="titre" class="form-label text-white fw-bold">{{ __('graphs.titre_du_graphique') }}</label>
                            <!-- Champ de saisie avec validation et valeur actuelle ou précédemment soumise -->
                            <input type="text" class="form-control @error('titre') is-invalid @enderror" id="titre" name="titre" required value="{{ old('titre', $graph->titre) }}">
                            <!-- Affichage des erreurs spécifiques au champ titre -->
                            @error('titre')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Groupe pour le type de graphique (en lecture seule) -->
                        <div class="form-group mb-4">
                            <label class="form-label text-white fw-bold">{{ __('graphs.type_de_graphique') }}</label>
                            <!-- Affichage du type actuel sans possibilité de modification -->
                            <div class="p-3 border rounded">
                                @if($graph->type == 'global')
                                    <!-- Affichage pour type global -->
                                    <span class="fw-medium">{{ __('graphs.scores_globaux') }}</span>
                                    <small class="d-block text-muted">{{ __('graphs.scores_globaux_desc') }}</small>
                                @else
                                    <!-- Affichage pour type clan avec nom du clan -->
                                    <span class="fw-medium">{{ __('graphs.scores_clan') }}: {{ $graph->clan->nom ?? 'Non spécifié' }}</span>
                                    <small class="d-block text-muted">{{ __('graphs.scores_clan_desc') }}</small>
                                @endif
                            </div>
                            <!-- Champs cachés pour conserver les valeurs actuelles -->
                            <input type="hidden" name="type" value="{{ $graph->type }}">
                            @if($graph->type == 'clan')
                                <input type="hidden" name="clan_id" value="{{ $graph->clan_id }}">
                            @endif
                        </div>

                        <!-- Groupe pour les dates en deux colonnes -->
                        <div class="row mb-4">
                            <!-- Colonne pour la date de début -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_debut" class="form-label text-white fw-bold">{{ __('graphs.date_debut') }}</label>
                                    <!-- Sélecteur de date avec valeur actuelle formatée -->
                                    <input type="date" class="form-control @error('date_debut') is-invalid @enderror" id="date_debut" name="date_debut" required value="{{ old('date_debut', $graph->date_debut->format('Y-m-d')) }}">
                                    <!-- Affichage des erreurs spécifiques au champ date_debut -->
                                    @error('date_debut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <!-- Colonne pour la date de fin -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_fin" class="form-label text-white fw-bold">{{ __('graphs.date_fin') }}</label>
                                    <!-- Sélecteur de date avec valeur actuelle formatée -->
                                    <input type="date" class="form-control @error('date_fin') is-invalid @enderror" id="date_fin" name="date_fin" required value="{{ old('date_fin', $graph->date_fin->format('Y-m-d')) }}">
                                    <!-- Affichage des erreurs spécifiques au champ date_fin -->
                                    @error('date_fin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Groupe pour les boutons d'action avec espacement entre eux -->
                        <div class="form-group d-flex justify-content-between">
                            <!-- Bouton d'annulation qui renvoie à la page de détails du graphique -->
                            <a href="{{ route('graphs.show', $graph->id) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>{{ __('graphs.annuler') }}
                            </a>
                            <!-- Bouton de soumission du formulaire pour enregistrer les modifications -->
                            <button type="submit" class="btn bouton">
                                <i class="fas fa-save me-2"></i>{{ __('graphs.enregistrer_modifications') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection