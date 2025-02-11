@extends('Layouts.app')
@section('titre', 'Clans')

@section('style')
<link rel="stylesheet" style="text/css" href="{{asset('css/Leaderboard.css')}}">
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
<style>
    .conteneurImage {
        background-image: url('{{ asset('images/ui/leaderboard.png') }}');
        background-size: cover;
        background-position: center center;
        width: 100%;
        height: 150px;
        opacity: 0.5;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        padding: 15px;
        border-bottom: 2px solid rgba(255, 255, 255, 1);
    }
</style>
@endsection

@section('contenu')

<div class="contenuPrincipal">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 colonneCanaux">
                <div class="container">
                    <div class="column">
                        <div class="conteneurImage">
                            <div class="texteSurImage">Leaderboards</div>
                        </div>
                        <!-- Sidebar: Livewire Component -->
                        <livewire:sidebar-clans :userClans="$userClans" />
                    </div>
                </div>
            </div>

            <!-- Leaderboard Column -->
            <div class="col-md-8 colonneLeaderboard">
                <div id="topClansContainer">
                    <livewire:leaderboard-switcher :topClans="$topClans" :topUsers="$topUsers" />
                </div>
            </div>

            <div class="col-md-2 colonneMembres">
                <div class="contenuScrollableMembres">
                    <div class="membre">
                        <a href="#">
                            <img src="{{asset('img/Utilisateur/utilisateur1.jpg')}}">
                            <div>
                                <strong>ADMIN</strong> - Tommy Jackson
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="#">
                            <img src="{{asset('img/Utilisateur/utilisateur2.jpg')}}">
                            <div>
                                AverageGymGoer
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="#">
                            <img src="{{asset('img/Utilisateur/utilisateur3.jpg')}}">
                            <div>
                                NotTheAverageGuy
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="#">
                            <img src="{{asset('img/Utilisateur/utilisateur15.jpg')}}">
                            <div>
                                Ghost
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="#">
                            <img src="{{asset('img/Utilisateur/utilisateur16.jpg')}}">
                            <div>
                                Coach Noah
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="#">
                            <img src="{{asset('img/Utilisateur/utilisateur17.jpg')}}">
                            <div>
                                MotivationGuy
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="#">
                            <img src="{{asset('img/Utilisateur/utilisateur18.jpg')}}">
                            <div>
                                xXDarkSlayerXx
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
    // Export container image function
    function exportContainerImage(containerId, filename) {
        var container = document.getElementById(containerId);
        // Get current computed background color of the container
        var computedStyle = window.getComputedStyle(container);
        var bgColor = computedStyle.backgroundColor;

        // Hide any dropdown elements inside the container
        var dropdowns = container.querySelectorAll('.dropdown');
        dropdowns.forEach(dropdown => dropdown.style.display = 'none');

        html2canvas(container, {
            backgroundColor: bgColor // Retain the container's background color
        }).then(function(canvas) {
            // Restore the dropdown display after capture
            dropdowns.forEach(dropdown => dropdown.style.display = '');

            var link = document.createElement('a');
            link.download = filename;
            link.href = canvas.toDataURL("image/png");
            link.click();
        });
    }

    document.getElementById('exportClansImageBtn').addEventListener('click', function(e) {
        e.preventDefault();
        exportContainerImage('topClansContainer', 'topClans.png');
    });

    document.getElementById('exportUsersImageBtn').addEventListener('click', function(e) {
        e.preventDefault();
        exportContainerImage('topUsersContainer', 'topUsers.png');
    });
</script>

<script>
document.addEventListener('livewire:load', function() {
    console.log('Livewire loaded');

    // Immediate test event (fires once after Livewire is loaded)
    console.log('Emitting test event: clanSelected "test"');
    window.livewire.emit('clanSelected', 'test');

    const canalElements = document.querySelectorAll('.canal[data-clan]');
    if (canalElements.length === 0) {
        console.warn('No sidebar elements with data-clan were found.');
    } else {
        console.log('Found', canalElements.length, 'sidebar items');
    }

    canalElements.forEach(function(element) {
        element.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const clanId = element.getAttribute('data-clan');
            console.log('Clicked sidebar item with clanId:', clanId);
            window.livewire.emit('clanSelected', clanId);
        });
    });
});
</script>
@endsection