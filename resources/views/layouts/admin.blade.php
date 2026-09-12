@php
    $user = auth()->user();

    $title  = trim($__env->yieldContent('title')) ?: 'Admin - AEEJ';
    $header = trim($__env->yieldContent('header')) ?: 'Tableau de bord Admin';

    $isDash      = request()->routeIs('admin.dashboard');
    $isAnnonces  = request()->routeIs('admin.annonces.*');
    $isActivites = request()->routeIs('admin.activites.*');
    $isBureau    = request()->routeIs('admin.bureau.*');
    $isMembres   = request()->routeIs('admin.membres.*');
    $isPays      = request()->routeIs('admin.pays.*');
    $isDeps      = request()->routeIs('admin.departements.*');
    $isMessages  = request()->routeIs('admin.messages.*');
@endphp

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>

    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @yield('styles')
    <script src="{{ asset('js/admin.js') }}" defer></script>
    <script src="{{ asset('js/password-toggle.js') }}" defer></script>
    <script src="{{ asset('js/select-search.js') }}" defer></script>
</head>
<body class="page">

<div class="layout">

    {{-- Sidebar desktop --}}
    <aside class="sidebar" aria-label="Navigation admin">
        <div class="sidebar__top">
            <div class="who">
                <x-avatar :user="$user" :size="44" bg="#2563eb" />
                <div class="who__meta">
                    <div class="who__name">{{ $user->name }}</div>
                    <div class="who__email">{{ $user->email }}</div>
                </div>
            </div>
        </div>

        <nav class="nav">
            <a class="nav__item {{ $isDash ? 'is-active' : '' }}" href="{{ route('admin.dashboard') }}"><span class="nav__icon"><x-icon name="dashboard"/></span>Dashboard</a>
            <a class="nav__item {{ $isAnnonces ? 'is-active' : '' }}" href="{{ route('admin.annonces.index') }}"><span class="nav__icon"><x-icon name="megaphone"/></span>Annonces</a>
            <a class="nav__item {{ $isActivites ? 'is-active' : '' }}" href="{{ route('admin.activites.index') }}"><span class="nav__icon"><x-icon name="calendar"/></span>Activités</a>
            <a class="nav__item {{ $isBureau ? 'is-active' : '' }}" href="{{ route('admin.bureau.index') }}"><span class="nav__icon"><x-icon name="briefcase"/></span>Bureau</a>

            <a class="nav__item {{ request()->routeIs('admin.galerie.*') ? 'is-active' : '' }}"
   href="{{ route('admin.galerie.index') }}"><span class="nav__icon"><x-icon name="image"/></span>Galerie</a>

            <a class="nav__item {{ request()->routeIs('admin.hero-images.*') ? 'is-active' : '' }}"
   href="{{ route('admin.hero-images.index') }}"><span class="nav__icon"><x-icon name="home"/></span>Accueil (photos)</a>

            <a class="nav__item {{ request()->routeIs('admin.partenaires*') ? 'is-active' : '' }}"
   href="{{ route('admin.partenaires.index') }}"><span class="nav__icon"><x-icon name="handshake"/></span>Partenaires</a>

            <a class="nav__item {{ $isMessages ? 'is-active' : '' }}"
   href="{{ route('admin.messages.index') }}"><span class="nav__icon"><x-icon name="mail"/></span>Messages</a>

            <div class="nav__sep"></div>

            <a class="nav__item {{ $isMembres ? 'is-active' : '' }}" href="{{ route('admin.membres.index') }}"><span class="nav__icon"><x-icon name="users"/></span>Membres</a>
            <a class="nav__item {{ $isDeps ? 'is-active' : '' }}" href="{{ route('admin.departements.index') }}"><span class="nav__icon"><x-icon name="tag"/></span>Départements</a>
            <a class="nav__item {{ $isPays ? 'is-active' : '' }}" href="{{ route('admin.pays.index') }}"><span class="nav__icon"><x-icon name="globe"/></span>Pays</a>

            @if($user->is_super_admin)
            <a class="nav__item {{ request()->routeIs('admin.admins.*') ? 'is-active' : '' }}"
             href="{{ route('admin.admins.index') }}">
            <span class="nav__icon"><x-icon name="shield"/></span>Admins
             </a>
            <a class="nav__item {{ request()->routeIs('admin.tresorerie-comptes.*') ? 'is-active' : '' }}"
             href="{{ route('admin.tresorerie-comptes.index') }}">
            <span class="nav__icon"><x-icon name="wallet"/></span>Trésorerie
             </a>
        @endif

            <div class="nav__sep"></div>

            <a class="nav__item" href="{{ route('dashboard') }}"><span class="nav__icon"><x-icon name="switch"/></span>Espace membre</a>

        </nav>

        <div class="sidebar__bottom">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn--primary btn--full" type="submit">Déconnexion</button>
            </form>
        </div>
    </aside>

    {{-- Main --}}
    <div class="main">

        {{-- Topbar --}}
        <header class="topbar">
            <div class="topbar__left">
                <button class="iconbtn" type="button" data-admin-drawer-open aria-label="Ouvrir le menu"><x-icon name="menu"/></button>
                <div class="topbar__title">{{ $header }}</div>
            </div>

            <div class="topbar__right">
                <a class="btn btn--ghost" href="{{ route('accueil') }}">Site public</a>
                <x-avatar :user="$user" :size="40" bg="#2563eb" />
            </div>
        </header>

        {{-- Flash --}}
        <div class="flashwrap">
            @if(session('success'))
                <div class="flash flash--success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="flash flash--error">{{ session('error') }}</div>
            @endif
        </div>

        <main class="content">
            @yield('content')
        </main>
    </div>
