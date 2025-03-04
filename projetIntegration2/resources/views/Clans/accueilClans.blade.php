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
                                        <p>Aucun canal n'est créé pour ce clan. Veuillez en créer une pour commencer.</p>
                                    @endif
                                    </div>
                                @endforeach
                            @else
                                <p>Aucune catégorie de canal n'a été créée pour ce clan. Veuillez en créer une pour ajout des canaux.</p>
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
                    <input type="text" placeholder="Entrez votre message içi..." maxlength="1000">
                    <i class="fa-solid fa-play aly fa-xl"></i>
                </div>
            </div>
            <div class="col-md-2 colonneMembres">
                <div class="contenuScrollableMembres">
                    @if(isset($membres))
                        @foreach($membres as $membre)
                            <div class="membre">
                                <a href="">
                                    <img src="{{asset($membre->imageProfil)}}" > 
                                    <div>
                                        {{$membre->prenom}} {{$membre->nom}}
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    @else
                        <p>Invitez quelqu'un au clan pour afficher les membres!</p>
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
                <div>Ajouter un canal</div>
            </div>
            <div class="texteConfirmation">
                <input type="text" name="entreeNomCanal" class="form-control entreeNomCanal" placeholder="ex.: Cardio">
                <span class="messageErreur"></span>
            </div>

            <div class="boutonsConfirmation">
                <button class="annuler" type="button">Annuler</button>
                <button id="confirmerAjout" type="button">Confirmer</button>
            </div>
        </div>
    </div>

    <!-- Fenêtre contextuelle pour renommer un canal -->
    <div id="renommerCanal" class="fenetreCategorie">
        <div class="conteneurConfirmation">
            <div class="titreConfirmation">
                <div>Renommer un canal</div>
            </div>
            <div class="texteConfirmation">
                <input type="text" name="entreeNomCanal" class="form-control entreeNomCanal" placeholder="ex.: Cardio">
                <span class="messageErreur"></span>
            </div>

            <div class="boutonsConfirmation">
                <button class="annuler" type="button">Annuler</button>
                <button id="confirmerRenommage" type="button">Confirmer</button>
            </div>
        </div>
    </div>

    <!-- Fenêtre contextuelle pour supprimer un canal -->
    <div id="confirmationSuppression" class="fenetreCategorie">
        <div class="conteneurConfirmation">
            <div class="titreConfirmation">
                <div>Supprimer le canal</div>
            </div>
            <div class="texteConfirmation">
                <div>Êtes-vous sur de vouloir supprimer ce canal?</div>
            </div>

            <div class="boutonsConfirmation">
                <button class="annuler" type="button">Annuler</button>
                <button id="confirmerSuppression" type="button">Supprimer</button>
            </div>
        </div>
    </div>

</div>

@endsection()


@section("scripts")
<script src="{{ asset('js/Clans/clans.js') }}" crossorigin="anonymous"> </script>
@endsection()