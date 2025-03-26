@extends('layouts.app')

@section('style')
    <style>
        .back-button, .unit-btn {
            background: linear-gradient(135deg, #a9fe77, #60c22c);
            border: none;
            padding: 12px 25px;
            cursor: pointer;
            font-size: 16px; /* Texte légèrement réduit */
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
    </style>
@endsection

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
        <input type="text" name="q" placeholder="{{ __('clans.recherche_clan') }}" required style="padding:10px; width:300px; color:#000;">
        <button type="submit" class="back-button">
            {{ __('clans.bouton_recherche') }}
        </button>
    </form>

    @isset($mesClans)
        @if($mesClans->isEmpty())
            <p>{{ __('clans.aucun_clans_trouve') }}</p>
        @else
            <ul class="result-list" style="list-style:none; padding:0;">
                @foreach($mesClans as $clan)
                    <li class="result-item"
                        style="display:flex; justify-content: space-between; align-items:center; padding:10px; border-bottom:1px solid #ccc;">
                        <div style="display: flex; align-items: center;">
                            @if($clan->image)
                                <img src="{{ asset($clan->image) }}" alt="Photo de {{ $clan->nom }}"
                                    style="width:50px; height:50px; margin-right:10px; border-radius:50%;">
                            @else
                                <img src="{{ asset('images/clans/default.jpg') }}" alt="Photo par défaut de {{ $clan->nom }}"
                                    style="width:50px; height:50px; margin-right:10px; border-radius:50%;">
                            @endif
                            <span class="clan-name">{{ $clan->nom }}</span>
                        </div>
                        <!-- Formulaire pour rejoindre le clan avec le nouveau style -->
                        <form action="{{ route('clans.rejoindre') }}" method="POST">
                            @csrf
                            <input type="hidden" name="clan_id" value="{{ $clan->id }}">
                            <button type="submit" class="back-button">
                                {{ __('clans.joindre') }}
                            </button>
                        </form>
                    </li>
                @endforeach
            </ul>
        @endif
    @endisset
@endsection