@extends('layouts.app')

@section('contenu')
    <style>
        body {
            background-color: #333;
            /* Gris foncé */
            color: white;
            /* Texte blanc */
            scrollbar-width: none;
            /* Firefox */
            -ms-overflow-style: none;
            /* IE 10+ */
        }

        body::-webkit-scrollbar {
            display: none;
            /* Chrome, Safari, Opera */
        }

        .result-list {
            list-style-type: none;
            padding: 0;
            border: 1px solid #555;
            /* Contour gris foncé */
            border-radius: 5px;
            margin-top: 20px;
            padding: 10px;
            background-color: #444;
            /* Gris foncé pour le fond */
        }

        .result-item {
            background-color: #333;
            /* Fond gris foncé pour les resultats */
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

        .profile-button,
        .add-button {
            background-color: #555;
            /* Gris foncé pour les boutons */
            color: white;
            border: none;
            padding: 5px 10px;
            margin-left: 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .profile-button:hover,
        .add-button:hover {
            background-color: #666;
            /* Gris plus clair au survol */
        }

        .modal-content {
            background-color: #444;
            /* Gris foncé pour le modal */
            color: white;
        }

        .modal-header,
        .modal-footer {
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
            width: 400px;
            /* Ajustez la largeur de la barre de recherche */
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

        .result-item>* {
            margin-right: 10px;
        }

        .result-item form {
            margin: 0;
        }

        .result-list {
            list-style: none;
            padding: 0;
            overflow: auto;
            max-height: 80vh;
            scrollbar-width: thin;
            /* Firefox */
        }

        .result-list::-webkit-scrollbar {
            width: 5px;
            /* Chrome, Safari */
            height: 5px;
        }

        .result-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }

        /* Style de la zone cliquable pour afficher le profil */
        .profile-trigger {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .profile-trigger>* {
            margin-right: 10px;
        }

        /* Bouton Ajouter en vert et style ajusté */
        .add-button {
            background-color: #a9fe77;
            color: #000;
            border: 1px solid #999;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .add-button:hover {
            background-color: #98e96a;
        }
    </style>

    <div class="top-buttons" style="margin-bottom: 20px;">
<<<<<<< Updated upstream
        <a href="{{ route('amis.index') }}" class="search-button" style="text-decoration: none; padding: 10px 20px; background-color: #a9fe77; color: #000; border: 1px solid #999; border-radius: 5px; margin-right: 10px;">
            {{ __('friends.recherche_amis') }}
        </a>
        <a href="{{ route('amis.demandes') }}" class="add-button" style="text-decoration: none; padding: 10px 20px; background-color: #a9fe77; color: #000; border: 1px solid #999; border-radius: 5px;">
            {{ __('friends.liste_demandes_amis') }}
=======
        <a href="{{ route('amis.index') }}" class="search-button"
            style="text-decoration: none; padding: 10px 20px; background-color: #a9fe77; color: #000; border: 1px solid #999; border-radius: 5px; margin-right: 10px;">
            Rechercher des amis
        </a>
        <a href="{{ route('amis.demandes') }}" class="add-button"
            style="text-decoration: none; padding: 10px 20px; background-color: #a9fe77; color: #000; border: 1px solid #999; border-radius: 5px;">
            Liste des demandes d'amis
>>>>>>> Stashed changes
        </a>
    </div>

    <!-- Affichage des messages -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="search-bar">
        <form action="{{ route('amis.recherche') }}" method="POST">
            @csrf
            <input type="text" name="q" placeholder="{{ __('friends.recherche_exemple') }}" required class="search-input">
            <button type="submit" class="search-button">{{ __('friends.bouton_recherche') }}</button>
        </form>
    </div>

    @isset($utilisateurs)
        @if($utilisateurs->isEmpty())
            <p>{{ __('friends.aucun_utilisateurs_trouves') }}</p>
        @else
            <h2>{{ __('friends.resultats_recherche') }}</h2>
            <ul class="result-list">
                @foreach($utilisateurs as $utilisateur)
                    <li class="result-item" data-target="#profileModal{{ $utilisateur->id }}">
                        <div class="profile-trigger">
                            @if($utilisateur->avatar)
<<<<<<< Updated upstream
                                <img src="{{ asset('images/avatars/' . $utilisateur->avatar) }}" alt="{{ __('friends.avatar') }}" class="avatar" style="width:40px;height:40px;border-radius:50%;">
                            @else
                                <img src="{{ asset('images/avatars/default-avatar.jpg') }}" alt="{{ __('friends.avatar_defaut') }}" class="avatar" style="width:40px;height:40px;border-radius:50%;">
=======
                                <img src="{{ asset('images/avatars/' . $utilisateur->avatar) }}" alt="Avatar" class="avatar"
                                    style="width:40px;height:40px;border-radius:50%;">
                            @else
                                <img src="{{ asset('images/avatars/default-avatar.jpg') }}" alt="Avatar" class="avatar"
                                    style="width:40px;height:40px;border-radius:50%;">
>>>>>>> Stashed changes
                            @endif
                            <span class="username">{{ $utilisateur->prenom }} {{ $utilisateur->nom }}</span>
                        </div>
<<<<<<< Updated upstream
                        
                        <!-- Formulaire d'envoi de demande d'ami -->
                        <form action="{{ route('amis.ajouter') }}" method="POST" onClick="event.stopPropagation();">
                            @csrf
                            <!-- Ici, "user_id" serait l'expéditeur de la demande et "friend_id" le destinataire -->
                            <input type="hidden" name="user_id" value="{{ $utilisateurConnecteId ?? 0 }}">
                            <input type="hidden" name="friend_id" value="{{ $utilisateur->id }}">
                            <button type="submit" class="add-button">{{ __('friends.ajouter_amis') }}</button>
                        </form>
=======

                        @if(in_array($utilisateur->id, $sentRequests))
                            <button type="button" class="sent-button" disabled>Demande d'ami envoyé</button>
                        @else
                            <form action="{{ route('amis.ajouter') }}" method="POST" onClick="event.stopPropagation();">
                                @csrf
                                <input type="hidden" name="friend_id" value="{{ $utilisateur->id }}">
                                <button type="submit" class="add-button">Ajouter</button>
                            </form>
                        @endif
>>>>>>> Stashed changes
                    </li>

                    <!-- Modal -->
                    <div class="modal fade" id="profileModal{{ $utilisateur->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="profileModalLabel{{ $utilisateur->id }}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
<<<<<<< Updated upstream
                                    <h5 class="modal-title" id="profileModalLabel{{ $utilisateur->id }}">{{ __('friends.profil_de', ['name' => $utilisateur->username]) }}</h5>
=======
                                    <h5 class="modal-title" id="profileModalLabel{{ $utilisateur->id }}">Profil de
                                        {{ $utilisateur->prenom }} {{ $utilisateur->nom }}
                                    </h5>
>>>>>>> Stashed changes
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    @if($utilisateur->avatar)
<<<<<<< Updated upstream
                                        <img src="{{ asset('images/avatars/' . $utilisateur->avatar) }}" alt="{{ __('friends.avatar') }}" class="profile-avatar">
=======
                                        <img src="{{ asset('images/avatars/' . $utilisateur->avatar) }}" alt="Avatar"
                                            class="profile-avatar">
>>>>>>> Stashed changes
                                    @else
                                        <img src="{{ asset('images/avatars/default-avatar.jpg') }}" alt="{{ __('friends.avatar_defaut') }}" class="profile-avatar">
                                    @endif
                                    <p><strong>{{ __('friends.nom_complet') }}</strong> {{ $utilisateur->name }}</p>
                                    <p><strong>{{ __('friends.email') }}</strong> {{ $utilisateur->email }}</p>
                                    <p><strong>{{ __('friends.membre_depuis') }}</strong> {{ $utilisateur->created_at->format('d M Y') }}</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('friends.fermer') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </ul>
        @endif
    @endisset
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.search-input').forEach(function (input) {
            input.addEventListener('keydown', function (event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    this.form.submit();
                }
            });
        });
        // Pour chaque zone cliquable, attacher un click qui ouvre le modal correspondant
        document.querySelectorAll('.profile-trigger').forEach(function (trigger) {
            trigger.addEventListener('click', function () {
                var modalId = this.closest('.result-item').getAttribute('data-target');
                $(modalId).modal('show');
            });
        });
    });
</script>