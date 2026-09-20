@extends('layouts.app')

@section('content')
<div class="py-8">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-5 mb-10">
        <div>
            <p class="wine-kicker mb-2">A nossa coleção</p>
            <h1 class="font-serif text-5xl text-stone-900">Vinhos para guardar na memória</h1>
            <p class="text-stone-500 mt-3">{{ $events->total() }} rótulos selecionados para a sua mesa.</p>
        </div>
        <form action="{{ route('events.index') }}" method="GET" class="flex w-full md:w-auto border border-stone-300 bg-white rounded-lg overflow-hidden">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Pesquisar vinhos" class="w-full md:w-64 px-4 py-3 outline-none text-sm">
            <button class="wine-button px-5 text-sm font-semibold">Pesquisar</button>
        </form>
    </div>

    <div class="flex flex-wrap gap-2 mb-8">
        <a href="{{ route('events.index') }}" class="px-4 py-2 rounded-full border text-sm {{ request()->routeIs('events.index') && !request('search') ? 'bg-[#5b1820] text-white border-[#5b1820]' : 'bg-white border-stone-300 text-stone-700 hover:border-[#5b1820]' }}">Todos</a>
        @foreach ($footerCategories as $category)
            <a href="{{ route('events.category', $category) }}" class="px-4 py-2 rounded-full border text-sm bg-white border-stone-300 text-stone-700 hover:border-[#5b1820]">{{ $category->name }}</a>
        @endforeach
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($events as $event)
            <article class="bg-white border border-stone-200 group overflow-hidden hover:shadow-xl transition">
                <a href="{{ route('events.show', $event) }}" class="block overflow-hidden">
                    <img src="{{ $event->image ? asset('storage/' . $event->image) : 'https://images.unsplash.com/photo-1506377247377-2a5b3b417ebb?auto=format&fit=crop&w=900&q=80' }}" class="w-full aspect-[4/5] object-cover group-hover:scale-105 transition duration-500" alt="{{ $event->title }}">
                </a>
                <div class="p-5">
                    <p class="wine-kicker mb-2">{{ $event->category->name ?? 'Vinho português' }}</p>
                    <h2 class="font-serif text-2xl text-stone-900 mb-1">{{ $event->title }}</h2>
                    <p class="text-sm text-stone-500 mb-4">{{ $event->city ?? 'Portugal' }} · Colheita selecionada</p>
                    <div class="flex items-center justify-between gap-3">
                        <p class="text-lg font-bold text-[#5b1820]">
                            @if($event->discount_percentage > 0)<span class="text-sm text-stone-400 price-old mr-1">{{ number_format($event->price ?: 12.50, 2, ',', '.') }} €</span>@endif
                            {{ number_format($event->sale_price, 2, ',', '.') }} €
                        </p>
                        @if($event->discount_percentage > 0)
                            <span class="shrink-0 text-xs font-semibold bg-emerald-100 text-emerald-800 px-2 py-1 rounded-full">-{{ rtrim(rtrim(number_format($event->discount_percentage, 2, ',', '.'), '0'), ',') }}%</span>
                        @endif
                        <a href="{{ route('events.show', $event) }}" class="wine-button px-4 py-2 rounded text-sm font-semibold">Ver vinho</a>
                    </div>
                </div>
            </article>
        @empty
            <div class="col-span-full bg-white border border-stone-200 p-12 text-center text-stone-500">Não encontrámos vinhos com esse perfil.</div>
        @endforelse
    </div>
    <div class="mt-10">{{ $events->withQueryString()->links() }}</div>
</div>
@endsection
