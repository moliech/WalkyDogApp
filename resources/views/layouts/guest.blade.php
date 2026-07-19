<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
        <body class="font-sans text-brand-dark antialiased bg-brand-bg">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div class="mb-4">
                <a href="/" class="text-3xl font-black text-brand-dark no-underline tracking-tight">
                    WalkyDog
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-8 py-8 bg-white border border-gray-100 shadow-xl shadow-gray-100/40 overflow-hidden sm:rounded-2xl">
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
                        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d=\"M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.024 10.024 0 014.168-5.263M13.875 18.825l3.524 3.524m-3.524-3.524a8 8 0 11-8-8L12 12m4.88-4.88a9.961 9.961 0 014.662 4.88c-1.274 4.057-5.064 7-9.542 7-1.447 0-2.825-.303-4.08-.853m0 0L7.12 7.12m0 0A8.003 8.003 0 0112 4c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-2.224 3.618\"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d=\"M3 3l18 18\"></path>';
                    } else {
                        input.type = 'password';
                        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d=\"M15 12a3 3 0 11-6 0 3 3 0 016 0z\"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d=\"M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z\"></path>';
                    }
                }
            }
        </script>
    </body>
</html>
