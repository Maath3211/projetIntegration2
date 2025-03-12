@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h4 class="mb-0">{{ __('charts.evolution_point_periode') }}</h4>
          <a href="{{ route('scores.meilleursGroupes') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('charts.retour_classements') }}
          </a>
        </div>
        <div class="card-body">
          <div style="height: 400px;">
            <canvas id="scoreChart"></canvas>
          </div>
        </div>
      </div>
      <!-- Additional content -->
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
  // Pass translations to JavaScript
  const translations = {
      clanScores: "{{ __('charts.pointages_clan') }}",
      userScores: "{{ __('charts.pointages_utilisateurs') }}",
      score: "{{ __('charts.score') }}",
      months: "{{ __('charts.months') }}"
  };
  
  document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('scoreChart');
    if (!canvas) {
      console.error('Canvas element not found!');
      return;
    }
    const ctx = canvas.getContext('2d');
    if (!ctx) {
      console.error('Canvas context not found!');
      return;
    }
    
    // Translate month labels
    const months = [
      "{{ __('charts.jan') }} 2023", 
      "{{ __('charts.fev') }} 2023", 
      "{{ __('charts.mar') }} 2023",
      "{{ __('charts.avr') }} 2023", 
      "{{ __('charts.mai') }} 2023", 
      "{{ __('charts.juin') }} 2023"
    ];
  
    const chartData = {
      labels: months,
      datasets: [
        {
          label: translations.clanScores,
          data: [1200, 1350, 1500, 1450, 1600, 1750],
          borderColor: '#4CAF50',
          backgroundColor: 'rgba(76, 175, 80, 0.1)',
          tension: 0.4,
          fill: true
        },
        {
          label: translations.userScores,
          data: [800, 950, 1100, 1250, 1300, 1400],
          borderColor: '#2196F3',
          backgroundColor: 'rgba(33, 150, 243, 0.1)',
          tension: 0.4,
          fill: true
        }
      ]
    };
  
    new Chart(ctx, {
      type: 'line',
      data: chartData,
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true,
            grid: {
              color: 'rgba(76, 175, 80, 0.1)'
            },
            ticks: { color: '#666' },
            title: {
              display: true,
              text: translations.score
            }
          },
          x: {
            grid: {
              color: 'rgba(76, 175, 80, 0.1)'
            },
            ticks: { color: '#666' },
            title: {
              display: true,
              text: translations.months
            }
          }
        },
        plugins: {
          legend: { position: 'top' }
        }
      }
    });
    console.log('Chart initialized successfully');
  });
</script>
@endpush