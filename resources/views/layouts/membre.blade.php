<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>{{ $title ?? 'Espace membre' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">

<div class="min-h-screen flex">

    {{-- SIDEBAR --}}
    <aside class="w-64 bg-white border-r hidden md:flex flex-col">
        <div class="p-6 border-b">
            <h1 class="text-lg font-bold text-gray-900">AEEJ</h1>
            <p class="text-sm text-gray-500">Espace membre</p>
        </div>

        <nav class="flex-1 p-4 space-y-1">
            <a href="{{ route('dashboard') }}"
               class="block px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('dashboard') ? 'bg-gray-100 font-semibold' : '' }}">
                Dashboard
            </a>

            <a href="{{ route('membre.annonces.index') }}"
               class="block px-4 py-2 rounded-lg hover:bg-gray-100">
                Annonces
            </a>

            <a href="{{ route('profile.edit') }}"
               class="block px-4 py-2 rounded-lg hover:bg-gray-100">
                Profil
            </a>
        </nav>

        <div class="p-4 border-t">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full text-left px-4 py-2 rounded-lg hover:bg-red-50 text-red-600">
                    Déconnexion
                </button>
            </form>
        </div>
    </aside>

    {{-- CONTENU --}}
    <div class="flex-1 flex flex-col">

        {{-- HEADER --}}
        <header class="bg-white border-b px-6 py-4 flex justify-between items-center">
            <h2 class="font-semibold text-lg text-gray-800">
                {{ $header ?? 'Dashboard' }}
            </h2>

            <div class="flex items-center gap-4">
                {{-- Cloche (future notification) --}}
                <button class="relative">
                    <span class="absolute -top-1 -right-1 h-2 w-2 bg-red-500 rounded-full"></span>
                    🔔
                </button>

                {{-- Avatar --}}
                <img
                    class="h-9 w-9 rounded-full object-cover"
                    src="{{ auth()->user()->profile_photo_path
                        ? asset('storage/' . auth()->user()->profile_photo_path)
                        : asset('images/default-avatar.png') }}"
                >
            </div>
        </header>

        {{-- PAGE --}}
        <main class="flex-1 p-6">
            {{ $slot }}
        </main>

    </div>
</div>

</body>
</html>
