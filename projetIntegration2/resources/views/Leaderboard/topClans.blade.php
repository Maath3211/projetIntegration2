@extends('Layouts.app')
@section('titre', 'Clans')

@section('style')
<link rel="stylesheet" style="text/css" href="{{asset('css/Leaderboard.css')}}">
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
<style>
    .conteneurImage {
        background-image: url('{{ asset('img/ui/leaderboard.png') }}');
        /* Use 'contain' to ensure the whole image is visible */
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center center;
        width: 100%;
        /* Remove fixed height and use an aspect ratio (adjust the ratio as needed) */
        aspect-ratio: 16 / 9;
        /* Optional fallback for browsers that don't support aspect-ratio */
        min-height: 150px;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        padding: 15px;
        border-bottom: 2px solid rgba(255, 255, 255, 1);
        /* Adjust opacity if needed */
        opacity: 0.5;
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
                <div >
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

// Export container image function as before
function exportContainerImage(containerId, filename) {
    var container = document.getElementById(containerId);
    if (!container) {
        console.error("Container not found:", containerId);
        return;
    }
    var computedStyle = window.getComputedStyle(container);
    var bgColor = computedStyle.backgroundColor;
    console.log("Exporting container:", containerId, "with background:", bgColor);
    var dropdowns = container.querySelectorAll('.dropdown');
    dropdowns.forEach(function(dropdown) {
        dropdown.style.display = 'none';
    });
    html2canvas(container, { backgroundColor: bgColor })
        .then(function(canvas) {
            dropdowns.forEach(function(dropdown) {
                dropdown.style.display = '';
            });
            var link = document.createElement('a');
            link.download = filename;
            link.href = canvas.toDataURL("image/png");
            link.click();
            console.log("Download triggered for", filename);
        })
        .catch(function(error) {
            console.error("html2canvas error:", error);
        });
}

// Use event delegation—make sure each export button ID is unique and only triggers its own container.
document.body.addEventListener('click', function(e) {
    // Global Clans export (only targets topClansContainer)
    var clansBtn = e.target.closest('#exportClansImageBtn');
    if (clansBtn) {
        e.preventDefault();
        console.log("Clicked global clans export");
        exportContainerImage('topClansContainer', 'topClans.png');
        return; // exit to avoid accidental fall-through
    }
    // Global Users export (if applicable)
    var usersBtn = e.target.closest('#exportUsersImageBtn');
    if (usersBtn) {
        e.preventDefault();
        console.log("Clicked global users export");
        exportContainerImage('topUsersContainer', 'topUsers.png');
        return;
    }
    // Clan Membres export
    var membresBtn = e.target.closest('#exportTopMembresImage');
    if (membresBtn) {
        e.preventDefault();
        console.log("Clicked clan membres export");
        exportContainerImage('topMembresContainer', 'topMembres.png');
        return;
    }
    // Clan Amelioration export
    var ameliorationBtn = e.target.closest('#exportTopAmeliorationImage');
    if (ameliorationBtn) {
        e.preventDefault();
        console.log("Clicked clan amelioration export");
        exportContainerImage('topAmeliorationContainer', 'topAmelioration.png');
        return;
    }
});

// Optionally, log Livewire messages to confirm export listeners remain active after updates.
document.addEventListener('livewire:load', function () {
    Livewire.hook('message.processed', function(message, component) {
        console.log("Livewire message processed – export listeners remain active.");
    });
});
</script>
@endsection