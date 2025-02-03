 <!DOCTYPE html>
<html lang="fr-CA">
<head>
<meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
<title> @yield('titre') </title>
<meta name="viewport" content="width=device-width, initial-scale=1">
        <script defer src="https://use.fontawesome.com/releases/v5.1.0/js/all.js" integrity="sha384-3LK/3kTpDE/Pkp8gTNp2gR/2gOiwQ6QaO7Td0zV76UFJVhqLl4Vl3KL1We6q6wR9" crossorigin="anonymous"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" style="text/css" href="\css\GabaritCss.css">
        <script src="https://cdn.tailwindcss.com"></script>
        @yield("style")
   
<meta charset="UTF-8">
</head>

<body class=" flex h-screen" id="background">
    <!-- Sidebar -->
    <aside class="w-16  text-white h-full flex flex-col items-center py-4 space-y-4" id="sidebar">
        <div class="w-10 h-10 bg-gray-600 rounded-full"></div>
        <div class="w-10 h-10 bg-gray-500 rounded-full"></div>
        <div class="w-10 h-10 bg-red-600 rounded-full"></div>
        <div class="w-10 h-10 bg-yellow-500 rounded-full"></div>
        <div class="w-10 h-10 bg-blue-700 rounded-full"></div>
        <div class="w-10 h-10 bg-white border border-gray-700 rounded-full"></div>
        <div class="w-10 h-10 bg-blue-900 rounded-full"></div>
    </aside>
    
    <!-- Main Content -->
     <main class="flex-1 p-6 flex flex-col justify-start  mt-4">
 
        <div>
          @yield('contenu')
        </div>
    </main>
<!-- Mettre le footer -->
 <footer>
</footer>
</body>
</html>
