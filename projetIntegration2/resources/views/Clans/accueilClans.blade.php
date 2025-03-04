@extends('Layouts.app')
@section('titre', 'Clans')

@section('style')
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" style="text/css" href="{{asset('css/Clans/clans.css')}}">
@endsection()

@section('contenu')
<div class="contenuPrincipal">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 colonneCanaux">
                <div class="container">
                    <div class="column"> <!-- column est en anglais parce que c'est le nom de la classe bootstrap c'est pas mon choix -->
                        @if(isset($clan))
                        <div class="conteneurImage" style="background-image: url('{{ asset($clan->image) }}');">
                            <div class="texteSurImage">{{ $clan->nom }}</div>
                            @if($utilisateur->id == $clan->adminId)
                            <div><a href="{{ route('clan.parametres', ['id' => $clan->id]) }}"><i class="fa-solid fa-ellipsis"></i></a></div>
                            @endif
                        </div>
                        @endif
                        <div class="conteneurCanaux">
                            @if(isset($categories))
                            @foreach($categories as $categorie)
                            <div class="categorieCanal">
                                <div class="titreCategorieCanal categorie_{{ $categorie->id }}">
                                    <div>
                                        <i class="fa-solid fa-minus"></i>
                                        {{ $categorie->categorie }}
                                    </div>
                                    <i class="fa-solid fa-plus fa-xs"></i>
                                </div>
                                @if(isset($canauxParCategorie[$categorie->id]))
                                @foreach($canauxParCategorie[$categorie->id] as $canal)
                                <div class="canal">
                                    <a href="/clan/{{ $id }}/canal/{{ $canal->id }}" class="canal_{{ $canal->id }}">
                                        <div>
                                            <i class="fa-solid fa-hashtag"></i>
                                            {{ $canal->titre }}
                                        </div>
                                    </a>
                                    <div class="iconesModificationCanal">
                                        <i class="fa-solid fa-pen modifier"></i>
                                        <i class="fa-solid fa-x supprimer"></i>
                                    </div>
                                </div>
                                @endforeach
                                @else
                                <p>{{ __('clans.no_channel_clan') }}</p>
                                @endif
                            </div>
                            @endforeach
                            @else
                            <p>{{ __('clans.no_category') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8 colonneMessages">
                <div class="contenuScrollable">
                    <!-- COLONNE POUR LES MESSAGES POUR XAVIER -->
                </div>
                <div class="entreeFixe">
                    <input type="text" placeholder="{{ __('clans.message_input) }}" maxlength="1000">
                    <i class="fa-solid fa-play aly fa-xl"></i>
                </div>
            </div>
            <div class="col-md-2 colonneMembres">
                <div class="contenuScrollableMembres">
                    @if(isset($membres))
                    @foreach($membres as $membre)
                    <div class="membre">
                        <a href="">
                            <img src="{{asset($membre->imageProfil)}}">
                            <div>
                                {{$membre->prenom}} {{$membre->nom}}
                            </div>
                        </a>
                    </div>
                    @endforeach
                    @else
                    <p>{{ __('clans.invite_show') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <form id="formulaireClan" action="{{ route('canal.actions', ['id' => $id]) }}" method="POST">
        @csrf
        <input type="hidden" class="action" name="action">
        <input type="hidden" class="requete" name="requete">
    </form>

    <!-- Fenêtre contextuelle pour ajouter un canal -->
    <div id="ajoutCanal" class="fenetreCategorie">
        <div class="conteneurConfirmation">
            <div class="titreConfirmation">
                <div>{{ __('clans.add_channel') }}</div>
            </div>
            <div class="texteConfirmation">
                <input type="text" name="entreeNomCanal" class="form-control entreeNomCanal" placeholder="ex.: Cardio">
                <span class="messageErreur"></span>
            </div>

            <div class="boutonsConfirmation">
                <button class="annuler" type="button">{{ __('clans.cancel') }}</button>
                <button id="confirmerAjout" type="button">{{ __('clans.confirm') }}</button>
            </div>
        </div>
    </div>

    <!-- Fenêtre contextuelle pour renommer un canal -->
    <div id="renommerCanal" class="fenetreCategorie">
        <div class="conteneurConfirmation">
            <div class="titreConfirmation">
                <div>{{ __('clans.rename_channel') }}</div>
            </div>
            <div class="texteConfirmation">
                <input type="text" name="entreeNomCanal" class="form-control entreeNomCanal" placeholder="ex.: Cardio">
                <span class="messageErreur"></span>
            </div>

            <div class="boutonsConfirmation">
                <button class="annuler" type="button">{{ __('clans.cancel') }}</button>
                <button id="confirmerRenommage" type="button">{{ __('clans.confirm') }}</button>
            </div>
        </div>
    </div>

    <!-- Fenêtre contextuelle pour supprimer un canal -->
    <div id="confirmationSuppression" class="fenetreCategorie">
        <div class="conteneurConfirmation">
            <div class="titreConfirmation">
                <div>{{ __('clans.delete_channel') }}</div>
            </div>
            <div class="texteConfirmation">
                <div>{{ __('clans.delete_channel_warning') }}</div>
            </div>

            <div class="boutonsConfirmation">
                <button class="annuler" type="button">{{ __('clans.cancel') }}</button>
                <button id="confirmerSuppression" type="button">{{ __('clans.delete') }}</button>
            </div>
        </div>
    </div>

    <div id="conteneurMessages">
        @if(session('message'))
        <div class="alert" id="messageSucces">
            <span>{{session('message')}}</span>
            <button class="close-btn">X</button>
        </div>
        @endif

        <!--Obligé d'utiliser $errors ici c'est la facon que laravel gère ses erreurs-->
        @if($errors->any() || session('erreur'))
        <div class="alert" id="messageErreur">
            <ul>
                @if($errors->any())
                @foreach($errors->all() as $erreur)
                <li>{{ $erreur }}</li>
                @endforeach
                @endif
                @if(session('erreur'))
                <li>{{ session('erreur') }}</li>
                @endif
            </ul>
            <button class="close-btn">X</button>
        </div>
        @endif
    </div>

</div>

@endsection()


@section("scripts")
<script src="{{ asset('js/Clans/clans.js') }}" crossorigin="anonymous"> </script>
@endsection()