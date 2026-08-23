<x-guest-layout>
    <div class="w-full max-w-lg mx-auto">

        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-gray-900">
                Criar conta
            </h1>

            <p class="mt-2 text-sm text-gray-600">
                Cria a tua conta para comprar vinhos e acompanhar as tuas encomendas.
            </p>
        </div>

        <div class="bg-white shadow-xl rounded-2xl p-8 border border-gray-100">
            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <div>
                    <x-input-label for="name" value="Nome" />

                    <x-text-input
                        id="name"
                        class="block mt-2 w-full"
                        type="text"
                        name="name"
                        :value="old('name')"
                        required
                        autofocus
                        autocomplete="name"
                        placeholder="O seu nome completo"
                    />

                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="email" value="Email" />

                    <x-text-input
                        id="email"
                        class="block mt-2 w-full"
                        type="email"
                        name="email"
                        :value="old('email')"
                        required
                        autocomplete="username"
                        placeholder="exemplo@email.com"
                    />

                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="role_id" value="Tipo de utilizador" />

                    <select
                        id="role_id"
                        name="role_id"
                        class="block mt-2 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        required
                    >
                        <option value="">
                            Selecione o tipo de utilizador
                        </option>

                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>

                    <x-input-error :messages="$errors->get('role_id')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="password" value="Palavra-passe" />

                    <x-text-input
                        id="password"
                        class="block mt-2 w-full"
                        type="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        placeholder="Crie uma palavra-passe segura"
                    />

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="password_confirmation" value="Confirmar palavra-passe" />

                    <x-text-input
                        id="password_confirmation"
                        class="block mt-2 w-full"
                        type="password"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="Repita a palavra-passe"
                    />

                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <button
                    type="submit"
                    class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition"
                >
                    Criar conta
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Já tem conta?

                    <a href="{{ route('login') }}"
                       class="font-semibold text-blue-600 hover:text-blue-800">
                        Entrar
                    </a>
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>