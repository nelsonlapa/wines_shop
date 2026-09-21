@extends('layouts.app')

@section('fullwidth')
    <section class="relative min-h-[680px] flex items-center text-white overflow-hidden">
        <img src="https://images.unsplash.com/photo-1510812431401-41d2bd2722f3?auto=format&fit=crop&w=2200&q=85" class="absolute inset-0 w-full h-full object-cover" alt="Vinhas e uma garrafa de vinho">
        <div class="absolute inset-0 bg-gradient-to-r from-[#281014]/95 via-[#42151d]/75 to-transparent"></div>
        <div class="relative z-10 max-w-7xl mx-auto w-full px-6 pt-24 pb-24">
            <div class="max-w-2xl">
                <p class="wine-kicker mb-5">Vinhos com origem</p>
                <h1 class="font-serif text-5xl md:text-7xl leading-tight mb-6">Uma boa história começa sempre com um vinho.</h1>
                <p class="text-lg text-white/80 max-w-lg mb-9">Rótulos portugueses escolhidos à mão, do Douro ao Alentejo, para tornar cada encontro memorável.</p>
                <form action="{{ route('events.index') }}" method="GET" class="max-w-xl flex bg-white rounded-lg overflow-hidden shadow-2xl">
                    <input type="text" name="search" placeholder="Procure por casta, região ou estilo" class="min-w-0 flex-1 px-5 py-4 text-stone-800 outline-none">
                    <button class="wine-button px-6 py-4 font-semibold">Explorar</button>
                </form>
            </div>
        </div>
    </section>
@endsection

@section('content')
    <section class="relative z-20 -mt-12">
        <div class="flex items-end justify-between mb-7">
            <div><p class="wine-kicker mb-2">A seleção da semana</p><h2 class="font-serif text-4xl text-stone-900">Destaques</h2></div>
            <a href="{{ route('events.index') }}" class="hidden sm:block text-sm font-semibold text-[#5b1820]">Ver toda a coleção <span aria-hidden="true">→</span></a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

            @forelse($events as $event)
                <div
                    class="relative group bg-white border border-stone-200 hover:shadow-xl transition overflow-hidden">

                    <!-- LINK -->
                    <a href="{{ route('events.show', $event) }}" class="absolute inset-0 z-10"></a>

                    @if ($event->image)
                        <img src="{{ $event->image ? asset('storage/' . $event->image) : 'https://images.unsplash.com/photo-1506377247377-2a5b3b417ebb?auto=format&fit=crop&w=800&q=80' }}" class="w-full h-64 object-cover group-hover:scale-105 transition duration-500" alt="{{ $event->title }}">
                    @endif

                    <div class="p-5 relative z-20">

                        <p class="wine-kicker mb-2">Seleção Aroma Nobre</p>
                        <h3 class="font-serif text-xl font-bold mb-2 text-gray-900">
                            {{ $event->title }}
                        </h3>

                        <p class="text-gray-500 text-sm mb-1">
                            {{ $event->category->name ?? 'Vinho português' }}
                        </p>

                        <p class="text-gray-500 text-sm mb-3">
                            {{ $event->city ?? 'Portugal' }}
                        </p>

                        <p class="font-bold text-[#5b1820] mb-4 flex flex-wrap items-center gap-2">
                            @if($event->discount_percentage > 0)<span class="text-sm text-stone-400 price-old">{{ number_format($event->price ?: 12.50, 2, ',', '.') }} €</span>@endif
                            <span>{{ number_format($event->sale_price, 2, ',', '.') }} €</span>
                            @if($event->discount_percentage > 0)<span class="text-xs font-semibold bg-emerald-100 text-emerald-800 px-2 py-1 rounded-full">-{{ rtrim(rtrim(number_format($event->discount_percentage, 2, ',', '.'), '0'), ',') }}%</span>@endif
                            <span class="font-normal text-xs text-stone-500">/ garrafa</span>
                        </p>

                        <a href="{{ route('events.show', $event) }}"
                            class="block text-center wine-button py-3 rounded-lg relative z-30">
                            Adicionar à seleção

                        </a>

                    </div>

                </div>

            @empty
                <p>Não existem vinhos disponíveis.</p>
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

                    <p class="wine-kicker mb-2">Descubra o seu estilo</p>
                    <h2 class="font-serif text-3xl mb-6 text-gray-900">
                        Escolha por ocasião
                    </h2>

                    <div class="grid grid-cols-2 gap-3">

                        @foreach ($categories as $category)
                            <a href="{{ route('events.category', $category) }}" class="relative overflow-hidden shadow hover:shadow-lg transition">

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

                    <p class="wine-kicker mb-2">Notas da casa</p>
                    <h2 class="font-serif text-3xl mb-6 text-gray-900">
                        Populares
                    </h2>

                    <div class="space-y-4">

                        @foreach ($events->take(4) as $event)
                            <a href="{{ route('events.show', $event) }}"
                                class="flex bg-white rounded-xl shadow hover:shadow-md transition overflow-hidden">

                                <!-- IMG -->
                                @if ($event->image)
                                    <img src="{{ $event->image ? asset('storage/' . $event->image) : 'https://images.unsplash.com/photo-1473973266408-ed4e27abdd47?auto=format&fit=crop&w=300&q=80' }}" class="w-24 h-24 object-cover">
                                @endif

                                <!-- INFO -->
                                <div class="p-3 flex flex-col justify-center">

                                    <h3 class="font-semibold text-gray-900">
                                        {{ $event->title }}
                                    </h3>

                                    <p class="text-sm text-gray-500">
                                        {{ $event->category->name ?? 'Vinho português' }}
                                    </p>

                                    <p class="text-sm font-bold text-[#5b1820]">
                                        {{ $event->price == 0 ? '12,50' : number_format($event->price, 2, ',', '.') }} €
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
