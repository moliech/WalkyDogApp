<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'WalkyDog') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-brand-dark antialiased bg-brand-bg relative overflow-x-hidden min-h-screen">
        <!-- Fondo de Líneas y Ondas Topográficas Vectoriales (Topography Contour Waves) -->
        <div class="fixed inset-0 pointer-events-none z-0 opacity-80 overflow-hidden">
            <svg class="w-full h-full text-brand-primary" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 900" preserveAspectRatio="xMidYMid slice" fill="none" stroke="currentColor" stroke-width="1.5">
                <!-- Ondas de la Esquina Superior Derecha -->
                <path d="M 900 -100 C 1100 100, 1300 200, 1500 50" stroke="rgba(224, 122, 95, 0.18)" />
                <path d="M 850 -100 C 1080 120, 1280 240, 1500 100" stroke="rgba(224, 122, 95, 0.16)" />
                <path d="M 800 -100 C 1050 140, 1250 280, 1500 150" stroke="rgba(224, 122, 95, 0.14)" />
                <path d="M 750 -100 C 1020 160, 1220 320, 1500 200" stroke="rgba(224, 122, 95, 0.12)" />
                <path d="M 700 -100 C 990 180, 1190 360, 1500 250" stroke="rgba(224, 122, 95, 0.10)" />

                <!-- Ondas Centrales Fluídas -->
                <path d="M -100 300 C 300 150, 700 450, 1500 200" stroke="rgba(255, 140, 50, 0.15)" />
                <path d="M -100 360 C 320 210, 720 510, 1500 260" stroke="rgba(255, 140, 50, 0.13)" />
                <path d="M -100 420 C 340 270, 740 570, 1500 320" stroke="rgba(255, 140, 50, 0.11)" />

                <!-- Ondas de la Esquina Inferior Izquierda -->
                <path d="M -100 600 C 200 750, 500 800, 800 1000" stroke="rgba(129, 178, 154, 0.22)" stroke-width="2" />
                <path d="M -100 660 C 220 800, 520 840, 830 1000" stroke="rgba(129, 178, 154, 0.18)" stroke-width="2" />
                <path d="M -100 720 C 240 850, 540 880, 860 1000" stroke="rgba(129, 178, 154, 0.14)" />
                <path d="M -100 780 C 260 900, 560 920, 890 1000" stroke="rgba(129, 178, 154, 0.10)" />
            </svg>
        </div>

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative z-10 px-4">
            <div class="mb-4 text-center">
                <a href="/" class="text-3xl font-black text-brand-dark no-underline tracking-tight flex items-center justify-center gap-2">
                    <svg class="w-8 h-8 text-brand-primary" viewBox="0 0 100 100" fill="currentColor">
                        <path d="M 50 43 C 35 43, 26 56, 28 70 C 30 82, 42 86, 50 86 C 58 86, 70 82, 72 70 C 74 56, 65 43, 50 43 Z"/>
                        <circle cx="24" cy="42" r="9"/>
                        <circle cx="39" cy="24" r="10.5"/>
                        <circle cx="61" cy="24" r="10.5"/>
                        <circle cx="76" cy="42" r="9"/>
                    </svg>
                    <span>WalkyDog</span>
                </a>
            </div>

            <!-- Caja de Login Redondeada (sm:rounded-3xl) -->
            <div class="w-full sm:max-w-md mt-4 px-8 py-8 bg-white border border-gray-100 shadow-2xl shadow-gray-200/60 sm:rounded-3xl relative z-10">
                {{ $slot }}
            </div>
        </div>
        
        <script>
            function togglePasswordVisibility(inputId, iconId) {
                const input = document.getElementById(inputId);
                const icon = document.getElementById(iconId);
                if (input && icon) {
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"></path>';
                    } else {
                        input.type = 'password';
                        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
                    }
                }
            }
        </script>
    </body>
</html>
