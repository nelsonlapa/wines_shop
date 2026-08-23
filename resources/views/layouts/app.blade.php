<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>Vinha & Companhia | Vinhos com origem</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="wine-site overflow-x-hidden min-h-screen flex flex-col">

    <!-- Navbar -->
    @include('partials.navbar')


    <!-- CONTEÚDO NORMAL -->
    <main class="flex-1">
        @yield('fullwidth')

        <div class="container mx-auto px-6 py-8">
            @yield('content')
        </div>
    </main>
    
    @include('partials.footer')
</body>

</html>
