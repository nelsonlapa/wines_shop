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
        Olá <strong>{{ $registration->user->name }}</strong>, a tua encomenda de <strong>{{ $registration->event->title }}</strong> foi confirmada.
    </p>

    <div class="bg-gray-50 p-6 rounded-lg inline-block border border-dashed border-gray-300 mb-6">
        <p class="text-xs text-gray-500 mb-3 uppercase font-bold tracking-wider">O Teu QR Code de Acesso</p>
        <img 
            src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode(route('checkin', $registration->ticket_token)) }}" 
            class="mx-auto shadow-sm"
            alt="QR Code"
        >
        <p class="mt-3 text-sm font-mono text-gray-400">{{ $registration->ticket_token }}</p>
    </div>

    <div class="flex flex-col gap-3">
        <a href="{{ route('registrations.my') }}" class="bg-purple-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-purple-700 transition">
            Ver as minhas encomendas
        </a>
        <a href="{{ route('events.index') }}" class="text-gray-500 hover:text-gray-700 text-sm">
            Voltar à página inicial
        </a>
    </div>

</div>

@endsection