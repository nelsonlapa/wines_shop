@extends('layouts.app')

@section('content')
@php
    $first = $registrations->first();
    $productsTotal = $registrations->sum(fn ($item) => $item->event->sale_price);
    $shippingCost = $first->order?->shipping_cost ?? $first->shipping_cost ?? 0;
@endphp
<div class="max-w-3xl mx-auto py-8">
    <div class="bg-white border border-stone-200 p-8">
        <p class="wine-kicker mb-2">Recibo de compra</p>
        <h1 class="font-serif text-4xl text-stone-900 mb-2">Compra confirmada</h1>
        <p class="text-stone-500 mb-8">Referência {{ $first->order?->order_reference ?? $first->order_reference ?? 'AN-' . $first->id }} · {{ $first->order?->created_at?->format('d/m/Y H:i') ?? $first->created_at->format('d/m/Y H:i') }}</p>

        <div class="space-y-4 border-y border-stone-200 py-5">
            @foreach($registrations->groupBy('event_id') as $items)
                @php($product = $items->first()->event)
                <div class="flex justify-between gap-4">
                    <div><p class="font-semibold text-stone-800">{{ $product->title }}</p><p class="text-sm text-stone-500">{{ $items->count() }} garrafa(s)</p></div>
                    <p class="font-semibold text-[#5b1820] whitespace-nowrap">{{ number_format($product->sale_price * $items->count(), 2, ',', '.') }} €</p>
                </div>
            @endforeach
        </div>
        <div class="space-y-2 mt-5 text-stone-600">
            <div class="flex justify-between"><span>Produtos</span><span>{{ number_format($productsTotal, 2, ',', '.') }} €</span></div>
            <div class="flex justify-between"><span>Envio</span><span>{{ $shippingCost ? number_format($shippingCost, 2, ',', '.') . ' €' : 'Grátis' }}</span></div>
            <div class="flex justify-between border-t border-stone-200 pt-4 text-lg font-bold text-stone-900"><span>Total pago</span><span>{{ number_format($productsTotal + $shippingCost, 2, ',', '.') }} €</span></div>
        </div>
    </div>
    <a href="{{ route('registrations.my') }}" class="inline-block mt-6 text-sm font-semibold text-[#5b1820]">← Voltar às minhas compras</a>
</div>
@endsection
