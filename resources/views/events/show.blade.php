@extends('layouts.app')

@section('content')
<div class="py-8">
    <a href="{{ route('events.index') }}" class="text-sm text-[#5b1820] font-semibold">← Voltar à coleção</a>

    @if (session('error'))
        <div class="mt-5 bg-red-50 border border-red-200 text-red-800 rounded-lg px-5 py-4">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 mt-7 items-start">
        <div class="bg-white overflow-hidden border border-stone-200">
            <img src="{{ $event->image ? asset('storage/' . $event->image) : 'https://images.unsplash.com/photo-1506377247377-2a5b3b417ebb?auto=format&fit=crop&w=1200&q=85' }}" class="w-full aspect-[4/5] object-cover" alt="{{ $event->title }}">
        </div>

        <div class="pt-2">
            <p class="wine-kicker mb-3">{{ $event->category->name ?? 'Vinho português' }}</p>
            <h1 class="font-serif text-5xl text-stone-900 leading-tight mb-3">{{ $event->title }}</h1>
            <p class="text-stone-500 mb-7">{{ $event->city ?? 'Portugal' }} · Produção limitada</p>
            <p class="font-serif text-3xl text-[#5b1820] mb-7 flex flex-wrap items-center gap-x-3 gap-y-2">
                @if($event->discount_percentage > 0)
                    <span class="font-sans text-lg text-stone-400 price-old">{{ number_format($event->price ?: 12.50, 2, ',', '.') }} €</span>
                @endif
                <span class="font-sans font-semibold whitespace-nowrap leading-none">{{ number_format($event->sale_price, 2, ',', '.') }} €</span>
                @if($event->discount_percentage > 0)
                    <span class="font-sans text-sm font-semibold bg-emerald-100 text-emerald-800 px-2.5 py-1 rounded-full">-{{ rtrim(rtrim(number_format($event->discount_percentage, 2, ',', '.'), '0'), ',') }}%</span>
                @endif
                <span class="font-sans text-sm text-stone-500">/ garrafa</span>
            </p>

            <div class="prose prose-stone max-w-none text-stone-600 mb-8">
                {!! nl2br(e($event->description)) !!}
            </div>

            <section class="border-y border-stone-200 py-6 mb-8">
                <p class="wine-kicker mb-4">Ficha técnica</p>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4 text-sm">
                    @if($event->producer)
                        <div><dt class="text-stone-500">Produtor</dt><dd class="font-semibold text-stone-900">{{ $event->producer }}</dd></div>
                    @endif
                    @if($event->country)
                        <div><dt class="text-stone-500">País</dt><dd class="font-semibold text-stone-900">{{ $event->country }}</dd></div>
                    @endif
                    @if($event->wine_region)
                        <div><dt class="text-stone-500">Região</dt><dd class="font-semibold text-stone-900">{{ $event->wine_region }}</dd></div>
                    @endif
                    @if($event->winemaker)
                        <div><dt class="text-stone-500">Enólogo</dt><dd class="font-semibold text-stone-900">{{ $event->winemaker }}</dd></div>
                    @endif
                    @if($event->alcohol_percentage !== null)
                        <div><dt class="text-stone-500">Teor de álcool</dt><dd class="font-semibold text-stone-900">{{ number_format($event->alcohol_percentage, 1, ',', '.') }}%</dd></div>
                    @endif
                    @if($event->bottle_capacity)
                        <div><dt class="text-stone-500">Capacidade</dt><dd class="font-semibold text-stone-900">{{ $event->bottle_capacity }}</dd></div>
                    @endif
                    @if($event->grapes)
                        <div class="sm:col-span-2"><dt class="text-stone-500">Castas</dt><dd class="font-semibold text-stone-900">{{ $event->grapes }}</dd></div>
                    @endif
                </dl>
            </section>

            <div class="grid grid-cols-2 border-y border-stone-200 py-5 mb-8 text-center text-sm">
                <div><p class="wine-kicker mb-1">Origem</p><p>Portugal</p></div>
                <div><p class="wine-kicker mb-1">Entrega</p><p>2 a 4 dias</p></div>
            </div>

            @if($event->price == 0)
                <form action="{{ route('events.register', $event) }}" method="POST">
                    @csrf
                    <input type="hidden" name="seat_id" value="{{ $event->seats()->where('status', 'available')->value('id') }}">
                    <button type="submit" class="wine-button w-full py-4 rounded-lg font-semibold">Adicionar à seleção</button>
                </form>
            @else
                <form action="{{ route('cart.add', $event) }}" method="POST">
                    @csrf
                    <button type="submit" class="wine-button block w-full py-4 rounded-lg text-center font-semibold">Adicionar ao carrinho</button>
                </form>
            @endif
            <p class="text-xs text-stone-500 text-center mt-3">Pagamento seguro · Envio para todo o Portugal continental</p>
        </div>
    </div>

    @if($relatedEvents->isNotEmpty())
        <section class="mt-20">
            <p class="wine-kicker mb-2">Também pode gostar</p>
            <h2 class="font-serif text-3xl mb-6">Da mesma região</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                @foreach($relatedEvents as $related)
                    <a href="{{ route('events.show', $related) }}" class="bg-white border border-stone-200 p-3 flex gap-4 items-center hover:shadow-lg transition">
                        <img src="{{ $related->image ? asset('storage/' . $related->image) : 'https://images.unsplash.com/photo-1473973266408-ed4e27abdd47?auto=format&fit=crop&w=300&q=80' }}" class="w-20 h-24 object-cover" alt="{{ $related->title }}">
                        <div><p class="wine-kicker mb-1">{{ $related->category->name ?? 'Seleção' }}</p><p class="font-serif text-lg">{{ $related->title }}</p><p class="text-sm text-[#5b1820] mt-1">{{ number_format($related->sale_price, 2, ',', '.') }} €</p></div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection
