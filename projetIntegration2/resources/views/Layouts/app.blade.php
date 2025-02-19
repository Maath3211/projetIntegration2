<!DOCTYPE html>
<html lang="fr-CA">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title> GymCord - @yield('titre') </title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script defer src="https://use.fontawesome.com/releases/v5.1.0/js/all.js" integrity="sha384-3LK/3kTpDE/Pkp8gTNp2gR/2gOiwQ6QaO7Td0zV76UFJVhqLl4Vl3KL1We6q6wR9" crossorigin="anonymous"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> <!-- importation bootstrap -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

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
      <a href="/clan/1">
        <div class="w-16 h-16 rounded-full overflow-hidden"><img src="{{ asset('img/workoutMasterLogo.jpg') }}" class="object-cover w-full h-full"></div>
      </a>
      <a href="/clan/2">
        <div class="w-16 h-16 rounded-full overflow-hidden"><img src="{{ asset('img/workoutMasterLogo.jpg') }}" class="object-cover w-full h-full"></div>
      </a>
      <a href="/clan/3">
        <div class="w-16 h-16 rounded-full overflow-hidden"><img src="{{ asset('img/workoutMasterLogo.jpg') }}" class="object-cover w-full h-full"></div>
      </a>
      <a href="/clan/4">
        <div class="w-16 h-16 rounded-full overflow-hidden"><img src="{{ asset('img/workoutMasterLogo.jpg') }}" class="object-cover w-full h-full"></div>
      </a>
      <a href="/clan/5">
        <div class="w-16 h-16 rounded-full overflow-hidden"><img src="{{ asset('img/workoutMasterLogo.jpg') }}" class="object-cover w-full h-full"></div>
      </a>
      <a href="/clan/6">
        <div class="w-16 h-16 rounded-full overflow-hidden"><img src="{{ asset('img/workoutMasterLogo.jpg') }}" class="object-cover w-full h-full"></div>
      </a>
      <a href="/clan/7">
        <div class="w-16 h-16 rounded-full overflow-hidden"><img src="{{ asset('img/workoutMasterLogo.jpg') }}" class="object-cover w-full h-full"></div>
      </a>

    </aside>
  </header>

  <!-- Contenu principal -->
  <main>
    <div>
      @yield('contenu')
      @livewireScripts
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
@yield('scripts')

</html>