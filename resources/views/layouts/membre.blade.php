<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>{{ $title ?? 'Espace membre' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Style pour l'avatar en initiales */
        .avatar-initials-sm {
            width: 36px;
            height: 36px;
            background-color: #3182ce; /* Bleu */
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-weight: bold;
            font-size: 0.875rem;
            text-transform: uppercase;
            border: 2px solid white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="bg-gray-100">

@php
    $user = auth()->user();
    $initials = strtoupper(substr($user->name, 0, 1) . (explode(' ', $user->name)[1][0] ?? ''));
@endphp

<div class="min-h-screen flex">

    {{-- SIDEBAR --}}
    <aside class="w-64 bg-white border-r hidden md:flex flex-col">
        <div class="p-6 border-b flex items-center gap-3">
            {{-- OPTIONNEL : On peut aussi mettre les initiales ici à côté du titre --}}
            <div class="avatar-initials-sm" style="width: 30px; height: 30px; font-size: 0.7rem;">
                {{ $initials }}
            </div>
            <div>
                <h1 class="text-lg font-bold text-gray-900 leading-none">AEEJ</h1>
                <p class="text-xs text-gray-500">Espace membre</p>
            </div>
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
                {{-- Nom de l'utilisateur (optionnel pour la clarté) --}}
                <span class="text-sm font-medium text-gray-700 hidden sm:inline">{{ $user->name }}</span>

                {{-- Cloche --}}
                <button class="relative text-xl">
                    <span class="absolute top-0 right-0 h-2 w-2 bg-red-500 rounded-full border border-white"></span>
                    🔔
                </button>

                {{-- REMPLACEMENT DE L'IMAGE PAR LES INITIALES --}}
                <div class="avatar-initials-sm">
                    {{ $initials }}
                </div>
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