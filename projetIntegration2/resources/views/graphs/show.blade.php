@extends('layouts.app')
@section('titre', $graph->titre)
@section('style')
<link rel="stylesheet" style="text/css" href="{{asset('css/graphs/graphs.css')}}">
@endsection()
@section('contenu')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h1 class="h3 mb-0 text-white">{{ $graph->titre }}</h1>
                    <div>
                        <a href="{{ route('graphs.index') }}" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left me-1"></i> Retour
                        </a>
                        <a href="{{ route('graphs.create') }}" class="btn bouton">
                            <i class="fas fa-plus-circle me-1"></i> Nouveau graphique
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body p-3">
                                    <h5 class="card-title">Informations</h5>
                                    <table class="table table-sm table-borderless mb-0">
                                        <tr>
                                            <td class="fw-bold text-white">Type:</td>
                                            <td class="text-white">
                                                @if($graph->type == 'global')
                                                Pointages Global
                                                @elseif($graph->clan)
                                                Pointages Clan: {{ $graph->clan->nom }}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-white">Période:</td>
                                            <td class="text-white">{{ $graph->date_debut->format('d/m/Y') }} au {{ $graph->date_fin->format('d/m/Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-white">Créé le:</td>
                                            <td class="text-white">{{ $graph->created_at->format('d/m/Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-white">Expire le:</td>
                                            <td class="text-white">{{ $graph->date_expiration->format('d/m/Y') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="chart-container" style="position: relative; height:400px; width:100%">
                        <canvas id="scoreChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('scoreChart').getContext('2d');
        const data = @json($graph->data);
        
        // Get proper color scheme to match ScoreGraph
        const chartColor = '{{ $graph->type == "global" ? "#2196F3" : "#4CAF50" }}';
        const bgColor = '{{ $graph->type == "global" ? "rgba(33, 150, 243, 0.1)" : "rgba(76, 175, 80, 0.1)" }}';
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [{
                    label: '{{ $graph->type == "global" ? "Scores Globaux" : "Scores Clan" }}',
                    data: data.values,
                    borderColor: chartColor,
                    backgroundColor: bgColor,
                    tension: 0.4,
                    fill: true,
                    borderWidth: 3,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: chartColor,
                    pointRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Points',
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
                        },
                        ticks: {
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: data.interval === 'daily' ? 'Jours' : 'Mois',
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
                        },
                        ticks: {
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: '{{ $graph->titre }}',
                        font: {
                            size: 18,
                            weight: 'bold'
                        },
                        padding: {
                            bottom: 20
                        }
                    },
                    legend: {
                        labels: {
                            font: {
                                size: 14
                            },
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleFont: {
                            size: 14
                        },
                        bodyFont: {
                            size: 13
                        },
                        padding: 10,
                        cornerRadius: 5,
                        displayColors: false
                    }
                }
            }
        });
    });
</script>
@endsection
@endsection