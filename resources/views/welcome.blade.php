@extends('layouts.app')

@section('fullwidth')
    <section class="relative h-[800px] flex items-center justify-center text-white mb-24 overflow-hidden">

        <!-- IMAGEM -->
        <img src="{{ asset('images/hero.jpg') }}" class="absolute inset-0 w-full h-full object-cover scale-110 ">




        <!-- CONTEÚDO -->
        <div class="relative z-10 text-center px-4">

            <!-- CONTAINER BLUR -->
            <div class="backdrop-blur-md bg-white/10 border border-white/10 rounded-3xl p-10 max-w-4xl mx-auto shadow-2xl">

                <h1 class="text-5xl font-bold mb-4 text-white">
                    OS MELHORES EVENTOS ESTÃO AQUI.
                </h1>

                <p class="text-lg mb-6 max-w-2xl mx-auto text-white/80">
                    Encontre e garanta já o seu bilhete para os concertos,
                    festivais, jogos e espetáculos imperdíveis em Portugal
                </p>

                <!-- SEARCH -->
                <form action="{{ route('events.index') }}" method="GET"
                    class="max-w-3xl mx-auto flex items-center bg-white/90 rounded-xl shadow-lg overflow-hidden">

                    <!-- SEARCH -->
                    <div class="flex items-center flex-1 px-4">
                        <span class="text-gray-400 mr-2">🔎</span>

                        <input type="text" name="search" placeholder="Pesquisar eventos..."
                            class="w-full outline-none text-gray-800 bg-transparent py-4">
                    </div>

                    <!-- DIVIDER -->
                    <div class="w-px h-8 bg-gray-200"></div>

                    <!-- LOCATION -->
                    <div class="flex items-center px-4">
                        <span class="text-gray-400 mr-2">📍</span>

                        <input type="text" name="location" placeholder="Lisboa, Portugal"
                            class="w-40 outline-none text-gray-800 bg-transparent">
                    </div>

                    <!-- BUTTON -->
                    <button class="bg-blue-600 text-white px-6 py-4 hover:bg-blue-700 transition">
                        Pesquisar
                    </button>

                </form>

            </div>

        </div>

    </section>
@endsection

@section('content')
    <section class="relative -mt-64 z-20">
        <p class="text-5xl font-bold mb-4">
            Em Destaque
        </p>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

            @forelse($events as $event)
                <div
                    class="relative group bg-white rounded-2xl shadow-xl hover:shadow-2xl transition transform hover:-translate-y-1 overflow-hidden">

                    <!-- LINK -->
                    <a href="{{ route('events.show', $event) }}" class="absolute inset-0 z-10"></a>

                    @if ($event->image)
                        <img src="{{ asset('storage/' . $event->image) }}" class="w-full h-48 object-cover">
                    @endif

                    <div class="p-5 relative z-20">

                        <h3 class="text-xl font-bold mb-2 text-gray-900">
                            {{ $event->title }}
                        </h3>

                        <p class="text-gray-500 text-sm mb-1">
                            📅 {{ $event->date->format('d/m/Y H:i') }}
                        </p>

                        <p class="text-gray-500 text-sm mb-3">
                            📍 {{ $event->city ?? 'Local a definir' }}
                        </p>

                        <p class="font-bold text-blue-600 mb-4">
                            {{ $event->price == 0 ? 'Gratuito' : '€ ' . $event->price }}
                        </p>

                        <a href="{{ route('events.show', $event) }}"
                            class="block text-center bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 relative z-30">
                            {{ $event->price == 0 ? 'Inscrever-me' : 'Comprar Bilhete' }}

                        </a>

                    </div>

                </div>

            @empty
                <p>Não existem eventos disponíveis.</p>
            @endforelse

        </div>
    </section>
    <section class="mt-16">

        <div class="container mx-auto px-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                <!-- ===================== -->
                <!-- COLUNA ESQUERDA -->
                <!-- CATEGORIAS -->
                <!-- ===================== -->
                <div>

                    <h2 class="text-2xl font-bold mb-6 text-gray-900">
                        Categorias Populares
                    </h2>

                    <div class="grid grid-cols-2 gap-3">

                        @foreach ($categories as $category)
                            <a href="#" class="relative rounded-xl overflow-hidden shadow hover:shadow-lg transition">

                                <img src="{{ asset('storage/' . $category->image) }}" class="w-full h-24 object-cover">

                                <div class="absolute inset-0 bg-black/40 flex items-end p-2">
                                    <p class="text-white text-xs font-semibold">
                                        {{ $category->name }}
                                    </p>
                                </div>

                            </a>
                        @endforeach

                    </div>

                </div>

                <!-- ===================== -->
                <!-- COLUNA DIREITA -->
                <!-- NOVIDADES / RECOMENDADOS -->
                <!-- ===================== -->
                <div>

                    <h2 class="text-2xl font-bold mb-6 text-gray-900">
                        Novidades & Recomendados
                    </h2>

                    <div class="space-y-4">

                        @foreach ($events->take(4) as $event)
                            <a href="{{ route('events.show', $event) }}"
                                class="flex bg-white rounded-xl shadow hover:shadow-md transition overflow-hidden">

                                <!-- IMG -->
                                @if ($event->image)
                                    <img src="{{ asset('storage/' . $event->image) }}" class="w-24 h-24 object-cover">
                                @endif

                                <!-- INFO -->
                                <div class="p-3 flex flex-col justify-center">

                                    <h3 class="font-semibold text-gray-900">
                                        {{ $event->title }}
                                    </h3>

                                    <p class="text-sm text-gray-500">
                                        📅 {{ $event->date->format('d/m') }}
                                    </p>

                                    <p class="text-sm font-bold text-blue-600">
                                        @if ($event->price == 0)
                                            Gratuito
                                        @else
                                            € {{ $event->price }}
                                        @endif
                                    </p>

                                </div>

                            </a>
                        @endforeach

                    </div>

                </div>

            </div>

        </div>

    </section>
@endsection
