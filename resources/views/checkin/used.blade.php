@extends('layouts.app')

@section('content')

<div class="max-w-xl mx-auto bg-yellow-100 border border-yellow-400 p-8 rounded-lg text-center">

<h1 class="text-3xl font-bold text-yellow-700">
⚠ Bilhete já utilizado
</h1>

<p class="mt-4">
{{ $registration->user->name }}
</p>

<p>
Evento: {{ $registration->event->title }}
</p>

</div>

@endsection
