@extends('Layouts.app')
@section('titre', 'Leaderboards')

@section('style')
<link rel="stylesheet" style="text/css" href="{{asset('css/Leaderboard.css')}}">
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
@endsection

@section('contenu')

<div class="contenuPrincipal">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 colonneCanaux">
                <div class="container">
                    <div class="column">
                        <div class="conteneurImage">
                            <div class="texteSurImage">{{__('leaderboard.classements')}}</div>
                        </div>
                        <!-- Sidebar: Livewire Component -->
                        <livewire:sidebar-clans :userClans="$userClans" />
                    </div>
                </div>
            </div>

            <!-- Leaderboard Column -->
            <div class="col-md-8 colonneLeaderboard" style="overflow-y: auto; height: 100vh; scrollbar-width: thin; scrollbar-color: transparent transparent;">
                <div>
                    <livewire:leaderboard-switcher :topClans="$topClans" :topUsers="$topUsers" />
                </div>
            </div>


        </div>
    </div>
</div>
</div>

<style>
    .colonneLeaderboard::-webkit-scrollbar {
        width: 8px;
    }

    .colonneLeaderboard::-webkit-scrollbar-thumb {
        background-color: transparent;
    }

    .colonneLeaderboard:hover::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 0.5);
    }
</style>

@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="{{ asset('js/standalone-export.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const script = document.createElement('script');
        script.src = "{{ asset('js/leaderboards/leaderboard.js') }}";
        script.onload = function() {
            console.log('Leaderboard script loaded');
            if (typeof initLeaderboard === 'function') {
                initLeaderboard();
            }
        };
        document.body.appendChild(script);
    });
</script>
@endsection