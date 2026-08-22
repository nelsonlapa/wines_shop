@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto bg-white shadow-lg rounded-xl p-8 text-center">

    <h1 class="text-3xl font-bold text-green-600 mb-4">
        Inscrição confirmada
    </h1>

    <p class="text-gray-600 mb-6">
        A sua inscrição no evento foi realizada com sucesso.
    </p>

    <h2 class="text-xl font-bold mb-2">
        {{ $registration->event->title }}
    </h2>

    <p class="text-gray-600 mb-1">
        Data: {{ $registration->event->date->format('d/m/Y H:i') }}
    </p>

    <p class="text-gray-600 mb-6">
        Local: {{ $registration->event->city }}
    </p>

    @if($registration->seat)
        <p class="text-gray-700 mb-6">
            Lugar: {{ $registration->seat->label }}
        </p>
    @endif

    <div class="inline-block bg-gray-100 p-6 rounded-xl">
        <p class="text-sm text-gray-500 mb-3">
            Apresente este QR Code à entrada
        </p>

        <img
            src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data={{ urlencode(route('checkin', $registration->ticket_token)) }}"
            alt="QR Code da inscrição"
            class="w-56 h-56 mx-auto"
        >
    </div>

    <div class="mt-8">
        <a href="{{ route('registrations.my') }}"
           class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
            Ver todas as minhas inscrições
        </a>
    </div>

</div>

@endsection
