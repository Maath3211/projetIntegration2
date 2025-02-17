@extends('layouts.app')

@section('contenu')
    <style>
        body {
            background-color: #333; /* Gris foncé */
            color: white; /* Texte blanc */
        }
        .result-list {
            list-style-type: none;
            padding: 0;
            border: 1px solid #555; /* Contour gris foncé */
            border-radius: 5px;
            margin-top: 20px;
            padding: 10px;
            background-color: #444; /* Gris foncé pour le fond */
        }
        .result-item {
            background-color: #333; /* Fond gris foncé pour les resultats */
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
        }
        .username {
            margin-left: 10px;
        }
        .profile-button, .add-button {
            background-color: #555; /* Gris foncé pour les boutons */
            color: white;
            border: none;
            padding: 5px 10px;
            margin-left: 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .profile-button:hover, .add-button:hover {
            background-color: #666; /* Gris plus clair au survol */
        }
        .modal-content {
            background-color: #444; /* Gris foncé pour le modal */
            color: white;
        }
        .modal-header, .modal-footer {
            border: none;
        }
        .profile-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: block;
            margin: 0 auto 10px;
        }
        .search-bar {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        .search-input {
            padding: 10px;
            border: 1px solid #555;
            border-radius: 5px 0 0 5px;
            background-color: #444;
            color: white;
        }
        .search-button {
            padding: 10px 20px;
            border: 1px solid #555;
            border-radius: 0 5px 5px 0;
            background-color: #555;
            color: white;
            cursor: pointer;
        }
        .search-button:hover {
            background-color: #666;
        }
    </style>

    <h1>Rechercher des amis</h1>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <div class="search-bar">
        <form action="{{ route('amis.recherche') }}" method="POST">
            @csrf
            <input type="text" name="q" placeholder="Nom d'utilisateur" required class="search-input">
            <button type="submit" class="search-button">Rechercher</button>
        </form>
    </div>

    @isset($utilisateurs)
        @if($utilisateurs->isEmpty())
            <p>Aucun utilisateur trouvé.</p>
        @else
            <ul class="result-list">
            <h2>Résultats de recherche:</h2>
                @foreach($utilisateurs as $utilisateur)
                    <li class="result-item">
                        @if($utilisateur->avatar)
                            <img src="{{ asset('images/avatars/' . $utilisateur->avatar) }}" alt="Avatar" class="avatar">
                        @else
                            <img src="{{ asset('images/avatars/default-avatar.jpg') }}" alt="Avatar" class="avatar">
                        @endif
                        <span class="username">{{ $utilisateur->username }}</span>
                        <button type="button" class="profile-button" data-toggle="modal" data-target="#profileModal{{ $utilisateur->id }}">Voir Profil</button>
                        <form action="{{ route('amis.ajouter') }}" method="POST" style="display: inline;">
                            @csrf
                            <input type="hidden" name="utilisateur_id" value="{{ $utilisateur->id }}">
                            <button type="submit" class="add-button">Ajouter</button>
                        </form>
                    </li>

                    <!-- Modal -->
                    <div class="modal fade" id="profileModal{{ $utilisateur->id }}" tabindex="-1" role="dialog" aria-labelledby="profileModalLabel{{ $utilisateur->id }}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="profileModalLabel{{ $utilisateur->id }}">Profil de {{ $utilisateur->username }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    @if($utilisateur->avatar)
                                        <img src="{{ asset('images/avatars/' . $utilisateur->avatar) }}" alt="Avatar" class="profile-avatar">
                                    @else
                                        <img src="{{ asset('images/avatars/default-avatar.jpg') }}" alt="Avatar" class="profile-avatar">
                                    @endif
                                    <p><strong>Nom complet:</strong> {{ $utilisateur->name }}</p>
                                    <p><strong>Email:</strong> {{ $utilisateur->email }}</p>
                                    <p><strong>Membre depuis:</strong> {{ $utilisateur->created_at->format('d M Y') }}</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </ul>
        @endif
    @endisset
@endsection