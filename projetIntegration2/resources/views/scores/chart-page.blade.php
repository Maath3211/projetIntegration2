@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h4 class="mb-0">Évolution des scores sur 6 mois</h4>
          <a href="{{ route('scores.meilleursGroupes') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour au classement
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
  
    const chartData = {
      labels: ['Jan 2023', 'Feb 2023', 'Mar 2023', 'Apr 2023', 'May 2023', 'Jun 2023'],
      datasets: [
        {
          label: 'Score des Clans',
          data: [1200, 1350, 1500, 1450, 1600, 1750],
          borderColor: '#4CAF50',
          backgroundColor: 'rgba(76, 175, 80, 0.1)',
          tension: 0.4,
          fill: true
        },
        {
          label: 'Score des Utilisateurs',
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
            ticks: { color: '#666' }
          },
          x: {
            grid: {
              color: 'rgba(76, 175, 80, 0.1)'
            },
            ticks: { color: '#666' }
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