<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 leading-relaxed">
        ¿Olvidaste tu contraseña? No hay problema. Dinós tu dirección de correo electrónico y te enviaremos un enlace para restablecer tu contraseña y que puedas elegir una nueva.
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Correo Electrónico" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none" href="{{ route('login') }}">
                Regresar al Login
            </a>
            
            <x-primary-button>
                Enviar Enlace
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
