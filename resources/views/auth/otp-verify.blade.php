<x-guest-layout>
    <h3 class="text-lg font-black text-brand-dark mb-2">Verificación de Código</h3>
    <p class="text-xs text-gray-500 mb-6 leading-relaxed">Ingresa el código de 6 dígitos enviado a tu correo electrónico para completar el acceso seguro.</p>

    @if(session('status'))
        <div class="mb-4 p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-sm text-emerald-600 font-medium">
            {{ session('status') }}
        </div>
    @endif

    <form action="{{ route('otp.verify.post') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block font-medium text-sm text-gray-700" for="code">Código de Seguridad</label>
            <input class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 transition duration-200 outline-none text-brand-dark bg-white shadow-sm block mt-1" 
                   id="code" type="text" name="code" required autofocus maxlength="6" autocomplete="off" placeholder="######" style="text-align: center; font-size: 20px; font-weight: 800; letter-spacing: 4px;">
            @error('code') 
                <span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span> 
            @enderror
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full inline-flex justify-center items-center px-5 py-2.5 bg-brand-primary hover:bg-brand-primary-hover text-white font-extrabold text-sm rounded-xl shadow-sm hover:shadow-lg hover:shadow-brand-primary/20 hover:-translate-y-0.5 transition duration-200 cursor-pointer">
                Verificar Código
            </button>
        </div>
    </form>

    <form action="{{ route('otp.resend') }}" method="POST" class="mt-4 text-center border-t border-gray-50 pt-4">
        @csrf
        <button type="submit" class="text-xs text-brand-primary hover:text-brand-primary-hover font-extrabold transition duration-200 cursor-pointer bg-transparent border-0 outline-none">
            ¿No recibiste el correo? Reenviar código
        </button>
    </form>
</x-guest-layout>