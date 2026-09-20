@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-8">
    <div class="mb-10">
        <p class="wine-kicker mb-2">A sua seleção</p>
        <h1 class="font-serif text-5xl text-stone-900">Carrinho</h1>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-5 py-4 text-emerald-800">{{ session('success') }}</div>
    @endif

    @if ($products->isEmpty())
        <div class="bg-white border border-stone-200 p-12 text-center">
            <p class="font-serif text-2xl text-stone-900 mb-3">O seu carrinho está vazio.</p>
            <p class="text-stone-500 mb-6">Encontre uma garrafa para acompanhar o próximo momento especial.</p>
            <a href="{{ route('events.index') }}" class="wine-button inline-block rounded-lg px-6 py-3 font-semibold">Explorar catálogo</a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-8 items-start">
            <div class="space-y-4">
                @foreach ($products as $product)
                    <div class="bg-white border border-stone-200 p-4 flex gap-5 items-center">
                        <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://images.unsplash.com/photo-1506377247377-2a5b3b417ebb?auto=format&fit=crop&w=300&q=80' }}" class="w-24 h-28 object-cover" alt="{{ $product->title }}">
                        <div class="flex-1">
                            <p class="wine-kicker mb-1">{{ $product->category->name ?? 'Vinho português' }}</p>
                            <h2 class="font-serif text-xl text-stone-900">{{ $product->title }}</h2>
                            <p class="text-sm text-[#5b1820] font-bold mt-2">
                                @if($product->discount_percentage > 0)<span class="text-stone-400 price-old mr-1">{{ number_format($product->price ?: 12.50, 2, ',', '.') }} €</span>@endif
                                {{ number_format($product->sale_price, 2, ',', '.') }} €
                            </p>
                        </div>
                        <form action="{{ route('cart.remove', $product) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm text-stone-500 hover:text-[#5b1820]">Remover</button>
                        </form>
                    </div>
                @endforeach
            </div>
            <aside class="bg-white border border-stone-200 p-6">
                <h2 class="font-serif text-2xl mb-6">Resumo</h2>
                <div class="flex justify-between border-b border-stone-200 pb-4 mb-5 text-stone-600"><span>{{ $products->count() }} artigo(s)</span><span>{{ number_format($total, 2, ',', '.') }} €</span></div>
                @if ($products->count() === 1)
                    <a href="{{ route('checkout', ['event_id' => $products->first()->id]) }}" class="wine-button block rounded-lg py-3 text-center font-semibold">Finalizar compra</a>
                @else
                    <p class="text-xs text-stone-500">Selecione uma garrafa de cada vez para finalizar o pagamento.</p>
                @endif
            </aside>
        </div>
    @endif
</div>
@endsection
