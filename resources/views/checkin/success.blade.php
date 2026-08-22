@extends('layouts.app')

@section('content')

<div class="max-w-xl mx-auto bg-green-100 border border-green-400 p-8 rounded-lg text-center">

<h1 class="text-3xl font-bold text-green-700 mb-4">
✔ Entrada Validada
</h1>

<p class="text-lg">
<strong>{{ $registration->user->name }}</strong>
</p>

<p class="text-gray-700 mt-2">
Evento: {{ $registration->event->title }}
</p>

<p class="text-gray-700">
Hora: {{ now()->format('H:i') }}
</p>

</div>

@endsection
