@extends('layouts.app')

@section('content')

<h1 class="text-3xl font-bold mb-8">
    As minhas compras
</h1>

@if($registrationsByEvent->isEmpty())

<p class="text-gray-600">
    Ainda não tem compras.
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

                            <div class="flex-1">
                                <h3 class="text-xl font-bold mb-2">
                                    {{ $registration->event->title }}
                                </h3>

                                <p class="text-gray-600">
                                    Compra: {{ $registration->created_at->format('d/m/Y H:i') }}
                                </p>

                                <p class="text-gray-600">
                                    Total: {{ number_format($registration->event->sale_price, 2, ',', '.') }} €
                                </p>

                                <p class="text-gray-500 text-sm mt-1">
                                    Referência #{{ $registration->id }}
                                </p>

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
                                    Ver recibo
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
