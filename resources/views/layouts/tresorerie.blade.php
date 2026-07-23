@php
    $user = auth()->user();

    $title  = trim($__env->yieldContent('title')) ?: 'Trésorerie - AEEJ';
    $header = trim($__env->yieldContent('header')) ?: 'Trésorerie';

    $isDash        = request()->routeIs('tresorerie.dashboard');
    $isCotisations = request()->routeIs('tresorerie.cotisations.*');
    $isConfig      = request()->routeIs('tresorerie.config.*');
    $isCaisse      = request()->routeIs('tresorerie.caisse.*');
    $isDepenses    = request()->routeIs('tresorerie.depenses.*');

    $initials = $user
        ? strtoupper(substr($user->name, 0, 1) . (explode(' ', $user->name)[1][0] ?? ''))
        : '??';
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
</head>
<body class="page">

<div class="layout">

    {{-- Sidebar desktop --}}
    <aside class="sidebar" aria-label="Navigation trésorerie">
        <div class="sidebar__top">
            <div class="brand">
                <div class="brand__logo">AEEJ</div>
                <div class="brand__text">
                    <div class="brand__title">Trésorerie</div>
                    <div class="brand__sub">
                        @if($user->isChefTresorier()) Chef trésorier
                        @elseif($user->isTresorier()) Trésorier
                        @elseif($user->isCommissaireComptes()) Commissaire aux comptes
                        @endif
                    </div>
                </div>
            </div>

            <div class="who">
                <div class="avatar" style="background:#22c55e;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;text-transform:uppercase;">
                    {{ $initials }}
                </div>
                <div class="who__meta">
                    <div class="who__name">{{ $user->name }}</div>
                    <div class="who__email">{{ $user->email }}</div>
                </div>
            </div>
        </div>

        <nav class="nav">
            <a class="nav__item {{ $isDash ? 'is-active' : '' }}" href="{{ route('tresorerie.dashboard') }}"><span class="nav__icon">⌂</span>Accueil</a>

            @if($user->isTresorier())
                <a class="nav__item {{ $isCotisations ? 'is-active' : '' }}" href="{{ route('tresorerie.cotisations.index') }}"><span class="nav__icon">🧾</span>Cotisations</a>
            @endif

            @if($user->isChefTresorier())
                <a class="nav__item {{ $isConfig ? 'is-active' : '' }}" href="{{ route('tresorerie.config.edit') }}"><span class="nav__icon">⚙</span>Montants cotisation</a>
            @endif

            @if($user->isChefTresorier() || $user->isCommissaireComptes())
                <a class="nav__item {{ $isCaisse ? 'is-active' : '' }}" href="{{ route('tresorerie.caisse.index') }}"><span class="nav__icon">🏦</span>Caisse</a>
            @endif

            @if($user->isCommissaireComptes())
                <a class="nav__item {{ $isDepenses ? 'is-active' : '' }}" href="{{ route('tresorerie.depenses.index') }}"><span class="nav__icon">💸</span>Dépenses</a>
            @endif

            <div class="nav__sep"></div>

            @if($user->is_admin)
                <a class="nav__item" href="{{ route('admin.dashboard') }}"><span class="nav__icon">↩</span>Espace admin</a>
            @endif
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

        <header class="topbar">
            <div class="topbar__left">
                <button class="iconbtn" type="button" data-admin-drawer-open aria-label="Ouvrir le menu">☰</button>
                <div class="topbar__title">{{ $header }}</div>
            </div>

            <div class="topbar__right">
                <div class="avatar" style="background:#22c55e;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;text-transform:uppercase;">
                    {{ $initials }}
                </div>
            </div>
        </header>

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
    <div class="drawer__panel" role="dialog" aria-modal="true" aria-label="Menu trésorerie">
        <div class="drawer__top">
            <div class="who">
                <div class="avatar" style="background:#22c55e;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;text-transform:uppercase;">
                    {{ $initials }}
                </div>
                <div class="who__meta">
                    <div class="who__name">{{ $user->name }}</div>
                    <div class="who__email">{{ $user->email }}</div>
                </div>
            </div>
            <button class="drawer__close" type="button" data-admin-drawer-close aria-label="Fermer">✕</button>
        </div>

        <nav class="nav">
            <a class="nav__item {{ $isDash ? 'is-active' : '' }}" href="{{ route('tresorerie.dashboard') }}"><span class="nav__icon">⌂</span>Accueil</a>

            @if($user->isTresorier())
                <a class="nav__item {{ $isCotisations ? 'is-active' : '' }}" href="{{ route('tresorerie.cotisations.index') }}"><span class="nav__icon">🧾</span>Cotisations</a>
            @endif

            @if($user->isChefTresorier())
                <a class="nav__item {{ $isConfig ? 'is-active' : '' }}" href="{{ route('tresorerie.config.edit') }}"><span class="nav__icon">⚙</span>Montants cotisation</a>
            @endif

            @if($user->isChefTresorier() || $user->isCommissaireComptes())
                <a class="nav__item {{ $isCaisse ? 'is-active' : '' }}" href="{{ route('tresorerie.caisse.index') }}"><span class="nav__icon">🏦</span>Caisse</a>
            @endif

            @if($user->isCommissaireComptes())
                <a class="nav__item {{ $isDepenses ? 'is-active' : '' }}" href="{{ route('tresorerie.depenses.index') }}"><span class="nav__icon">💸</span>Dépenses</a>
            @endif
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
