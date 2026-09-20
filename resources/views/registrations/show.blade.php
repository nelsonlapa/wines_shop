@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto bg-white shadow-lg rounded-xl p-8 text-center">

    <h1 class="text-3xl font-bold text-green-600 mb-4">
        Encomenda confirmada
    </h1>

    <p class="text-gray-600 mb-6">
        A sua encomenda foi realizada com sucesso.
    </p>

    <h2 class="text-xl font-bold mb-2">
        {{ $registration->event->title }}
    </h2>

    <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 text-left max-w-xl mx-auto">
        <div class="flex justify-between gap-4 border-b border-gray-200 pb-4 mb-4">
            <span class="text-sm text-gray-500">Referência #{{ $registration->id }}</span>
            <span class="text-sm text-gray-500">{{ $registration->created_at->format('d/m/Y H:i') }}</span>
        </div>
        <div class="flex justify-between gap-4">
            <span class="font-semibold text-gray-800">{{ $registration->event->title }}</span>
            <span class="font-semibold text-[#5b1820]">{{ number_format($registration->event->sale_price, 2, ',', '.') }} €</span>
        </div>
        @if($registration->event->discount_percentage > 0)
            <p class="text-sm text-emerald-700 mt-3">Desconto aplicado: {{ rtrim(rtrim(number_format($registration->event->discount_percentage, 2, ',', '.'), '0'), ',') }}%</p>
        @endif
        <div class="flex justify-between border-t border-gray-200 mt-4 pt-4 font-bold text-gray-800">
            <span>Total pago</span>
            <span>{{ number_format($registration->event->sale_price, 2, ',', '.') }} €</span>
        </div>
    </div>

    <div class="mt-8">
        <a href="{{ route('registrations.my') }}"
           class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
            Ver as minhas compras
        </a>
    </div>

</div>

@endsection
