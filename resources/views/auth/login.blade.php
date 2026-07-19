<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Correo Electrónico" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" value="Contraseña" />

            <div class="relative mt-1">
                <x-text-input id="password" class="block w-full pr-12"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />
                <button type="button" onclick="togglePasswordVisibility('password', 'eye-icon-login')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-brand-primary focus:outline-none">
                    <svg id="eye-icon-login" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-brand-primary focus:ring-brand-primary shadow-sm" name="remember">
                <span class="ms-2 text-sm text-gray-600">Recordarme</span>
            </label>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mt-6">
            @if (Route::has('password.request'))
                <a class="underline text-xs text-gray-600 hover:text-gray-900 rounded-md focus:outline-none" href="{{ route('password.request') }}">
                    ¿Olvidaste tu contraseña?
                </a>
            @endif

            <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                <a class="underline text-xs text-brand-primary hover:text-brand-primary-hover font-extrabold rounded-md focus:outline-none" href="{{ route('register') }}">
                    Crear Cuenta
                </a>

                <x-primary-button>
                    Ingresar
                </x-primary-button>
            </div>
        </div>
    </form>
</x-guest-layout>
