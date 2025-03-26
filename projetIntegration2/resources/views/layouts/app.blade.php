<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title> GymCord</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script defer src="https://use.fontawesome.com/releases/v5.1.0/js/all.js"
    integrity="sha384-3LK/3kTpDE/Pkp8gTNp2gR/2gOiwQ6QaO7Td0zV76UFJVhqLl4Vl3KL1We6q6wR9" crossorigin="anonymous">
  </script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <!-- importation bootstrap -->
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
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
  <!-- famille de police -->
  @livewireStyles
  @yield('style')
  <meta charset="UTF-8">
</head>

<body class=" flex h-screen">
  <!-- Barre de navigation entre les clans et les messages privés entre autres -->
  <button id="hamburgerBtn" class="hamburger-btn">
    <span class="hamburger-line"></span>
    <span class="hamburger-line"></span>
    <span class="hamburger-line"></span>
  </button>

  {{-- ** Navigation mobile ** --}}
  <div id="mobileMenu" class="mobile-menu">
    <!-- Navigation aux messages privés entre utilisateurs -->
    <a href="{{ route('conversations.index') }}">
      <div class="w-16 h-16 rounded-full overflow-hidden bullePersonnalisee">
        <i class="fa-solid fa-comment fa-2xl"></i>
      </div>
    </a>

    <!-- Navigation aux classements -->
    <a href="{{route('scores.meilleursGroupes')}}" title="{{__('layout.classements')}}">
      <div class="w-16 h-16 rounded-full overflow-hidden bullePersonnalisee">
        <i class="fa-solid fa-ranking-star fa-2xl"></i>
      </div>
    </a>

    <!-- Bouton pour la page de localisation -->
    <a href="/localisation">
      <div class="w-16 h-16 rounded-full overflow-hidden bullePersonnalisee">
        <i class="fa-solid fa-location-dot fa-2xl"></i>
      </div>
    </a>

    <!-- Tous les clans dont l'utilisateur actuel fait partie -->
    @if (isset($clans))
    @foreach ($clans as $clan)
    <a href="{{ route('clan.montrer', ['id' => $clan->id]) }}">
      <div class="w-16 h-16 rounded-full overflow-hidden">
        <img src="{{ asset($clan->image) }}" class="object-cover w-full h-full">
      </div>
    </a>
    @endforeach
    @endif

    <a id="creerClanMobile">
      <div class="w-16 h-16 rounded-full overflow-hidden bullePersonnalisee creerClan">
        <i class="fa-regular fa-square-plus fa-2xl"></i>
      </div>
    </a>

    <a href="{{ route('graphs.index') }}" title="Mes Graphiques">
      <div class="w-16 h-16 rounded-full overflow-hidden bullePersonnalisee position-relative">
        <i class="fa-solid fa-chart-line fa-2xl"></i>
        <i class="fa-solid fa-plus position-absolute" style="font-size: 0.8em; color: #a9fe77; bottom: 12px; right: 12px; background: #333; border-radius: 50%; width: 18px; height: 18px; display: flex; align-items: center; justify-content: center; border: 1px solid #a9fe77;"></i>
      </div>
    </a>

    <!-- Language Switcher -->
    <div class="w-16 text-center mb-4">
      <div class="language-option {{ app()->getLocale() == 'en' ? 'active' : '' }}"
        style="background: #444; color: white; padding: 5px; border-radius: 4px; margin-bottom: 4px; cursor: pointer;"
        data-locale="en"
        onclick="switchLanguageWithLivewire('en')">
        <span style="color: {{ app()->getLocale() == 'en' ? '#fff' : '#aaa' }}; text-decoration: none; display: block;">EN</span>
      </div>
      <div class="language-option {{ app()->getLocale() == 'fr' ? 'active' : '' }}"
        style="background: #444; color: white; padding: 5px; border-radius: 4px; cursor: pointer;"
        data-locale="fr"
        onclick="switchLanguageWithLivewire('fr')">
        <span style="color: {{ app()->getLocale() == 'fr' ? '#fff' : '#aaa' }}; text-decoration: none; display: block;">FR</span>
      </div>
    </div>

    <div class="relative mt-auto">
      <button id="mobileProfileMenuBtn" class="w-16 h-16 overflow-hidden rounded-full">
        <img src="{{ asset(Auth::user()->imageProfil) }}" class="object-cover w-full h-full">
      </button>
      <div id="mobileProfileMenu" class="absolute bottom-full left-0 mb-2 w-48 bg-white shadow-lg rounded-lg hidden">
        <ul>
          <li class="py-2 px-4 hover:bg-gray-100">
            <a href="{{ route('profil.profil') }}" class="block text-gray-800">{{__('layout.mon_profil')}}</a>
          </li>
          <li class="py-2 px-4 hover:bg-gray-100">
            <a href="{{ route('profil.modification') }}" class="block text-gray-800">{{__('layout.parametres')}}</a>
          </li>
          <li class="border-t border-gray-200">
            <form action="{{ route('profil.deconnexion') }}" method="post">
              @csrf
              <button type="submit" class="w-full text-left py-2 px-4 text-red-600 hover:bg-gray-100">{{__('layout.deconnexion')}}</button>
            </form>
          </li>
        </ul>
      </div>
    </div>

    <script>

    </script>
  </div>


  {{-- ** Navigation PC ** --}}
  <header id="navBar">
    <aside class="w-20 text-white h-screen flex flex-col items-center py-4 space-y-4 p-5">

      <!-- Navigation aux messages privés entre utilisateurs -->
      <a href="{{ route('conversations.index') }}" title="{{__('layout.messagerie')}}">
        <div class="w-16 h-16 rounded-full overflow-hidden bullePersonnalisee"><i
            class="fa-solid fa-comment fa-2xl"></i></div>
      </a>

      <!-- Navigation aux classements -->
      <a href="{{route('scores.meilleursGroupes')}}" title="{{__('layout.classements')}}">
        <div class="w-16 h-16 rounded-full overflow-hidden bullePersonnalisee"><i
            class="fa-solid fa-ranking-star fa-2xl"></i></div>
      </a>

      <!-- Bouton pour la page de localisation -->
      <a href="/localisation" title="{{__('layout.localisation')}}">
        <div class="w-16 h-16 rounded-full overflow-hidden bullePersonnalisee">
          <i class="fa-solid fa-location-dot fa-2xl"></i>
        </div>
      </a>

      <!-- Tous les clans dont l'utilisateur actuel fait partie -->
      @if (isset($clans))
      @foreach ($clans as $clan)
      <a href="{{ route('clan.montrer', ['id' => $clan->id]) }}" title="{{$clan->nom}}">
        <div class=" w-16 h-16 rounded-full overflow-hidden"><img src="{{ asset($clan->image) }}"
            class="object-cover w-full h-full"></div>
      </a>
      @endforeach
      @endif
      <a id="creerClan" title="{{__('layout.creer_clan')}}">
        <div class="w-16 h-16 rounded-full overflow-hidden bullePersonnalisee creerClan"><i
            class="fa-regular fa-square-plus fa-2xl"></i></i></div>
      </a>

      <a href="{{ route('graphs.index') }}" title="{{__('layout.mes_graphiques')}}">
        <div class="w-16 h-16 rounded-full overflow-hidden bullePersonnalisee position-relative">
          <i class="fa-solid fa-chart-line fa-2xl"></i>
          <i class="fa-solid fa-plus position-absolute" style="font-size: 0.8em; color: #a9fe77; bottom: 12px; right: 12px; background: #333; border-radius: 50%; width: 18px; height: 18px; display: flex; align-items: center; justify-content: center; border: 1px solid #a9fe77;"></i>
        </div>
      </a>
      <!-- Language Switcher -->
      <div class="w-16 text-center mb-4 languageOptionsDiv">
        <div class="language-option {{ app()->getLocale() == 'en' ? 'active' : '' }}"
          style="background: #444; color: white; padding: 5px; border-radius: 4px; margin-bottom: 4px; cursor: pointer;"
          data-locale="en"
          onclick="switchLanguageWithLivewire('en')">
          <span style="color: {{ app()->getLocale() == 'en' ? '#fff' : '#aaa' }}; text-decoration: none; display: block;">EN</span>
        </div>
        <div class="language-option {{ app()->getLocale() == 'fr' ? 'active' : '' }}"
          style="background: #444; color: white; padding: 5px; border-radius: 4px; cursor: pointer;"
          data-locale="fr"
          onclick="switchLanguageWithLivewire('fr')">
          <span style="color: {{ app()->getLocale() == 'fr' ? '#fff' : '#aaa' }}; text-decoration: none; display: block;">FR</span>
        </div>
      </div>

    </aside>
    <div class="mt-auto sectionProfil">
      <button id="profileMenuBtn" class="w-16 h-16 overflow-hidden rounded-full">
        <img src="{{ asset(Auth::user()->imageProfil) }}" class="object-cover w-full h-full">
      </button>
      <div id="profileMenu" class="absolute bottom-full left-0 mb-2 w-48 bg-white shadow-lg rounded-lg hidden">
        <ul>
          <li class="py-2 px-4 hover:bg-gray-100">
            <a href="{{ route('profil.profil') }}" class="block text-gray-800">{{__('layout.mon_profil')}}</a>
          </li>
          <li class="py-2 px-4 hover:bg-gray-100">
            <a href="{{ route('profil.modification') }}" class="block text-gray-800">{{__('layout.parametres')}}</a>
          </li>
          <li class="border-t border-gray-200">
            <form action="{{ route('profil.deconnexion') }}" method="post">
              @csrf
              <button type="submit" class="w-full text-left py-2 px-4 text-red-600 hover:bg-gray-100">{{__('layout.deconnexion')}}</button>
            </form>
          </li>
        </ul>
      </div>
    </div>
  </header>
  {{-- ** Fin navigation ** --}}

  <!-- Contenu principal -->
  <main>
    <div>
      @yield('contenu')
      <form action="{{ route('clan.creer') }}" method="POST" enctype="multipart/form-data"
        id="formulaireCreationClan">
        @csrf
        <div id="fenetreAjoutClan" class="fenetreCategorie">
          <div class="conteneurConfirmation">
            <div class="titreConfirmation" style="display: flex; align-items: center; padding:10px;">
              <img src="{{ asset('img/Clans/default.jpg') }}" alt="Image du clan" class="apercuImage"
                style="width: 50px; height: 50px; margin-right: 10px;">
              <input type="text" name="nomClan" class="form-control entreeNomClan"
                placeholder="{{__('layout.nom_clan')}}">
            </div>
            <div class="optionsClan">
              <div class="televersementImage" style="margin-top: 10px;">
                <button id="selectionnerImage" type="button">{{__('layout.choisir_image')}}</button>
                <input type="file" id="entreeImageCachee" name="imageClan" accept="image/*">
              </div>
              <div class="optionPublic" style="margin-top: 10px;">
                <label>
                  {{__('layout.clan_publique')}}
                  <input type="checkbox" name="clanPublic" class="form-check-input">
                </label>
              </div>
            </div>
            <span class="messageErreur"></span>


            <div class="boutonsConfirmation">
              <button class="annuler" type="button">{{__('layout.annuler')}}</button>
              <button id="confirmerAjoutClan" type="button">{{ __('layout.confirmer')}}</button>
            </div>
          </div>
        </div>
      </form>
      @livewireScripts

      <!-- Affichage des erreurs -->
      <div id="conteneurMessages">
        @if (session('message'))
        <div class="alert" id="messageSucces">
          <span>{{ session('message') }}</span>
          <button class="close-btn">X</button>
        </div>
        @endif

        <!--Obligé d'utiliser $errors ici c'est la facon que laravel gère ses erreurs-->
        @if ($errors->any() || session('erreur') || session('error'))
        <div class="alert" id="messageErreur">
          <ul>
            @if ($errors->any())
            @foreach ($errors->all() as $erreur)
            <li>{{ $erreur }}</li>
            @endforeach
            @endif
            @if (session('erreur'))
            <li>{{ session('erreur') }}</li>
            @endif
            @if (session('error'))
            <li>{{ session('error') }}</li>
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

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"
  integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"
  integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
  // Replace the original switchLanguage function
  function switchLanguageWithLivewire(locale) {
    console.log('Switching language to:', locale);

    // Update server locale
    fetch('/switch-language', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
          locale: locale
        })
      })
      .then(response => response.json())
      .then(data => {
        console.log('Language switch response:', data);

        // Notify all Livewire components with the correct parameter format
        if (window.Livewire) {
          window.Livewire.dispatch('localeChanged', {
            locale: locale
          });
        }

        // Force page reload to apply changes globally
        window.location.reload();
      })
      .catch(error => {
        console.error('Error switching language:', error);
      });
  }
</script>
@yield('scripts')

<script src="{{ asset('js/gabaritJs.js') }}" crossorigin="anonymous"></script>
<script src="{{ asset('js/drawerGabarit.js') }}" crossorigin="anonymous"></script>

</html>