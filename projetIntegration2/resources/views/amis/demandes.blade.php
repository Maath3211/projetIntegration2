@extends('layouts.app')

@section('contenu')
    <div class="top-buttons" style="margin-bottom: 20px;">
        <a href="{{ route('amis.index') }}" class="search-button" style="text-decoration: none; padding: 10px 20px; background-color: #a9fe77; color: #000; border: 1px solid #999; border-radius: 5px; margin-right: 10px;">
            {{ __('friends.recherche_amis') }}
        </a>
        <a href="{{ route('amis.demandes') }}" class="add-button" style="text-decoration: none; padding: 10px 20px; background-color: #a9fe77; color: #000; border: 1px solid #999; border-radius: 5px;">
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

    <h2>{{ __('friends.received_requests') }}</h2>

    @if($demandes->isEmpty())
        <p>{{ __('friends.no_requests') }}</p>
    @else
        <ul class="result-list">
            @foreach($demandes as $demande)
                <li class="result-item" style="padding: 10px; border-bottom: 1px solid #ccc; display: flex; align-items: center; justify-content: space-between;">
                    <div class="profile-trigger" style="display: flex; align-items: center;">
                        @if($demande->requester_avatar)
                            <img src="{{ asset('images/avatars/' . $demande->requester_avatar) }}" alt="{{ __('friends.avatar_profil') }}" class="avatar" style="width:40px;height:40px;border-radius:50%; margin-right:10px;">
                        @else
                            <img src="{{ asset('images/avatars/default-avatar.jpg') }}" alt="{{ __('friends.avatar_defaut') }}" class="avatar" style="width:40px;height:40px;border-radius:50%; margin-right:10px;">
                        @endif
                        <span class="username">{{ $demande->requester_username }}</span>
                    </div>
                    <div>
                        <!-- Bouton accepter -->
                        <form action="{{ route('amis.accepter') }}" method="POST" style="display:inline-block;">
                            @csrf
                            <input type="hidden" name="demande_id" value="{{ $demande->id }}">
                            <button type="submit" class="add-button" style="background-color: #a9fe77; border: none; padding: 8px 12px; border-radius: 5px; cursor: pointer;" title="{{ __('friends.accepter') }}">{{ __('friends.accepter') }}</button>
                        </form>
                        <!-- Bouton refuser -->
                        <form action="{{ route('amis.refuser') }}" method="POST" style="display:inline-block; margin-left:5px;">
                            @csrf
                            <input type="hidden" name="demande_id" value="{{ $demande->id }}">
                            <button type="submit" class="cancel-button" style="background-color: #ff7b7b; border: none; padding: 8px 12px; border-radius: 5px; cursor: pointer;" title="{{ __('friends.refuser') }}">{{ __('friends.refuser') }}</button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
@endsection