@extends('layouts.app')

@section('style')
    <style>
        .back-button, .unit-btn {
            background: linear-gradient(135deg, #a9fe77, #60c22c);
            border: none;
            padding: 12px 25px;
            cursor: pointer;
            font-size: 18px;
            color: black;
            font-weight: bold;
            border-radius: 8px;
            transition: all 0.3s ease-in-out;
            box-shadow: 2px 2px 10px rgba(169, 254, 119, 0.5);
            text-transform: uppercase;
        }

        .back-button:hover, .unit-btn:hover {
            background: linear-gradient(135deg, #60c22c, #a9fe77);
            transform: scale(1.05);
        }

        .back-button:active, .unit-btn:active {
            transform: scale(0.95);
            box-shadow: 1px 1px 5px rgba(169, 254, 119, 0.8);
        }
        
        /* Bouton refuser avec couleur rouge pour le "X" */
        .reject-button {
            background: linear-gradient(135deg, #ff6b6b, #ff0000);
            border: none;
            padding: 12px 25px;
            cursor: pointer;
            font-size: 18px;
            color: black;
            font-weight: bold;
            border-radius: 8px;
            transition: all 0.3s ease-in-out;
            box-shadow: 2px 2px 10px rgba(255, 107, 107, 0.5);
            text-transform: uppercase;
        }

        .reject-button:hover {
            background: linear-gradient(135deg, #ff0000, #ff6b6b);
            transform: scale(1.05);
        }

        .reject-button:active {
            transform: scale(0.95);
            box-shadow: 1px 1px 5px rgba(255, 107, 107, 0.8);
        }
    </style>
@endsection

@section('contenu')
    <!-- Conteneur avec un espacement au-dessus pour éviter qu'il soit collé au bord -->
    <div class="top-buttons" style="margin-top: 20px; margin-bottom: 20px; display: flex; gap: 10px;">
        <a href="{{ route('amis.index') }}" class="back-button" style="text-decoration: none;">
            {{ __('friends.recherche_amis') }}
        </a>
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

    <h2>{{ __('friends.demandes_recues') }}</h2>

    @if($demandes->isEmpty())
        <p>{{ __('friends.aucune_demande') }}</p>
    @else
        <ul class="result-list">
            @foreach($demandes as $demande)
                <li class="result-item" style="padding: 10px; border-bottom: 1px solid #ccc; display: flex; align-items: center; justify-content: space-between;">
                    <div class="profile-trigger" style="display: flex; align-items: center;">
                        @if($demande->requester_imageProfil)
                            <img src="{{ asset($demande->requester_imageProfil) }}" alt="{{ __('friends.avatar_profil') }}" class="avatar" style="width:40px;height:40px;border-radius:50%; margin-right:10px;">
                        @else
                            <img src="{{ asset('images/avatars/default-avatar.jpg') }}" alt="{{ __('friends.avatar_defaut') }}" class="avatar" style="width:40px;height:40px;border-radius:50%; margin-right:10px;">
                        @endif
                        <span>{{ $demande->email ?? 'Email non disponible' }}</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <!-- Bouton accepter -->
                        <form action="{{ route('amis.accepter') }}" method="POST">
                            @csrf
                            <input type="hidden" name="demande_id" value="{{ $demande->id }}">
                            <button type="submit" class="back-button" title="{{ __('friends.accepter') }}">
                                {{ __('friends.accepter') }}
                            </button>
                        </form>
                        <!-- Bouton refuser (affiché avec un X et en rouge) -->
                        <form action="{{ route('amis.refuser') }}" method="POST">
                            @csrf
                            <input type="hidden" name="demande_id" value="{{ $demande->id }}">
                            <button type="submit" class="reject-button" title="{{ __('friends.refuser') }}">
                                X
                            </button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
@endsection