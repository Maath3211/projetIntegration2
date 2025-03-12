@extends('layouts.app')
@section('titre', __('graphs.titre_graphiques'))
@section('style')
<link rel="stylesheet" style="text/css" href="{{asset('css/graphs/graphs.css')}}">
@endsection()

@section('contenu')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h1 class="mb-0 h3 text-white">{{ __('graphs.titre_graphiques') }}</h1>
                    <a href="{{ route('graphs.create') }}" class="btn bouton">
                        <i class="fas fa-plus-circle me-2"></i>{{ __('graphs.nouveau_graphique') }}
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    @if($graphs->isEmpty())
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-chart-line fa-4x text-muted"></i>
                        </div>
                        <h3 class="h5 mb-3">{{ __('graphs.graphiques_vides') }}</h3>
                        <p class="text-muted mb-4">{{ __('graphs.creer_premier') }}</p>
                        <a href="{{ route('graphs.create') }}" class="btn bouton">
                            <i class="fas fa-plus-circle me-2"></i>{{ __('graphs.creer_graphique') }}
                        </a>
                    </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('graphs.titre') }}</th>
                                    <th>{{ __('graphs.type') }}</th>
                                    <th>{{ __('graphs.periode') }}</th>
                                    <th>{{ __('graphs.cree_le') }}</th>
                                    <th>{{ __('graphs.expire_le') }}</th>
                                    <th class="text-center">{{ __('graphs.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($graphs as $graph)
                                <tr class="text-white">
                                    <td class="fw-medium">{{ $graph->titre }}</td>
                                    <td>
                                        @if($graph->type == 'global')
                                        <span class="badge bg-primary">{{ __('graphs.global') }}</span>
                                        @elseif($graph->clan)
                                        <span class="badge" style="background-color: #a9fe77;">{{ __('graphs.clan') }}: {{ $graph->clan->nom }}</span>
                                        @else
                                        <span class="badge bg-secondary">{{ __('graphs.inconnu') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-nowrap">{{ $graph->date_debut->format('d/m/Y') }}</span>
                                        <i class="fas fa-arrow-right mx-1 small"></i>
                                        <span class="text-nowrap">{{ $graph->date_fin->format('d/m/Y') }}</span>
                                    </td>
                                    <td>{{ $graph->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        @php
                                        $daysUntilExpiry = now()->diffInDays($graph->date_expiration, false);
                                        @endphp

                                        @if($daysUntilExpiry < 10)
                                        <span class="text-danger">{{ $graph->date_expiration->format('d/m/Y') }}</span>
                                        <small class="d-block text-danger">({{ $daysUntilExpiry }} {{ __('graphs.jours') }})</small>
                                        @else
                                        {{ $graph->date_expiration->format('d/m/Y') }}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center">
                                            <a href="{{ route('graphs.show', $graph->id) }}" class="action-btn view-btn me-2">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('graphs.edit', $graph->id) }}" class="action-btn edit-btn me-2">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('graphs.delete', $graph->id) }}" method="POST" class="d-inline-block">
                                                @csrf
                                                @method('DELETE')
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