</div>

{{-- Drawer mobile --}}
<div class="drawer" data-admin-drawer>
    <div class="drawer__backdrop" data-admin-drawer-close></div>
    <div class="drawer__panel" role="dialog" aria-modal="true" aria-label="Menu admin">

        <div class="drawer__top">
            <div class="who">
                <x-avatar :user="$user" :size="44" bg="#2563eb" />
                <div class="who__meta">
                    <div class="who__name">{{ $user->name }}</div>
                    <div class="who__email">{{ $user->email }}</div>
                </div>
            </div>
            <button class="drawer__close" type="button" data-admin-drawer-close aria-label="Fermer"><x-icon name="x"/></button>
        </div>

        <nav class="nav">
    <a class="nav__item {{ $isDash ? 'is-active' : '' }}" href="{{ route('admin.dashboard') }}"><span class="nav__icon"><x-icon name="dashboard"/></span>Dashboard</a>
    <a class="nav__item {{ $isAnnonces ? 'is-active' : '' }}" href="{{ route('admin.annonces.index') }}"><span class="nav__icon"><x-icon name="megaphone"/></span>Annonces</a>
    <a class="nav__item {{ $isActivites ? 'is-active' : '' }}" href="{{ route('admin.activites.index') }}"><span class="nav__icon"><x-icon name="calendar"/></span>Activités</a>
    <a class="nav__item {{ $isBureau ? 'is-active' : '' }}" href="{{ route('admin.bureau.index') }}"><span class="nav__icon"><x-icon name="briefcase"/></span>Bureau</a>

    <a class="nav__item {{ request()->routeIs('admin.galerie.*') ? 'is-active' : '' }}"
       href="{{ route('admin.galerie.index') }}"><span class="nav__icon"><x-icon name="image"/></span>Galerie</a>

    <a class="nav__item {{ request()->routeIs('admin.hero-images.*') ? 'is-active' : '' }}"
       href="{{ route('admin.hero-images.index') }}"><span class="nav__icon"><x-icon name="home"/></span>Accueil (photos)</a>

    <a class="nav__item {{ request()->routeIs('admin.partenaires*') ? 'is-active' : '' }}"
       href="{{ route('admin.partenaires.index') }}"><span class="nav__icon"><x-icon name="handshake"/></span>Partenaires</a>

    <a class="nav__item {{ $isMessages ? 'is-active' : '' }}"
       href="{{ route('admin.messages.index') }}"><span class="nav__icon"><x-icon name="mail"/></span>Messages</a>

    <div class="nav__sep"></div>

    <a class="nav__item {{ $isMembres ? 'is-active' : '' }}" href="{{ route('admin.membres.index') }}"><span class="nav__icon"><x-icon name="users"/></span>Membres</a>
    <a class="nav__item {{ $isDeps ? 'is-active' : '' }}" href="{{ route('admin.departements.index') }}"><span class="nav__icon"><x-icon name="tag"/></span>Départements</a>
    <a class="nav__item {{ $isPays ? 'is-active' : '' }}" href="{{ route('admin.pays.index') }}"><span class="nav__icon"><x-icon name="globe"/></span>Pays</a>

    @if($user && $user->is_super_admin)
        <a class="nav__item {{ request()->routeIs('admin.admins.*') ? 'is-active' : '' }}"
           href="{{ route('admin.admins.index') }}"><span class="nav__icon"><x-icon name="shield"/></span>Admins</a>
        <a class="nav__item {{ request()->routeIs('admin.tresorerie-comptes.*') ? 'is-active' : '' }}"
           href="{{ route('admin.tresorerie-comptes.index') }}"><span class="nav__icon"><x-icon name="wallet"/></span>Trésorerie</a>
    @endif

    <div class="nav__sep"></div>

    <a class="nav__item" href="{{ route('dashboard') }}"><span class="nav__icon"><x-icon name="switch"/></span>Espace membre</a>
</nav>


        <div class="sidebar__bottom">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn--primary btn--full" type="submit">Déconnexion</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
