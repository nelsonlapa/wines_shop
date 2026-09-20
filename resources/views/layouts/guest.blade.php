<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Aroma Nobre | {{ config('app.name', 'Vinhos com origem') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="wine-site auth-shell min-h-screen text-stone-900 antialiased">
    <div class="min-h-screen grid lg:grid-cols-[.9fr_1.1fr] bg-[#f7f1e8]">
        <aside class="relative hidden lg:flex min-h-screen overflow-hidden items-end p-12 text-white">
            <img src="https://images.unsplash.com/photo-1506377247377-2a5b3b417ebb?auto=format&fit=crop&w=1400&q=85" class="absolute inset-0 w-full h-full object-cover" alt="Garrafas de vinho">
            <div class="absolute inset-0 bg-gradient-to-t from-[#281014]/95 via-[#42151d]/55 to-[#42151d]/15"></div>
            <div class="relative z-10 max-w-md">
                <a href="{{ route('home') }}" class="brand-mark text-white">
                    <span class="brand-script">Aroma</span><span class="text-amber-200"> Nobre</span>
                </a>
                <p class="mt-6 font-serif text-4xl leading-tight">Vinhos escolhidos para momentos que ficam.</p>
                <p class="mt-4 text-white/75">Descobre rótulos portugueses com origem, carácter e uma história para contar.</p>
            </div>
        </aside>

        <main class="flex min-h-screen flex-col px-6 py-8 sm:px-10 lg:px-20">
            <div class="flex items-center justify-between">
                <a href="{{ route('home') }}" class="brand-mark text-[#5b1820] lg:hidden">
                    <span class="brand-script">Aroma</span> Nobre
                </a>
                <a href="{{ route('home') }}" class="ml-auto text-sm font-semibold text-[#5b1820] hover:text-[#771f2a]">Voltar à loja</a>
            </div>
            <div class="flex flex-1 items-center justify-center py-12">
                <div class="w-full max-w-md">
                    {{ $slot }}
                </div>
            </div>
        </main>
    </div>
</body>

</html>
