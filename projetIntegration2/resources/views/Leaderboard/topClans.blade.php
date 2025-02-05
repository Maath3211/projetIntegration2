@extends('Layouts.app')
@section('titre', 'Clans')

@section('style')
<link rel="stylesheet" style="text/css" href="{{asset('css/Leaderboard.css')}}">
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">

@endsection()

@section('contenu')

<div class="contenuPrincipal">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 colonneCanaux">
                <div class="container">
                    <div class="column">
                        <div class="conteneurImage">
                            <div class="texteSurImage">Leaderboards</div>
                            <div>
                                <a href="#">
                                    <i class="fa-solid fa-ellipsis"></i>
                                </a>
                            </div>
                        </div>
                        <div class="conteneurCanaux">
                            <div class="categorieCanal">
                                <div class="titreCategorieCanal">
                                    <div>
                                        <i class="fa-solid fa-minus"></i>
                                        Général
                                    </div>
                                    <a href="#"><i class="fa-solid fa-plus fa-xs"></i></a>
                                </div>

                                <div class="canal active">
                                    <a href="#">
                                        <div>
                                            <i class="fa-solid fa-hashtag"></i>
                                            global
                                        </div>
                                    </a>
                                    <div class="iconesModificationCanal">
                                        <a href="#"><i class="fa-solid fa-pen "></i></a>
                                        <a href="#"><i class="fa-solid fa-x"></i></a>
                                    </div>
                                </div>
                                <div class="canal">
                                    <a href="#">
                                        <div>
                                            <i class="fa-solid fa-hashtag"></i>
                                            groupe1
                                        </div>
                                    </a>
                                    <div class="iconesModificationCanal">
                                        <a href="#"><i class="fa-solid fa-pen "></i></a>
                                        <a href="#"><i class="fa-solid fa-x"></i></a>
                                    </div>
                                </div>
                                <div class="canal">
                                    <a href="#">
                                        <div>
                                            <i class="fa-solid fa-hashtag"></i>
                                            groupe2
                                        </div>
                                    </a>
                                    <div class="iconesModificationCanal">
                                        <a href="#"><i class="fa-solid fa-pen "></i></a>
                                        <a href="#"><i class="fa-solid fa-x"></i></a>
                                    </div>
                                </div>
                                <div class="canal">
                                    <a href="#">
                                        <div>
                                            <i class="fa-solid fa-hashtag"></i>
                                            groupe3
                                        </div>
                                    </a>
                                    <div class="iconesModificationCanal">
                                        <a href="#"><i class="fa-solid fa-pen "></i></a>
                                        <a href="#"><i class="fa-solid fa-x"></i></a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8 colonneLeaderboard">
                <div id="topClansContainer">
                    <!-- Leaderboard Header Box -->
                    <div class="leaderboard-header p-3 mb-3 border rounded d-flex justify-content-between align-items-center">
                        <div>
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('images/ui/trophy.png') }}" alt="Trophy" style="width:30px; height:30px;" class="mr-2">
                                <h2 class="mb-0">Top 10 Meilleurs Groupes</h2>
                            </div>
                            <p class="text-muted mb-0">Découvrez les clans les plus performants et inspirants du moment</p>
                        </div>
                        <div class="dropdown ml-2">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="exportDropdown" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="fa-solid fa-share-from-square fa-2x"></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="exportDropdown">
                                <a class="dropdown-item" href="{{ route('export.topClans') }}">Export CSV</a>
                                <a class="dropdown-item" href="#" id="exportClansImageBtn">Export Image</a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Column for positions 1 to 5 -->
                        <div class="col-md-6">
                            @foreach ($topClans->take(5) as $index => $clan)
                            <div class="clan-row d-flex align-items-center justify-content-between mb-2 py-2 px-3 border-bottom">
                                <div class="d-flex align-items-center">
                                    <div class="position mr-3">{{ $index + 1 }}</div>
                                    <img src="{{ asset('images/clans/' . $clan->clan_image) }}" alt="Clan Image" class="rounded-circle" style="width:40px; height:40px;">
                                    <span class="clan-nom ml-3">{{ $clan->clan_nom }}</span>
                                </div>
                                <span class="score">{{ $clan->clan_total_score }} points</span>
                            </div>
                            @endforeach
                        </div>
                        <!-- Column for positions 6 to 10 -->
                        <div class="col-md-6">
                            @foreach ($topClans->slice(5, 5) as $index => $clan)
                            <div class="clan-row d-flex align-items-center justify-content-between mb-2 py-2 px-3 border-bottom">
                                <div class="d-flex align-items-center">
                                    <div class="position mr-3">{{ $index + 6 }}</div>
                                    <img src="{{ asset('images/clans/' . $clan->clan_image) }}" alt="Clan Image" class="rounded-circle" style="width:40px; height:40px;">
                                    <span class="clan-nom ml-3">{{ $clan->clan_nom }}</span>
                                </div>
                                <span class="score">{{ $clan->clan_total_score }} points</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div id="topUsersContainer">
                    <div class="leaderboard-header p-3 mb-3 border rounded d-flex justify-content-between align-items-center">
                        <div>
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('images/ui/trophy.png') }}" alt="Trophy" style="width:30px; height:30px;" class="mr-2">
                                <h2 class="mb-0">Top 10 Meilleurs Utilisateurs</h2>
                            </div>
                            <p class="text-muted mb-0">Découvrez les utilisateurs les plus performants et inspirants du moment</p>
                        </div>
                        <div class="dropdown ml-2">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="exportDropdownUsers" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa-solid fa-share-from-square fa-2x"></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="exportDropdownUsers">
                                <a class="dropdown-item" href="{{ route('export.topUsers') }}">Exporter CSV</a>
                                <a class="dropdown-item" href="#" id="exportUsersImageBtn">Exporter Image</a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Column for positions 1 to 5 -->
                        <div class="col-md-6">
                            @foreach ($topUsers->take(5) as $index => $user)
                            <div class="clan-row d-flex align-items-center justify-content-between mb-2 py-2 px-3 border-bottom">
                                <div class="d-flex align-items-center">
                                    <div class="position mr-3">{{ $index + 1 }}</div>
                                    <img src="{{ asset($user->imageProfil) }}" alt="User Image" class="rounded-circle" style="width:40px; height:40px;">
                                    <span class="clan-nom ml-3">{{ $user->prenom }} {{ $user->nom }}</span>
                                </div>
                                <span class="score">{{ $user->total_score }} points</span>
                            </div>
                            @endforeach
                        </div>
                        <!-- Column for positions 6 to 10 -->
                        <div class="col-md-6">
                            @foreach ($topUsers->slice(5, 5) as $index => $user)
                            <div class="clan-row d-flex align-items-center justify-content-between mb-2 py-2 px-3 border-bottom">
                                <div class="d-flex align-items-center">
                                    <div class="position mr-3">{{ $index + 6 }}</div>
                                    <img src="{{ asset($user->imageProfil) }}" alt="User Image" class="rounded-circle" style="width:40px; height:40px;">
                                    <span class="clan-nom ml-3">{{ $user->prenom }} {{ $user->nom }}</span>
                                </div>
                                <span class="score">{{ $user->total_score }} points</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
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
                            <img src="{{asset('img/Utilisateur/utilisateur4.jpg')}}">
                            <div>
                                Julie St-Aubin
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="#">
                            <img src="{{asset('img/Utilisateur/utilisateur5.avif')}}">
                            <div>
                                Gnulons
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="#">
                            <img src="{{asset('img/Utilisateur/utilisateur6.jpg')}}">
                            <div>
                                Jack Jacked
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="#">
                            <img src="{{asset('img/Utilisateur/utilisateur7.jpg')}}">
                            <div>
                                Sophie
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="#">
                            <img src="{{asset('img/Utilisateur/utilisateur8.jpg')}}">
                            <div>
                                Lucia Percada
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="#">
                            <img src="{{asset('img/Utilisateur/utilisateur9.jpg')}}">
                            <div>
                                Stevie
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="#">
                            <img src="{{asset('img/Utilisateur/utilisateur11.jpg')}}">
                            <div>
                                Tom
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="#">
                            <img src="{{asset('img/Utilisateur/utilisateur12.jpg')}}">
                            <div>
                                Bluestack
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="#">
                            <img src="{{asset('img/Utilisateur/utilisateur13.jpg')}}">
                            <div>
                                CoolCarl123
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="#">
                            <img src="{{asset('img/Utilisateur/utilisateur14.webp')}}">
                            <div>
                                Sylvain
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
                    <div class="membre">
                        <a href="#">
                            <img src="{{asset('img/Utilisateur/utilisateur19.jpg')}}">
                            <div>
                                CalisthenicGod_1
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="#">
                            <img src="{{asset('img/Utilisateur/utilisateur20.jpg')}}">
                            <div>
                                Gymcord#654302
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="#">
                            <img src="{{asset('img/Utilisateur/utilisateur4.jpg')}}">
                            <div>
                                Julia Julia
                            </div>
                        </a>
                    </div>
                    <div class="membre">
                        <a href="#">
                            <img src="{{asset('img/Utilisateur/utilisateur2.jpg')}}">
                            <div>
                                Dieu Poulet
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
    // JavaScript qui soit s'exécuter une fois la page chargée
    document.addEventListener("DOMContentLoaded", function() {

        const canals = document.querySelectorAll('.canal');
        const divScrollable = document.querySelector(".contenuScrollable");

        // Pour montrer quel canal est actif actuellement
        canals.forEach(canal => {
            canal.addEventListener('click', function() {
                canals.forEach(c => c.classList.remove('active'));
                canal.classList.add('active');

                // Quand on change de canal de chat, on scroll jusqu'en bas automatiquement
                divScrollable.scrollTop = divScrollable.scrollHeight;
            });
        });

        // Déroulement automatique jusqu'en bas des messages
        divScrollable.scrollTop = divScrollable.scrollHeight;
    });

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
@endsection()