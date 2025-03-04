<!DOCTYPE html>
<html lang="fr-CA">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title> GymCord - @yield('titre') </title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script defer src="https://use.fontawesome.com/releases/v5.1.0/js/all.js" integrity="sha384-3LK/3kTpDE/Pkp8gTNp2gR/2gOiwQ6QaO7Td0zV76UFJVhqLl4Vl3KL1We6q6wR9" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> <!-- importation bootstrap -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

    <!-- Importation pour les fonctionnalité pusher et ajax -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>

  <link rel="stylesheet" style="text/css" href="\css\GabaritCss.css"> <!-- style du "layout" app.blade.css -->
  <script src="https://cdn.tailwindcss.com"></script> <!-- nous rajoute <aside> et <main> entre autres -->
  <script src="https://kit.fontawesome.com/55ec8bd5f8.js" crossorigin="anonymous"></script> <!-- importation de Font Awesome pour les icônes -->
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet"> <!-- famille de police -->
  @livewireStyles
  @yield("style")
  <meta charset="UTF-8">
</head>

<body class=" flex h-screen">
  <!-- Barre de navigation entre les clans et les messages privés entre autres -->
  <header>
    <aside class="w-20 text-white h-screen flex flex-col items-center py-4 space-y-4 p-5">

      <!-- Navigation aux messages privés entre utilisateurs -->
      <a href="#">
        <div class="w-16 h-16 rounded-full overflow-hidden bullePersonnalisee"><i class="fa-solid fa-comment fa-2xl"></i></div>
      </a>

      <!-- Navigation aux classements -->
      <a href="#">
        <div class="w-16 h-16 rounded-full overflow-hidden bullePersonnalisee"><i class="fa-solid fa-ranking-star fa-2xl"></i></div>
      </a>

      <!-- Tous les clans dont l'utilisateur actuel fait partie -->
      @if(isset($clans))
        @foreach($clans as $clan)
          <a href="{{ route('clan.montrer', ['id' => $clan->id]) }}">
            <div class="w-16 h-16 rounded-full overflow-hidden"><img src="{{ asset($clan->image) }}" class="object-cover w-full h-full"></div>
          </a>
        @endforeach
      @endif


      <a id="creerClan"> <div class="w-16 h-16 rounded-full overflow-hidden bullePersonnalisee creerClan"><i class="fa-regular fa-square-plus fa-2xl"></i></i></div></a>

        <form action="{{route('profil.deconnexion')}}" method="post">
          @csrf
          <button class="w-16 h-16 overflow-hidden" id="imgDeconnexion"><img src="{{ asset('img/logout.png') }}" class="object-cover w-full h-full"></button>
        </form>
      
      
      </aside>
    </header>
      

  <!-- Contenu principal -->
  <main>
    <div>
      @yield('contenu')
      <form action="{{ route('clan.creer') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div id="fenetreAjoutClan" class="fenetreCategorie">
          <div class="conteneurConfirmation">
              <div class="titreConfirmation" style="display: flex; align-items: center;">
                  <img src="{{ asset('img/Clans/default.jpg') }}" alt="Image du clan" class="apercuImage" style="width: 50px; height: 50px; margin-right: 10px;">
                  <input type="text" name="nomClan" class="form-control entreeNomClan" placeholder="Nom du clan">
              </div>
              <div class="optionsClan">
                  <div class="televersementImage" style="margin-top: 10px;">
                    <button id="selectionnerImage" type="button">Choisir une image</button>
                    <input type="file" id="entreeImageCachee" name="imageClan" accept="image/*">
                  </div>
                  <div class="optionPublic" style="margin-top: 10px;">
                    <label>
                        Clan public
                        <input type="checkbox" name="clanPublic" class="form-check-input">
                    </label>
                </div>
              </div>
              <span class="messageErreur"></span>

      
              <div class="boutonsConfirmation">
                  <button class="annuler" type="button">Annuler</button>
                  <button id="confirmerAjoutClan" type="submit">Confirmer</button>
              </div>
          </div>
        </div>
      </form>
      @livewireScripts

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
  </main>

  <!-- Pied de page -->
  <footer>
  </footer>

</body>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@yield('scripts')
<script src="{{asset('js/gabaritJs.js')}}" crossorigin="anonymous"></script>

</html>