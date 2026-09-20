@extends('layouts.app')

@section('content')

<div class="max-w-xl mx-auto bg-white shadow-lg border border-gray-200 p-8 rounded-xl text-center">

    <div class="mb-4 text-green-500">
        <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
    </div>

    <h1 class="text-3xl font-bold text-gray-800 mb-2">
        Compra Confirmada!
    </h1>

    <p class="text-gray-600 mb-6">
        Olá <strong>{{ $registrations->first()->user->name }}</strong>, a tua compra foi confirmada. Obrigado por escolheres a Aroma Nobre.
    </p>

    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 mb-6 text-left">
        <div class="flex justify-between gap-4 border-b border-gray-200 pb-4 mb-4">
            <div>
                <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Recibo de compra</p>
                <p class="text-sm text-gray-700 mt-1">Referências: {{ $registrations->pluck('id')->map(fn ($id) => '#' . $id)->join(', ') }}</p>
            </div>
            <p class="text-sm text-gray-500">{{ $registration->created_at->format('d/m/Y H:i') }}</p>
        </div>
        @foreach($registrations->groupBy('event_id') as $productRegistrations)
            @php($product = $productRegistrations->first()->event)
            <div class="flex justify-between gap-4 {{ !$loop->first ? 'border-t border-gray-200 mt-4 pt-4' : '' }}">
                <div>
                    <p class="font-semibold text-gray-800">{{ $product->title }}</p>
                    <p class="text-sm text-gray-500 mt-1">{{ $productRegistrations->count() }} garrafa(s)</p>
                </div>
                <p class="font-semibold text-[#5b1820]">{{ number_format($product->sale_price * $productRegistrations->count(), 2, ',', '.') }} €</p>
            </div>
            @if($product->discount_percentage > 0)
                <p class="text-sm text-emerald-700 mt-2">Desconto aplicado: {{ rtrim(rtrim(number_format($product->discount_percentage, 2, ',', '.'), '0'), ',') }}%</p>
            @endif
        @endforeach
        <div class="flex justify-between border-t border-gray-200 mt-4 pt-4 font-bold text-gray-800">
            <span>Total pago</span>
            <span>{{ number_format($registrations->sum(fn ($registration) => $registration->event->sale_price), 2, ',', '.') }} €</span>
        </div>
    </div>

    <div class="flex flex-col gap-3">
        <a href="{{ route('registrations.my') }}" class="bg-purple-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-purple-700 transition">
            Ver as minhas compras
        </a>
        <a href="{{ route('events.index') }}" class="text-gray-500 hover:text-gray-700 text-sm">
            Continuar a comprar
        </a>
    </div>

</div>

@endsection