@extends('layouts.app')
@section('titre', __('graphs.titre_graphiques'))
@section('style')
<!-- Importation de la feuille de style spécifique pour les graphiques -->
<link rel="stylesheet" style="text/css" href="{{asset('css/graphs/graphs.css')}}">
@endsection()

@section('contenu')
<!-- Conteneur principal avec marge supérieure -->
<div class="container mt-4">
    <!-- Ligne avec centrage du contenu -->
    <div class="row justify-content-center">
        <!-- Colonne prenant presque toute la largeur sur écrans moyens et grands -->
        <div class="col-md-11">
            <!-- Carte avec ombre légère -->
            <div class="card shadow-sm">
                <!-- En-tête de la carte avec titre et bouton d'ajout de graphique -->
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h1 class="mb-0 h3 text-white">{{ __('graphs.titre_graphiques') }}</h1>
                    <!-- Bouton pour créer un nouveau graphique -->
                    <a href="{{ route('graphs.create') }}" class="btn bouton">
                        <i class="fas fa-plus-circle me-2"></i>{{ __('graphs.nouveau_graphique') }}
                    </a>
                </div>
                <!-- Corps de la carte avec la liste des graphiques -->
                <div class="card-body">
                    <!-- Affichage des messages de succès (après création/modification/suppression) -->
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <!-- Bouton pour fermer l'alerte -->
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <!-- Affichage d'un message si aucun graphique n'existe encore -->
                    @if($graphs->isEmpty())
                    <div class="text-center py-5">
                        <!-- Icône de graphique grisée -->
                        <div class="mb-4">
                            <i class="fas fa-chart-line fa-4x text-muted"></i>
                        </div>
                        <!-- Message informatif -->
                        <h3 class="h5 mb-3">{{ __('graphs.graphiques_vides') }}</h3>
                        <p class="text-muted mb-4">{{ __('graphs.creer_premier') }}</p>
                        <!-- Bouton pour créer un premier graphique -->
                        <a href="{{ route('graphs.create') }}" class="btn bouton">
                            <i class="fas fa-plus-circle me-2"></i>{{ __('graphs.creer_graphique') }}
                        </a>
                    </div>
                    @else
                    <!-- Tableau responsive pour la liste des graphiques -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <!-- En-tête du tableau -->
                            <thead class="table-light">
                                <tr>
                                    <!-- Colonnes du tableau -->
                                    <th>{{ __('graphs.titre') }}</th>
                                    <th>{{ __('graphs.type') }}</th>
                                    <th>{{ __('graphs.periode') }}</th>
                                    <th>{{ __('graphs.cree_le') }}</th>
                                    <th>{{ __('graphs.expire_le') }}</th>
                                    <th class="text-center">{{ __('graphs.actions') }}</th>
                                </tr>
                            </thead>
                            <!-- Corps du tableau avec la liste des graphiques -->
                            <tbody>
                                @foreach($graphs as $graph)
                                <tr class="text-white">
                                    <!-- Colonne du titre du graphique, en gras -->
                                    <td class="fw-medium">{{ $graph->titre }}</td>
                                    <!-- Colonne du type de graphique avec badge coloré différent selon le type -->
                                    <td>
                                        @if($graph->type == 'global')
                                        <!-- Badge bleu pour les graphiques globaux -->
                                        <span class="badge bg-primary">{{ __('graphs.global') }}</span>
                                        @elseif($graph->clan)
                                        <!-- Badge vert pour les graphiques spécifiques à un clan -->
                                        <span class="badge" style="background-color: #a9fe77;">{{ __('graphs.clan') }}: {{ $graph->clan->nom }}</span>
                                        @else
                                        <!-- Badge gris pour les cas indéterminés -->
                                        <span class="badge bg-secondary">{{ __('graphs.inconnu') }}</span>
                                        @endif
                                    </td>
                                    <!-- Colonne de la période du graphique avec flèche entre les dates -->
                                    <td>
                                        <span class="text-nowrap">{{ $graph->date_debut->format('d/m/Y') }}</span>
                                        <i class="fas fa-arrow-right mx-1 small"></i>
                                        <span class="text-nowrap">{{ $graph->date_fin->format('d/m/Y') }}</span>
                                    </td>
                                    <!-- Date de création du graphique -->
                                    <td>{{ $graph->created_at->format('d/m/Y') }}</td>
                                    <!-- Date d'expiration avec mise en évidence si proche de l'expiration -->
                                    <td>
                                        @php
                                        // Calcul du nombre de jours avant expiration
                                        $daysUntilExpiry = now()->diffInDays($graph->date_expiration, false);
                                        @endphp

                                        @if($daysUntilExpiry < 10)
                                        <!-- Mise en évidence en rouge si moins de 10 jours avant expiration -->
                                        <span class="text-danger">{{ $graph->date_expiration->format('d/m/Y') }}</span>
                                        <small class="d-block text-danger">({{ $daysUntilExpiry }} {{ __('graphs.jours') }})</small>
                                        @else
                                        <!-- Affichage normal si plus de 10 jours avant expiration -->
                                        {{ $graph->date_expiration->format('d/m/Y') }}
                                        @endif
                                    </td>
                                    <!-- Colonne des actions possibles sur le graphique -->
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center">
                                            <!-- Bouton pour visualiser le graphique -->
                                            <a href="{{ route('graphs.show', $graph->id) }}" class="action-btn view-btn me-2">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <!-- Bouton pour modifier le graphique -->
                                            <a href="{{ route('graphs.edit', $graph->id) }}" class="action-btn edit-btn me-2">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <!-- Formulaire pour supprimer le graphique avec confirmation -->
                                            <form action="{{ route('graphs.delete', $graph->id) }}" method="POST" class="d-inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <!-- Bouton de suppression avec boîte de dialogue de confirmation -->
                                                <button type="submit" class="action-btn delete-btn" onclick="return confirm('{{ __('graphs.confirmation_suppression') }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection