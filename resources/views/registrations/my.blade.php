@extends('layouts.app')

@section('content')

<h1 class="text-3xl font-bold mb-8">
    As minhas encomendas
</h1>

@if($registrationsByEvent->isEmpty())

<p class="text-gray-600">
    Ainda não tem encomendas.
</p>

@else

    <div   div class="space-y-10">

        @foreach($registrationsByEvent as $eventRegistrations)
            @php
                $event = $eventRegistrations->first()->event;
            @endphp

            <section>
                <h2 class="text-2xl font-bold mb-4">
                    {{ $event->title }}
                </h2>

                <div class="space-y-6">
                    @foreach($eventRegistrations as $registration)

                        <div class="bg-white shadow-lg rounded-xl p-6 flex flex-col md:flex-row items-center gap-6">

                            <div class="bg-gray-100 p-4 rounded-lg text-center">
                                <p class="text-sm text-gray-500 mb-2">
                                    Meu QR Code
                                </p>

                                <img
                                    src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(route('checkin', $registration->ticket_token)) }}"
                                    alt="QR Code da inscrição"
                                    class="w-32 h-32"
                                >
                            </div>

                            <div class="flex-1">
                                <h3 class="text-xl font-bold mb-2">
                                    {{ $registration->event->title }}
                                </h3>

                                <p class="text-gray-600">
                                    Data: {{ $registration->event->date->format('d/m/Y H:i') }}
                                </p>

                                <p class="text-gray-600">
                                    Local: {{ $registration->event->city }}
                                </p>

                                @if($registration->seat)
                                    <p class="text-gray-600">
                                        Lugar: {{ $registration->seat->label }}
                                    </p>
                                @endif

                                <div class="mt-3">
                                    <span class="px-3 py-1 rounded text-white
                                        {{ $registration->status === 'confirmed'
                                            ? 'bg-green-500'
                                            : 'bg-yellow-500' }}">
                                        {{ ucfirst($registration->status) }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex flex-col gap-3">
                                <a href="{{ route('registrations.show', $registration) }}"
                                   class="bg-blue-600 text-white px-5 py-3 rounded-lg hover:bg-blue-700 text-center">
                                    Ver encomenda
                                </a>

                                <a href="{{ route('events.show', $registration->event) }}"
                                   class="bg-yellow-500 text-white px-5 py-3 rounded-lg hover:bg-yellow-600 text-center">
                                    Ver produto
                                </a>
                            </div>

                        </div>

                    @endforeach
                </div>
            </section>

        @endforeach

    </div>

@endif

@endsection
