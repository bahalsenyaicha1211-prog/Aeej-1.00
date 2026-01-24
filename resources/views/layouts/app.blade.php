<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'AEEJ'))</title>

    <!-- Fonts (Breeze) -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome (pour tes icônes inscription / footer / etc.) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Vite (Breeze + Tailwind) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- CSS spécifique page (ex: inscription.css via @section('styles')) -->
    @yield('styles')
</head>

<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex flex-col">

        {{-- Navigation Breeze (si tu veux la garder) --}}
        @include('layouts.navigation')

        {{-- Optionnel: header Breeze (si certaines pages Breeze l'utilisent) --}}
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        {{-- Contenu principal : compatible Blade classique ET Breeze --}}
        <main class="flex-1">
            @hasSection('content')
                @yield('content')
            @else
                {{ $slot ?? '' }}
            @endif
        </main>

        {{-- Footer simple (tu peux l’enrichir plus tard) --}}
        <footer class="mt-auto bg-gray-900 text-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                    <div>
                        <div class="font-semibold text-lg">AEEJ</div>
                        <div class="text-sm text-gray-400">Association des Étudiants Étrangers à Jendouba</div>
                    </div>

                    <div class="flex items-center gap-4">
                        <a class="hover:text-white" href="#" aria-label="Facebook"><i class="fa-brands fa-facebook"></i></a>
                        <a class="hover:text-white" href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                        <a class="hover:text-white" href="#" aria-label="Telegram"><i class="fa-brands fa-telegram"></i></a>
                        <a class="hover:text-white" href="#" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
                    </div>
                </div>

                <div class="border-t border-gray-800 mt-8 pt-6 text-sm text-gray-400">
                    © {{ date('Y') }} AEEJ — Tous droits réservés.
                </div>
            </div>
        </footer>
    </div>

    <!-- JS spécifique page (ex: inscription.js via @section('scripts')) -->
    @yield('scripts')
</body>
</html>
