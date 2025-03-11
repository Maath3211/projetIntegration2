@extends('layouts.app')
@section('titre', $graph->titre)
@section('style')
<link rel="stylesheet" style="text/css" href="{{asset('css/graphs/graphs.css')}}">
@endsection

@section('contenu')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h1 class="h3 mb-0 text-white">{{ $graph->titre }}</h1>
                    <div>
                        <a href="{{ route('graphs.index') }}" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left me-1"></i> {{ __('graphs.retour') }}
                        </a>
                        <a href="{{ route('graphs.create') }}" class="btn bouton">
                            <i class="fas fa-plus-circle me-1"></i> {{ __('graphs.nouveau_graphique') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body p-3">
                                    <h5 class="card-title">{{ __('graphs.informations') }}</h5>
                                    <table class="table table-sm table-borderless mb-0">
                                        <tr>
                                            <td class="fw-bold text-white">{{ __('graphs.type') }}:</td>
                                            <td class="text-white">
                                                @if($graph->type == 'global')
                                                {{ __('graphs.pointages_global') }}
                                                @elseif($graph->clan)
                                                {{ __('graphs.pointages_clan') }}: {{ $graph->clan->nom }}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-white">{{ __('graphs.periode_label') }}</td>
                                            <td class="text-white">{{ $graph->date_debut->format('d/m/Y') }} - {{ $graph->date_fin->format('d/m/Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-white">{{ __('graphs.cree_le') }}:</td>
                                            <td class="text-white">{{ $graph->created_at->format('d/m/Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-white">{{ __('graphs.expire_le') }}:</td>
                                            <td class="text-white">{{ $graph->date_expiration->format('d/m/Y') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="chart-container" style="position: relative; min-height: 400px;">
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
        const data = @json($graph -> data);

        // Add translations for months
        const translations = {
            clanScores: "{{ __('charts.pointages_clan') }}",
            userScores: "{{ __('charts.pointages_utilisateurs') }}",
            score: "{{ __('charts.score') }}",
            months: "{{ __('charts.months') }}",
            jan: "{{ __('charts.jan') }}",
            feb: "{{ __('charts.fev') }}",
            mar: "{{ __('charts.mar') }}",
            apr: "{{ __('charts.avr') }}",
            may: "{{ __('charts.mai') }}",
            jun: "{{ __('charts.juin') }}",
            jul: "{{ __('charts.jul') }}",
            aug: "{{ __('charts.aug') }}",
            sep: "{{ __('charts.sep') }}",
            oct: "{{ __('charts.oct') }}",
            nov: "{{ __('charts.nov') }}",
            dec: "{{ __('charts.dec') }}"
        };

        // Translate month labels if they are in Month Year format (e.g., "Jan 2025")
        if (data.interval === 'monthly') {
            data.labels = data.labels.map(label => {
                const parts = label.split(' ');
                if (parts.length === 2) {
                    const monthAbbr = parts[0].toLowerCase();
                    const year = parts[1];

                    // Get translation key based on month abbreviation
                    let translationKey;
                    switch(monthAbbr) {
                        case 'jan': translationKey = 'jan'; break;
                        case 'feb': translationKey = 'feb'; break;
                        case 'mar': translationKey = 'mar'; break;
                        case 'apr': translationKey = 'apr'; break;
                        case 'may': translationKey = 'may'; break;
                        case 'jun': translationKey = 'jun'; break;
                        case 'jul': translationKey = 'jul'; break;
                        case 'aug': translationKey = 'aug'; break;
                        case 'sep': translationKey = 'sep'; break;
                        case 'oct': translationKey = 'oct'; break;
                        case 'nov': translationKey = 'nov'; break;
                        case 'dec': translationKey = 'dec'; break;
                        default: translationKey = monthAbbr;
                    }

                    return translations[translationKey] + ' ' + year;
                }
                return label;
            });
        }

        // Get proper color scheme to match ScoreGraph
        const chartColor = '{{ $graph->type == "global" ? "#2196F3" : "#4CAF50" }}';
        const bgColor = '{{ $graph->type == "global" ? "rgba(33, 150, 243, 0.1)" : "rgba(76, 175, 80, 0.1)" }}';

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [{
                    label: '{{ $graph->type == "global" ? __("graphs.pointages_global") : __("graphs.pointages_clan") }}',
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
                            text: '{{ __("graphs.points") }}',
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
                            text: data.interval === 'daily' ? '{{ __("graphs.jours_label") }}' : '{{ __("graphs.mois_label") }}',
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