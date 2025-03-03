<!DOCTYPE html>
<html lang="fr-CA">
<head>
<meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
<title> GymCord - Paramètres de clan </title>
<meta name="viewport" content="width=device-width, initial-scale=1">
        <script defer src="https://use.fontawesome.com/releases/v5.1.0/js/all.js" integrity="sha384-3LK/3kTpDE/Pkp8gTNp2gR/2gOiwQ6QaO7Td0zV76UFJVhqLl4Vl3KL1We6q6wR9" crossorigin="anonymous"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" style="text/css" href="\css\gabaritCss.css">
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://kit.fontawesome.com/55ec8bd5f8.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" style="text/css" href="{{asset('css/Clans/parametres.css')}}">
<meta charset="UTF-8">
</head>

<body class=" flex h-screen" id="background">
    <main id="main">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-2 colonneNavigationParametres">
            <div class="conteneurNavigation">
              <div class="titreNavigation">Paramètres</div>
              <div class="navigationParametres">
                <div class="categorieParametre general actif" >Général</div>
                <div class="categorieParametre canaux" >Canaux</div>
                <div class="categorieParametre membres" >Membres</div>
                <div class="categorieParametre supprimer" >Supprimer le clan</div>
              </div>
            </div>
          </div>

          <div class="col-md-10 colonneParametres">
            <div class="conteneurParametres ">

              <div class="titreParametre">Paramètres généraux</div>
              <a href="{{ route('clan.montrer', ['id' => $id]) }}">
                <div class="boutonRetour">
                  <i class="fa-regular fa-circle-xmark fa-3x"></i>
                  <div>QUITTER</div>
                </div>
              </a>
            </div>
            <!-- TODO - CHANGER L'IMAGE QUI APPARAIT POUR CELLE DU CLAN -->
            <form action="{{ route('clan.miseAJour.general', ['id' => $id]) }}" method="POST" enctype="multipart/form-data" id="formulaireSoumission">
              @csrf
              <div class="row">
                <div class="col-md-12 parametresGeneraux">
                  <img src="{{asset($clan->image)}}" alt="erreur lors de l'affichage de votre image.">
                  <div class="formulaireAjoutImage">
                    <div>Une image de forme carrée est recommendée pour le clan.</div>
                      <div class="form-group">
                        <label for="imageClan" class="televerser">Téléverser une image</label>
                        <input type="file" class="form-control-file" id="imageClan" name="imageClan" accept="image/*">
                      </div>
                    </div>
                    <div class="nomClan">
                        <label for="nomClan">Nom du clan</label>
                        <input type="text" class="form-control" id="nomClan" name="nomClan" value="{{$clan->nom}}">
                    </div>
                </div>
              </div>
              <div class="row barreEnregistrerConteneur">
                  <div class="col-md-10 rangeeEnregistrer">
                      <div>N'oubliez pas d'enregistrer vos modifications avant de quitter!</div>
                      <button type="submit" class="btn btn-success">Enregistrer</button>
                  </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div id="conteneurMessages">
        @if(session('message'))
          <div class="alert" id="messageSucces">
            <span>{{session('message')}}</span>
            <button class="close-btn" onclick="fermerAlerte("messageSuccess")">X</button>
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
              @elseif(session('erreur'))
                    <li>{{session('erreur')}}</li>
              @endif
            </ul>
            <button class="close-btn">X</button>
          </div>
        @endif
    </div>
    </main>
 <footer>
</footer>
</body>
<script src="{{ asset('js/Clans/parametresClan.js') }}" crossorigin="anonymous"> </script>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</html>
