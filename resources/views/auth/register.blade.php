<x-guest-layout>
    <div class="mb-4 text-center">
        <h2 class="text-2xl font-black text-brand-dark">Crear tu Cuenta 🐾</h2>
        <p class="text-sm text-gray-500 mt-1">Únete a la red de cuidado de mascotas más grande</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Nombres -->
        <div>
            <x-input-label for="nombres" :value="__('Nombres')" />
            <x-text-input id="nombres" class="block mt-1 w-full" type="text" name="nombres" :value="old('nombres')" required autofocus autocomplete="given-name" />
            <x-input-error :messages="$errors->get('nombres')" class="mt-2" />
        </div>

        <!-- Apellidos -->
        <div>
            <x-input-label for="apellidos" :value="__('Apellidos')" />
            <x-text-input id="apellidos" class="block mt-1 w-full" type="text" name="apellidos" :value="old('apellidos')" required autocomplete="family-name" />
            <x-input-error :messages="$errors->get('apellidos')" class="mt-2" />
        </div>

        <!-- Nombre de Usuario (Username) -->
        <div>
            <x-input-label for="username" :value="__('Nombre de usuario (único)')" />
            <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Correo Electrónico')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Teléfono -->
        <div>
            <x-input-label for="telefono" :value="__('Teléfono de Contacto')" />
            <x-text-input id="telefono" class="block mt-1 w-full" type="text" name="telefono" :value="old('telefono')" autocomplete="tel" />
            <x-input-error :messages="$errors->get('telefono')" class="mt-2" />
        </div>

        <!-- Dirección -->
        <div>
            <x-input-label for="direccion" :value="__('Dirección de Residencia')" />
            <x-text-input id="direccion" class="block mt-1 w-full" type="text" name="direccion" :value="old('direccion')" required autocomplete="street-address" />
            <x-input-error :messages="$errors->get('direccion')" class="mt-2" />
        </div>

        <!-- Selector de Rol (Propietario / Paseador) -->
        <div>
            <x-input-label for="rol" :value="__('Tipo de Cuenta')" />
            <select id="rol" name="rol" required class="block mt-1 w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 transition duration-200 outline-none text-brand-dark bg-white">
                <option value="propietario" {{ old('rol') == 'propietario' ? 'selected' : '' }}>Quiero registrar a mis mascotas</option>
                <option value="paseador" {{ old('rol') == 'paseador' ? 'selected' : '' }}>Quiero ser Paseador de Perros</option>
            </select>
            <x-input-error :messages="$errors->get('rol')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Contraseña')" />
            <div class="relative mt-1">
                <x-text-input id="password" class="block w-full pr-12" type="password" name="password" required autocomplete="new-password" />
                <button type="button" onclick="togglePasswordVisibility('password', 'eye-icon-reg-pass')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-brand-primary focus:outline-none">
                    <svg id="eye-icon-reg-pass" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" />
            <div class="relative mt-1">
                <x-text-input id="password_confirmation" class="block w-full pr-12" type="password" name="password_confirmation" required autocomplete="new-password" />
                <button type="button" onclick="togglePasswordVisibility('password_confirmation', 'eye-icon-reg-confirm')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-brand-primary focus:outline-none">
                    <svg id="eye-icon-reg-confirm" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between pt-4">
            <a class="underline text-sm text-gray-600 hover:text-brand-primary rounded-md focus:outline-none" href="{{ route('login') }}">
                ¿Ya tienes cuenta? Inicia sesión
            </a>

            <x-primary-button class="ms-4">
                Registrarse
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>