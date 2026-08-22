@extends('layouts.app')

@section('fullwidth')
    <div class="relative min-h-screen w-full">

        <div class="absolute inset-0 overflow-hidden">
            <img src="{{ asset('images/hero.jpg') }}" class="w-full h-full object-cover object-center scale-110">
        </div>
@endsection

@section('content')
        <div class="relative z-10 px-6 py-12">

            <a href="{{ route('events.index') }}" class="text-white/80 hover:text-white mb-6 inline-block">
                ← Voltar
            </a>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 max-w-6xl mx-auto items-stretch">

                <div class="lg:col-span-2 bg-white rounded-2xl shadow-xl overflow-hidden h-full flex flex-col">

                    <img src="{{ asset('storage/' . $event->image) }}" class="w-full h-44 object-cover">

                    <div class="px-4 py-2 border-b bg-gray-50">
                        <p class="text-xs text-gray-500 mb-3">
                            🎤 Line-up do Evento
                        </p>

                        <div class="flex gap-3 flex-wrap">

                            @forelse($event->eventArtists as $eventArtist)
                                <div class="relative w-24 h-24 rounded-xl overflow-hidden shadow group">

                                    <img src="{{ asset('storage/' . $eventArtist->artist->image) }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition duration-300">

                                    <div class="absolute inset-0 bg-black/40"></div>

                                    @if ($eventArtist->is_headliner)
                                        <span
                                            class="absolute top-1 left-1 bg-yellow-400 text-black text-[9px] px-1 rounded">
                                            ⭐
                                        </span>
                                    @endif

                                    <div class="absolute bottom-0 w-full text-center p-1">
                                        <p class="text-[10px] text-white font-semibold truncate">
                                            {{ $eventArtist->artist->name }}
                                        </p>
                                    </div>

                                </div>

                            @empty
                                <p class="text-xs text-gray-400">Sem artistas associados</p>
                            @endforelse

                        </div>
                    </div>
                    <div class="px-4 py-4 border-b bg-white">

                        <h3 class="text-sm font-semibold text-gray-700 mb-3">
                            ✨ Experiência do Evento
                        </h3>

                        <div class="grid grid-cols-3 gap-2">

                            @forelse($event->images as $image)
                                <img src="{{ asset('storage/' . $image->image) }}"
                                    class="w-full h-20 object-cover rounded-lg hover:scale-105 transition">

                            @empty

                                <p class="text-xs text-gray-400 col-span-3">
                                    Sem imagens disponíveis
                                </p>
                            @endforelse

                        </div>

                    </div>

                    <div class="px-4 py-4 border-t bg-white">

                        <h3 class="text-sm font-semibold text-gray-700 mb-4">
                            🔥 Eventos Relacionados
                        </h3>

                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">

                            @forelse($relatedEvents as $related)
                                <a href="{{ route('events.show', $related) }}"
                                    class="bg-gray-50 rounded-xl overflow-hidden hover:shadow-md transition block">

                                    <img src="{{ $related->image ? asset('storage/' . $related->image) : asset('images/default.jpg') }}"
                                        class="w-full h-20 object-cover">

                                    <div class="p-2">

                                        <p class="text-xs font-semibold text-gray-800 truncate">
                                            {{ $related->title }}
                                        </p>

                                        <p class="text-[10px] text-gray-500">
                                            📅 {{ $related->date->format('d/m') }}
                                        </p>

                                        <p class="text-[10px] text-gray-500">
                                            📍 {{ $related->city }}
                                        </p>

                                    </div>

                                </a>

                            @empty

                                <p class="text-xs text-gray-400 col-span-3 text-center">
                                    Sem eventos relacionados
                                </p>
                            @endforelse

                        </div>

                    </div>
                </div>




                <div class="bg-white rounded-2xl shadow-xl p-6 h-full sticky top-6 text-black flex flex-col">

                    {{-- 🔥 POPUP DE ERRO INJETADO AQUI (Aparece automaticamente se o utilizador já tiver bilhete) --}}
                    @if (session('error'))
                        <dialog id="errorModal" class="modal">
                            <div class="modal-box bg-white text-center rounded-2xl shadow-2xl p-8 max-w-sm mx-auto">
                                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                </div>
                                <h3 class="font-bold text-xl text-gray-900 mb-2">Atenção!</h3>
                                <p class="text-gray-600 mb-6">{{ session('error') }}</p>
                                <div class="flex justify-center">
                                    <button onclick="document.getElementById('errorModal').close()" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition w-full font-medium">
                                        Entendido
                                    </button>
                                </div>
                            </div>
                        </dialog>

                        <script>
                            document.addEventListener("DOMContentLoaded", function() {
                                const modal = document.getElementById('errorModal');
                                if (modal) {
                                    modal.showModal();
                                }
                            });
                        </script>
                    @endif

                    @if($event->price == 0)
                        <form action="{{ route('events.register', $event) }}" method="POST">
                            @csrf

                            <button type="submit"
                                class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 mb-4">
                                Inscrever-me
                            </button>
                        </form>
                    @else
                        <button type="button" onclick="ticketModal.showModal()"
                            class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 mb-4">
                            Comprar Bilhete
                        </button>
                    @endif

                    <dialog id="ticketModal" class="modal">
                        <div class="modal-box max-w-2xl bg-white text-gray-900 rounded-2xl shadow-2xl">

                            <form id="checkoutForm" action="{{ route('checkout', $event->id ?? 1) }}" method="GET">
                            
                                <input type="hidden" name="event_id" value="{{ $event->id ?? 1 }}">

                                <div class="flex justify-between items-center mb-6">
                                    <h2 class="text-lg font-semibold">
                                        {{ $event->price == 0 ? 'Inscrever-me' : 'Comprar Bilhete' }}
                                    </h2>
                                    <button type="button" onclick="ticketModal.close()" class="text-gray-400 hover:text-black">✕</button>
                                </div>

                                <div class="flex justify-between mb-6 text-sm">
                                    <span id="indicator-1" class="font-semibold text-black">1. Info</span>
                                    <span id="indicator-2" class="text-gray-400">2. Bilhete</span>
                                    <span id="indicator-3" class="text-gray-400">3. Pagamento</span>
                                </div>

                                <div id="step-1">
                                    <h3 class="font-semibold mb-4">Informação</h3>

                                    <input type="text" placeholder="Nome" name="buyer_name" required
                                        class="w-full border rounded-lg px-4 py-3 mb-3 focus:ring-2 focus:ring-black outline-none">

                                    <input type="email" placeholder="Email" name="buyer_email" required
                                        class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-black outline-none">
                                </div>

                                <div id="step-2" class="hidden">
                                    <h3 class="font-semibold mb-4">Escolher Bilhete</h3>

                                    @if (!$event->has_seats)
                                        @foreach ($event->ticketTypes as $ticket)
                                            <label class="flex justify-between items-center border rounded-xl p-4 mb-3 hover:border-black transition cursor-pointer has-[:checked]:border-black has-[:checked]:bg-gray-50">
                                                <div class="flex items-center gap-3">
                                                    <input type="radio" name="ticket_id" value="{{ $ticket->id }}" class="w-4 h-4 text-black focus:ring-black" required>
                                                    <div>
                                                        <p class="font-medium">{{ $ticket->name }}</p>
                                                        <p class="text-xs text-gray-500">Disponível</p>
                                                    </div>
                                                </div>
                                                <p class="font-semibold">€ {{ $ticket->price }}</p>
                                            </label>
                                        @endforeach
                                    @else
                                        <p class="text-sm text-gray-500">
                                            Seleção de lugares (em breve)
                                        </p>
                                    @endif
                                </div>

                                <div id="step-3" class="hidden">
                                    <h3 class="font-semibold mb-4">Pagamento</h3>

                                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                                        <div class="flex items-center gap-3">
                                            <svg class="w-8 h-8 text-indigo-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14.5v-1.5H9c-.55 0-1-.45-1-1v-3c0-.55.45-1 1-1h4v-1.5H9v-2h2v-1.5h2v1.5h2c.55 0 1 .45 1 1v3c0 .55-.45 1-1 1h-4v1.5h2v2h-2z"/></svg>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-800">
                                                    Processamento Seguro via Stripe
                                                </p>
                                                <p class="text-xs text-gray-500">
                                                    Serás redirecionado para concluir o pagamento.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-8 flex justify-between items-center">

                                    <button type="button" onclick="prevStep()" class="text-gray-500 hover:text-black transition">
                                        ← Voltar
                                    </button>

                                    <button type="button" id="btnContinuar" onclick="nextStep()"
                                        class="bg-black text-white px-6 py-2 rounded-lg hover:bg-gray-800 transition">
                                        Continuar
                                    </button>

                                </div>

                            </form>
                        </div>
                    </dialog>
                    
                    <script>
                        let step = 1;

                        function updateIndicators() {
                            document.getElementById('indicator-1').className = step === 1 ? 'font-semibold text-black' : 'text-gray-400';
                            document.getElementById('indicator-2').className = step === 2 ? 'font-semibold text-black' : 'text-gray-400';
                            document.getElementById('indicator-3').className = step === 3 ? 'font-semibold text-black' : 'text-gray-400';
                        }

                        function showStep() {
                            document.getElementById('step-1').classList.toggle('hidden', step !== 1);
                            document.getElementById('step-2').classList.toggle('hidden', step !== 2);
                            document.getElementById('step-3').classList.toggle('hidden', step !== 3);
                            
                            updateIndicators();

                            const btnContinuar = document.getElementById('btnContinuar');
                            if (step === 3) {
                                btnContinuar.innerText = 'Pagar com Stripe';
                            } else {
                                btnContinuar.innerText = 'Continuar';
                            }
                        }

                        function nextStep() {
                            if (step === 3) {
                                document.getElementById('checkoutForm').submit();
                            } else if (step < 3) {
                                step++;
                                showStep();
                            }
                        }

                        function prevStep() {
                            if (step > 1) {
                                step--;
                                showStep();
                            }
                        }
                    </script>


                    <div class="mb-4 mt-6">
                        <p class="text-sm text-gray-500">Preço</p>

                        <p class="text-2xl font-bold text-blue-600">

                            {{-- 🔥 CASO 1: LUGARES MARCADOS --}}
                            @if ($event->has_seats)
                                @if ($event->price == 0)
                                    Gratuito
                                @else
                                    € {{ $event->price }}
                                @endif

                                {{-- 🔥 CASO 2: TIPOS DE BILHETE --}}
                            @else
                                @php
                                    $minPrice = $event->ticketTypes->min('price');
                                @endphp

                                @if (!$minPrice || $minPrice == 0)
                                    Gratuito
                                @else
                                    Desde € {{ $minPrice }}
                                @endif
                            @endif

                        </p>
                    </div>

                    <hr class="my-4 border-gray-200">

                    <div class="bg-gray-100 rounded-xl p-4">

                        <p class="text-xs text-gray-500 mb-2 uppercase tracking-wide">
                            📅 Dias do evento
                        </p>

                        <p class="text-sm font-semibold text-gray-800">
                            {{ $event->date->format('d M Y') }}
                        </p>

                        <p class="text-xs text-gray-500 mt-1">
                            {{ $event->date->format('H:i') }} • Abertura de portas
                        </p>

                    </div>
                    <hr class="my-4 border-gray-200">

                    <div class="bg-white border rounded-xl p-4">

                        <p class="text-xs text-gray-500 mb-3 uppercase tracking-wide">
                            🎫 Bilhetes disponíveis
                        </p>

                        <div class="space-y-2 text-sm">

                            {{-- 🔥 CASO 1: TEM LUGARES MARCADOS --}}
                            @if ($event->has_seats)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                                        <span>Bilhete Geral</span>
                                    </div>

                                    <span class="text-gray-500">
                                        {{ $event->seats()->where('status', 'available')->count() }}
                                    </span>
                                </div>

                                {{-- 🔥 CASO 2: TIPOS DE BILHETE --}}
                            @else
                                @forelse($event->ticketTypes as $ticket)
                                    <div class="flex items-center justify-between">

                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                                            <span>{{ $ticket->name }} - €{{ $ticket->price }}</span>
                                        </div>

                                        <span class="text-gray-500">
                                            {{ $ticket->quantity }}
                                        </span>

                                    </div>

                                @empty

                                    <p class="text-xs text-gray-400">
                                        Sem bilhetes disponíveis
                                    </p>
                                @endforelse
                            @endif

                        </div>

                    </div>

                    <hr class="my-4 border-gray-200">
                    <div class="mt-4 bg-gray-100 rounded-xl overflow-hidden">

                        <div class="p-3 border-b bg-white">
                            <p class="text-xs text-gray-500 uppercase tracking-wide">
                                📍 Localização
                            </p>

                            <p class="text-sm font-semibold text-gray-800">
                                {{ $event->address }} - {{ $event->city }}
                            </p>
                        </div>

                        @if ($event->latitude && $event->longitude)
                            <div class="w-full h-40">
                                <iframe class="w-full h-full" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                                    src="https://maps.google.com/maps?q={{ $event->latitude }},{{ $event->longitude }}&z=15&output=embed">
                                </iframe>
                            </div>
                        @else
                            <div class="p-4 text-sm text-gray-500">
                                Localização não disponível
                            </div>
                        @endif

                    </div>
                    <hr class="my-4 border-gray-200">
                    <div class="mt-4 bg-white border rounded-xl p-4 text-black">

                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-3">
                            ℹ️ Informações úteis
                        </p>

                        <div class="space-y-3 text-sm">

                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <p class="font-semibold">Como chegar</p>
                                    <p class="text-gray-500 text-xs">
                                        Transporte público, carro ou táxi até ao local do evento
                                    </p>
                                </div>
                                <span class="text-gray-400">›</span>
                            </div>

                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <p class="font-semibold">O que levar</p>
                                    <p class="text-gray-500 text-xs">
                                        Bilhete, documento de identificação e boa disposição
                                    </p>
                                </div>
                                <span class="text-gray-400">›</span>
                            </div>

                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <p class="font-semibold">Dicas do evento</p>
                                    <p class="text-gray-500 text-xs">
                                        Chegar cedo para evitar filas e garantir melhor experiência
                                    </p>
                                </div>
                                <span class="text-gray-400">›</span>
                            </div>

                        </div>

                    </div>
                </div>


            </div>

        </div>

@endsection