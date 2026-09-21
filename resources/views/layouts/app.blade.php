<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>Aroma Nobre | Vinhos com origem</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script>
        (() => {
            const theme = localStorage.getItem('aroma-theme');
            if (theme === 'dark') document.documentElement.classList.add('dark');
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="wine-site overflow-x-hidden min-h-screen flex flex-col">

    <!-- Navbar -->
    @include('partials.navbar')


    <!-- CONTEÚDO NORMAL -->
    <main class="flex-1">
        @yield('fullwidth')

        <div class="container mx-auto px-6 py-8 pt-24 md:pt-28">
            @yield('content')
        </div>
    </main>
    
    @include('partials.footer')
</body>

</html>
