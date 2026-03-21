{{-- resources/views/components/member-layout.blade.php --}}
@php
    $user = auth()->user();
    
    $nameParts = explode(' ', trim($user->name));
    if (count($nameParts) >= 2) {
        $initials = substr($nameParts[0], 0, 1) . substr(end($nameParts), 0, 1);
    } else {
        $initials = substr($user->name, 0, 2);
    }
    $initials = strtoupper($initials);

    $unreadAnnoncesCount = $unreadAnnoncesCount ?? 0;

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

    <link rel="stylesheet" href="{{ asset('css/member.css') }}">
    <script src="{{ asset('js/member.js') }}" defer></script>
    
    <style>
        .avatar-circle {
            background: #055b20;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            text-transform: uppercase;
            border-radius: 50%;
            flex-shrink: 0;
        }
        .avatar-sidebar { width: 45px; height: 45px; font-size: 1.2rem; }
        .avatar-topbar { width: 35px; height: 35px; font-size: 0.9rem; }
        
        /* Ajustement pour les icônes du menu */
        .nav__item { display: flex; align-items: center; gap: 12px; }
        .nav__icon { font-size: 1.1rem; width: 20px; text-align: center; }
    </style>
</head>
<body class="page">

<div class="layout">

    {{-- Sidebar Desktop --}}
    <aside class="sidebar" aria-label="Navigation membre">
        <div class="sidebar__top">
            <div class="avatar-circle avatar-sidebar">
                {{ $initials }}
            </div>
            <div class="userbox">
                <div class="userbox__name">{{ $user->name }}</div>
                <div class="userbox__email">{{ $user->email }}</div>
            </div>
        </div>

        <nav class="nav">
            <a class="nav__item {{ $isDashboard ? 'is-active' : '' }}" href="{{ route('dashboard') }}">
                <span class="nav__icon">🏠</span>
                <span>Tableau de bord</span>
            </a>

            <a class="nav__item {{ $isAnnonces ? 'is-active' : '' }}" href="{{ route('membre.annonces.index') }}">
                <span class="nav__icon">📢</span>
                <span>Annonces</span>
                @if($unreadAnnoncesCount > 0)
                    <span class="badge">{{ $unreadAnnoncesCount }}</span>
                @endif
            </a>

            <a class="nav__item {{ $isProfile ? 'is-active' : '' }}" href="{{ route('profile.edit') }}">
                <span class="nav__icon">👤</span>
                <span>Mon profil</span>
            </a>

            @if($user->is_admin)
                <a class="nav__item" href="{{ route('admin.dashboard') }}">
                    <span class="nav__icon">⚙️</span>
                    <span>Espace admin</span>
                </a>
            @endif
        </nav>

        <div class="sidebar__bottom">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn--primary" style="width:100%; display: flex; align-items: center; justify-content: center; gap: 8px;">
                    <span>🚪</span> Déconnexion
                </button>
            </form>
        </div>
    </aside>

    <div class="main">
        <header class="topbar">
            <div style="display:flex; align-items:center; gap:10px;">
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

                <div class="avatar-circle avatar-topbar">
                    {{ $initials }}
                </div>
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
                <div class="avatar-circle avatar-sidebar">
                    {{ $initials }}
                </div>
                <div class="userbox">
                    <div class="userbox__name">{{ $user->name }}</div>
                </div>
            </div>
            <button class="drawer__close" data-drawer-close type="button">✕</button>
        </div>

        <nav class="nav">
            <a class="nav__item {{ $isDashboard ? 'is-active' : '' }}" href="{{ route('dashboard') }}">🏠 Tableau de bord</a>
            <a class="nav__item {{ $isAnnonces ? 'is-active' : '' }}" href="{{ route('membre.annonces.index') }}">📢 Annonces</a>
            <a class="nav__item {{ $isProfile ? 'is-active' : '' }}" href="{{ route('profile.edit') }}">👤 Mon profil</a>
        </nav>
    </div>
</div>

</body>
</html>