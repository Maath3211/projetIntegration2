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
    width: 400px; /* Ajustez la largeur de la barre de recherche */
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
.result-item > * {
    margin-right: 10px;
}
.result-item form {
    margin: 0;
}
.result-list {
    list-style: none;
    padding: 0;
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

.profile-trigger > * {
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

/* Style appliqué exclusivement aux boutons ciblés */
.back-button, .unit-btn {
    background: linear-gradient(135deg, #a9fe77, #60c22c);
    border: none;
    padding: 12px 25px;
    cursor: pointer;
    font-size: 16px; /* Text réduit */
    color: black;
    font-weight: bold;
    border-radius: 8px;
    transition: all 0.3s ease-in-out;
    box-shadow: 2px 2px 10px rgba(169, 254, 119, 0.5);
    text-transform: uppercase;
    margin-right: 10px; /* Espacement entre les boutons */
}

.back-button:hover, .unit-btn:hover {
    background: linear-gradient(135deg, #60c22c, #a9fe77);
    transform: scale(1.05);
}

.back-button:active, .unit-btn:active {
    transform: scale(0.95);
    box-shadow: 1px 1px 5px rgba(169, 254, 119, 0.8);
}

/* Classe spécifique pour réduire la taille du texte du bouton "ajouter" */
.ajouter-btn {
    font-size: 14px; /* Texte réduit pour le bouton "ajouter" */
}
    </style>

    <!-- Ajoutez un margin-top sur le conteneur des deux boutons -->
    <div class="top-buttons" style="margin-top: 20px; margin-bottom: 20px;">
        <!-- Bouton "Recherche d'amis" -->
        <a href="{{ route('amis.index') }}" class="back-button" style="text-decoration: none;">
            {{ __('friends.recherche_amis') }}
        </a>
        <!-- Bouton "Liste de demandes d'amis" -->
        <a href="{{ route('amis.demandes') }}" class="unit-btn" style="text-decoration: none;">
            {{ __('friends.liste_demandes_amis') }}
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
            <!-- Bouton "Rechercher" modifié pour utiliser le style "back-button" -->
            <button type="submit" class="back-button">{{ __('friends.bouton_recherche') }}</button>
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
                                <img src="{{ asset('images/avatars/' . $utilisateur->avatar) }}" alt="{{ __('friends.avatar') }}" class="avatar" style="width:40px;height:40px;border-radius:50%;">
                            @else
                                <img src="{{ asset('images/avatars/default-avatar.jpg') }}" alt="{{ __('friends.avatar_defaut') }}" class="avatar" style="width:40px;height:40px;border-radius:50%;">
                            @endif
                            <span class="email">{{ $utilisateur->email }}</span>
                        </div>
                        
                        <!-- Formulaire d'envoi de demande d'ami -->
                        <form action="{{ route('amis.ajouter') }}" method="POST" onClick="event.stopPropagation();">
                            @csrf
                            <!-- Ici, "user_id" serait l'expéditeur de la demande et "friend_id" le destinataire -->
                            <input type="hidden" name="user_id" value="{{ $utilisateurConnecteId ?? 0 }}">
                            <input type="hidden" name="friend_id" value="{{ $utilisateur->id }}">
                            <button type="submit" class="back-button ajouter-btn">{{ __('friends.ajouter_amis') }}</button>
                        </form>
                    </li>

                    <!-- Modal -->
                    <div class="modal fade" id="profileModal{{ $utilisateur->id }}" tabindex="-1" role="dialog" aria-labelledby="profileModalLabel{{ $utilisateur->id }}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="profileModalLabel{{ $utilisateur->id }}">Profil de {{ $utilisateur->email }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    @if($utilisateur->avatar)
                                        <img src="{{ asset('images/avatars/' . $utilisateur->avatar) }}" alt="{{ __('friends.avatar') }}" class="profile-avatar">
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
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.search-input').forEach(function(input) {
        input.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                this.form.submit();
            }
        });
    });
    // Pour chaque zone cliquable, attacher un click qui ouvre le modal correspondant
    document.querySelectorAll('.profile-trigger').forEach(function(trigger) {
        trigger.addEventListener('click', function() {
            var modalId = this.closest('.result-item').getAttribute('data-target');
            $(modalId).modal('show');
        });
    });
});
</script>