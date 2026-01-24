{{-- resources/views/components/member-layout.blade.php --}}
@php
    $user = auth()->user();

    // Avatar URL
    $avatarUrl = $user->profile_photo_path
        ? asset('storage/'.$user->profile_photo_path)
        : asset('images/default-avatar.png');

    $unreadAnnoncesCount = $unreadAnnoncesCount ?? 0;

    // Helpers active
    $isDashboard = request()->routeIs('dashboard');
    $isAnnonces  = request()->routeIs('membre.annonces.*');
    $isProfile   = request()->routeIs('profile.edit');
@endphp

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $header ?? 'Espace membre' }}</title>

    {{-- CSS membre (public/css/member.css) --}}
    <link rel="stylesheet" href="{{ asset('css/member.css') }}">
    {{-- JS membre (public/js/member.js) --}}
    <script src="{{ asset('js/member.js') }}" defer></script>
</head>
<body class="page">

<div class="layout">

    {{-- Sidebar Desktop --}}
    <aside class="sidebar" aria-label="Navigation membre">
        <div class="sidebar__top">
            <img src="{{ $avatarUrl }}" class="avatar" alt="Photo de profil">
            <div class="userbox">
                <div class="userbox__name">{{ $user->name }}</div>
                <div class="userbox__email">{{ $user->email }}</div>
            </div>
        </div>

        <nav class="nav">
            <a class="nav__item {{ $isDashboard ? 'is-active' : '' }}" href="{{ route('dashboard') }}">
                <span>Tableau de bord</span>
            </a>

            <a class="nav__item {{ $isAnnonces ? 'is-active' : '' }}" href="{{ route('membre.annonces.index') }}">
                <span>Annonces</span>
                @if($unreadAnnoncesCount > 0)
                    <span class="badge">{{ $unreadAnnoncesCount }}</span>
                @endif
            </a>

            <a class="nav__item {{ $isProfile ? 'is-active' : '' }}" href="{{ route('profile.edit') }}">
                <span>Mon profil</span>
            </a>

            @if($user->is_admin)
                <a class="nav__item" href="{{ route('admin.dashboard') }}">
                    <span>Espace admin</span>
                </a>
            @endif
        </nav>

        <div class="sidebar__bottom">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn--primary" style="width:100%;">Déconnexion</button>
            </form>
        </div>
    </aside>

    {{-- Main --}}
    <div class="main">

        {{-- Topbar Mobile/Global --}}
        <header class="topbar">
            <div style="display:flex; align-items:center; gap:10px;">
                {{-- Drawer open (mobile) --}}
                <button class="iconbtn" data-drawer-open type="button" aria-label="Ouvrir le menu">
                    ☰
                </button>

                <div class="topbar__title">
                    {{ $header ?? 'Espace membre' }}
                </div>
            </div>

            <div class="topbar__right">
                <a class="iconbtn" href="{{ route('membre.annonces.index') }}" aria-label="Annonces">
                    🔔
                    @if($unreadAnnoncesCount > 0)
                        <span class="dot">{{ $unreadAnnoncesCount }}</span>
                    @endif
                </a>

                <img src="{{ $avatarUrl }}" class="avatar" alt="Photo de profil">
            </div>
        </header>

        <main class="content">
            {{ $slot }}
        </main>
    </div>
</div>

{{-- Drawer Mobile --}}
<div class="drawer" data-drawer>
    <div class="drawer__backdrop" data-drawer-backdrop></div>
    <div class="drawer__panel" role="dialog" aria-modal="true" aria-label="Menu membre">
        <div class="drawer__top">
            <div style="display:flex; align-items:center; gap:10px;">
                <img src="{{ $avatarUrl }}" class="avatar" alt="Photo de profil">
                <div class="userbox">
                    <div class="userbox__name">{{ $user->name }}</div>
                    <div class="userbox__email">{{ $user->email }}</div>
                </div>
            </div>

            <button class="drawer__close" data-drawer-close type="button" aria-label="Fermer le menu">✕</button>
        </div>

        <nav class="nav">
            <a class="nav__item {{ $isDashboard ? 'is-active' : '' }}" href="{{ route('dashboard') }}">Tableau de bord</a>

            <a class="nav__item {{ $isAnnonces ? 'is-active' : '' }}" href="{{ route('membre.annonces.index') }}">
                <span>Annonces</span>
                @if($unreadAnnoncesCount > 0)
                    <span class="badge">{{ $unreadAnnoncesCount }}</span>
                @endif
            </a>

            <a class="nav__item {{ $isProfile ? 'is-active' : '' }}" href="{{ route('profile.edit') }}">Mon profil</a>

            @if($user->is_admin)
                <a class="nav__item" href="{{ route('admin.dashboard') }}">Espace admin</a>
            @endif
        </nav>

        <div class="sidebar__bottom">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn--primary" style="width:100%;">Déconnexion</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
