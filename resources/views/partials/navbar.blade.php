<nav class="absolute top-0 left-0 w-full z-50 py-6">
    <div class="max-w-7xl mx-auto px-6">
        <div class="backdrop-blur-md bg-white/10 border border-white/10 rounded-2xl px-6 py-5 flex items-center justify-between gap-6">

            <div class="flex items-center gap-5">


                <a href="{{ route('events.index') }}"
                   class="text-2xl font-bold text-white whitespace-nowrap">
                    <span class="text-purple-600">Event</span>Manager
                </a>
            </div>

            <div class="h-10 w-px bg-gray-200 hidden lg:block"></div>

            <div class="hidden lg:flex items-center gap-8 text-white/90 font-medium">
                <a href="#"
                   class="inline-flex items-center gap-2 hover:text-purple-300 transition">
                    <span>▣</span>
                    <span>Shows</span>
                </a>

                <a href="#"
                   class="inline-flex items-center gap-2 hover:text-purple-300 transition">
                    <span>☆</span>
                    <span>Festivais</span>
                </a>

                <a href="#"
                   class="inline-flex items-center gap-2 hover:text-purple-300 transition">
                    <span>🏆</span>
                    <span>Desporto</span>
                </a>

                <a href="#"
                   class="inline-flex items-center gap-2 hover:text-purple-300 transition">
                    <span>▤</span>
                    <span>Blog</span>
                </a>
            </div>

            <div class="h-10 w-px bg-gray-200 hidden xl:block"></div>

            <div class="flex items-center gap-4">
                @auth
                    @if(auth()->user()->role && in_array(auth()->user()->role->name, ['admin', 'organizador']))
                        <a href="{{ route('checkin.scanner') }}"
                           class="inline-flex items-center gap-2 border border-green-200 bg-green-50 text-green-700 px-5 py-3 rounded-full hover:bg-green-100 transition font-semibold whitespace-nowrap">
                            <span>▦</span>
                            <span>Validar QR</span>
                        </a>
                        <a href="{{ url('/welldone') }}"
                            class="inline-flex items-center gap-2 border border-green-200 bg-green-50 text-green-700 px-5 py-3 rounded-full hover:bg-green-100 transition font-semibold whitespace-nowrap">

                            <span>🛠</span>
                            <span>Painel Administrativo</span>
                        </a>
                    @endif

                    <a href="{{ route('registrations.my') }}"
                       class="inline-flex items-center gap-2 border border-purple-200 bg-purple-50 text-purple-700 px-5 py-3 rounded-full hover:bg-purple-100 transition font-semibold whitespace-nowrap">
                        <span>🎟</span>
                        <span>Os Meus Bilhetes</span>
                    </a>

                    <div class="h-10 w-px bg-gray-200 hidden xl:block"></div>

                    <a href="#"
                       class="inline-flex items-center gap-3 border border-gray-200 bg-white px-4 py-2 rounded-full hover:bg-gray-50 transition shadow-sm whitespace-nowrap">
                        <span class="w-8 h-8 rounded-full bg-purple-600 text-white flex items-center justify-center">
                            👤
                        </span>

                        <span class="text-gray-700 font-medium">
                            {{ auth()->user()->name }}
                        </span>

                        <span class="text-gray-500">
                            ▾
                        </span>
                    </a>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf

                        <button type="submit"
                                class="inline-flex items-center gap-2 border border-red-200 bg-red-50 text-red-600 px-5 py-3 rounded-full hover:bg-red-100 transition font-semibold whitespace-nowrap">
                            <span>↪</span>
                            <span>Logout</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="text-white/90 hover:text-purple-300 font-medium transition">
                        Login
                    </a>

                    <a href="{{ route('register') }}"
                       class="bg-purple-600 text-white px-5 py-3 rounded-full hover:bg-purple-700 transition font-semibold shadow-sm">
                        Criar Conta
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
