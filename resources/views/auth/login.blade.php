<x-guest-layout>
    <div class="w-full max-w-md mx-auto">

        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-gray-900">
                Entrar na conta
            </h1>

            <p class="mt-2 text-sm text-gray-600">
                Acede à tua área pessoal para acompanhar as tuas encomendas e favoritos.
            </p>
        </div>

        <div class="bg-white shadow-xl rounded-2xl p-8 border border-gray-100">
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <x-input-label for="email" value="Email" />

                    <x-text-input
                        id="email"
                        class="block mt-2 w-full"
                        type="email"
                        name="email"
                        :value="old('email')"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="exemplo@email.com"
                    />

                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <div class="flex items-center justify-between">
                        <x-input-label for="password" value="Palavra-passe" />

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                               class="text-sm font-medium text-blue-600 hover:text-blue-800">
                                Esqueceu-se?
                            </a>
                        @endif
                    </div>

                    <x-text-input
                        id="password"
                        class="block mt-2 w-full"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="A sua palavra-passe"
                    />

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="flex items-center justify-between">
                    <label for="remember_me" class="inline-flex items-center">
                        <input
                            id="remember_me"
                            type="checkbox"
                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500"
                            name="remember"
                        >

                        <span class="ms-2 text-sm text-gray-600">
                            Manter sessão iniciada
                        </span>
                    </label>
                </div>

                <button
                    type="submit"
                    class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition"
                >
                    Entrar
                </button>
            </form>

            @if (Route::has('register'))
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Ainda não tem conta?

                        <a href="{{ route('register') }}"
                           class="font-semibold text-blue-600 hover:text-blue-800">
                            Criar conta
                        </a>
                    </p>
                </div>
            @endif
        </div>
    </div>
</x-guest-layout>