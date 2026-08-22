@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gray-100 py-10">

    <div class="max-w-7xl mx-auto px-6">

        <!-- TOPO -->
        <div class="mb-8">

            <h1 class="text-3xl font-bold text-gray-900 mb-4">
                Explorar Eventos
            </h1>

            <!-- SEARCH BAR -->
            <form action="{{ route('events.index') }}"
                  method="GET"
                  class="bg-white rounded-xl shadow flex items-center overflow-hidden">

                <!-- SEARCH -->
                <div class="flex items-center flex-1 px-4">
                    <span class="text-gray-400 mr-2">🔎</span>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Pesquisar eventos..."
                        class="w-full py-4 outline-none bg-transparent"
                    >
                </div>

                <!-- DIVIDER -->
                <div class="w-px h-8 bg-gray-200"></div>

                <!-- LOCATION -->
                <div class="flex items-center px-4">
                    <span class="text-gray-400 mr-2">📍</span>

                    <input
                        type="text"
                        name="location"
                        value="{{ request('location') }}"
                        placeholder="Lisboa, Portugal"
                        class="w-40 outline-none bg-transparent"
                    >
                </div>

                <!-- BUTTON -->
                <button class="bg-blue-600 text-white px-6 py-4 hover:bg-blue-700 transition">
                    Pesquisar
                </button>

            </form>

        </div>

        <!-- RESULTADOS -->
        <div class="mb-6 flex items-center justify-between">

            <p class="text-gray-500">
                {{ $events->total() }} eventos encontrados
            </p>

        </div>

        <!-- GRID -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

            @forelse($events as $event)

                <div class="relative bg-white rounded-2xl shadow hover:shadow-xl transition overflow-hidden group">

                    <!-- LINK CARD -->
                    <a href="{{ route('events.show', $event) }}"
                       class="absolute inset-0 z-10"></a>

                    <!-- IMAGE -->
                    <img src="{{ asset('storage/' . $event->image) }}"
                         class="w-full h-48 object-cover group-hover:scale-105 transition duration-300">

                    <!-- CONTENT -->
                    <div class="p-4">

                        <h2 class="font-bold text-lg text-gray-900 mb-2">
                            {{ $event->title }}
                        </h2>

                        <p class="text-sm text-gray-500 mb-1">
                            📅 {{ $event->date->format('d/m/Y H:i') }}
                        </p>

                        <p class="text-sm text-gray-500 mb-3">
                            📍 {{ $event->city }}
                        </p>

                        <div class="flex items-center justify-between">

                            <p class="font-bold text-blue-600">
                                @if($event->price == 0)
                                    Gratuito
                                @else
                                    € {{ $event->price }}
                                @endif
                            </p>

                            <span class="text-sm text-blue-600 font-medium">
                                Comprar →
                            </span>

                        </div>

                    </div>

                </div>

            @empty

                <div class="col-span-full text-center py-20">

                    <p class="text-gray-500 text-lg">
                        Nenhum evento encontrado.
                    </p>

                </div>

            @endforelse

        </div>

        <!-- PAGINAÇÃO -->
        <div class="mt-10">
            {{ $events->withQueryString()->links() }}
        </div>

    </div>

</div>

@endsection
