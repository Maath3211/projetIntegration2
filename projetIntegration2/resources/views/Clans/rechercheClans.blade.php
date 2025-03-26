@extends('layouts.app')

@section('contenu')
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

    <!-- Formulaire de recherche de clans -->
    <form action="{{ route('clans.recherche') }}" method="POST" style="margin-bottom:20px;">
        @csrf
        <input type="text" name="q" placeholder="{{ __('chat.recherche_exemple') }}" required style="padding:10px; width:300px; color:#000;">
        <button type="submit" class="search-button"
            style="padding: 10px 20px; background-color: #a9fe77; color:#000; border:1px solid #999; border-radius:5px; cursor:pointer;">{{ __('clans.bouton_recherche') }}</button>
    </form>

    @isset($clans)
        @if($clans->isEmpty())
            <p>{{ __('clans.aucun_clans_trouve') }}</p>
        @else
            <ul class="result-list" style="list-style:none; padding:0;">
                @foreach($clans as $clan)
                    <li class="result-item"
                        style="display:flex; justify-content: space-between; align-items:center; padding:10px; border-bottom:1px solid #ccc;">
                        <div style="display: flex; align-items: center;">
                            @if($clan->image)
                                <img src="{{ asset('images/clans/' . $clan->image) }}" alt="Photo de {{ $clan->nom }}"
                                    style="width:50px; height:50px; margin-right:10px; border-radius:50%;">
                            @else
                                <img src="{{ asset('images/clans/default.jpg') }}" alt="Photo par défaut de {{ $clan->nom }}"
                                    style="width:50px; height:50px; margin-right:10px; border-radius:50%;">
                            @endif
                            <span class="clan-name">{{ $clan->nom }}</span>
                        </div>
                        <!-- Formulaire pour rejoindre le clan -->
                        <form action="{{ route('clans.rejoindre') }}" method="POST">
                            @csrf
                            <input type="hidden" name="clan_id" value="{{ $clan->id }}">
                            <button type="submit" class="join-button"
                                style="background-color: #a9fe77; color: #000; border:1px solid #999; padding:10px 20px; border-radius:5px; cursor:pointer;">{{ __('clans.joindre') }}</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        @endif
    @endisset
@endsection