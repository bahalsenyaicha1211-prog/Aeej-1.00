{{-- resources/views/components/member-layout.blade.php --}}
@php
    $user = auth()->user();

    $unreadAnnoncesCount = $unreadAnnoncesCount ?? 0;

    $isDashboard = request()->routeIs('dashboard');
    $isAnnonces  = request()->routeIs('membre.annonces.*');
    $isCotisation = request()->routeIs('membre.cotisations.*');
    $isProfile   = request()->routeIs('profile.edit');

    $isTresCotisations = request()->routeIs('tresorerie.cotisations.*');
    $isTresConfig      = request()->routeIs('tresorerie.config.*');
    $isTresCaisse      = request()->routeIs('tresorerie.caisse.*');
    $isTresDepenses    = request()->routeIs('tresorerie.depenses.*');
@endphp

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $header ?? 'Espace membre' }}</title>

    <link rel="stylesheet" href="{{ asset('css/member.css') }}">
    <script src="{{ asset('js/member.js') }}" defer></script>
    <script src="{{ asset('js/password-toggle.js') }}" defer></script>
    <script src="{{ asset('js/select-search.js') }}" defer></script>
    
    <style>
        /* Ajustement pour les icônes du menu */
        .nav__item { display: flex; align-items: center; gap: 12px; }
        .nav__icon { width: 20px; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .nav__icon svg, .nav__item > svg { width: 19px; height: 19px; flex-shrink: 0; }
        .iconbtn { display: inline-flex; align-items: center; justify-content: center; }
        .iconbtn svg { width: 20px; height: 20px; }
        .btn svg { width: 18px; height: 18px; }
    </style>
</head>
<body class="page">

<div class="layout">

    {{-- Sidebar Desktop --}}
    <aside class="sidebar" aria-label="Navigation membre">
        <div class="sidebar__top">
            <x-avatar :user="$user" :size="45" />
            <div class="userbox">
                <div class="userbox__name">{{ $user->name }}</div>
                <div class="userbox__email">{{ $user->email }}</div>
            </div>
        </div>

        <nav class="nav">
            <a class="nav__item {{ $isDashboard ? 'is-active' : '' }}" href="{{ route('dashboard') }}">
                <span class="nav__icon"><x-icon name="home"/></span>
                <span>Tableau de bord</span>
            </a>

            <a class="nav__item {{ $isAnnonces ? 'is-active' : '' }}" href="{{ route('membre.annonces.index') }}">
                <span class="nav__icon"><x-icon name="megaphone"/></span>
                <span>Annonces</span>
                @if($unreadAnnoncesCount > 0)
                    <span class="badge">{{ $unreadAnnoncesCount }}</span>
                @endif
            </a>

            <a class="nav__item {{ $isCotisation ? 'is-active' : '' }}" href="{{ route('membre.cotisations.index') }}">
                <span class="nav__icon"><x-icon name="wallet"/></span>
                <span>Ma cotisation</span>
            </a>

            <a class="nav__item {{ $isProfile ? 'is-active' : '' }}" href="{{ route('profile.edit') }}">
                <span class="nav__icon"><x-icon name="user"/></span>
                <span>Mon profil</span>
            </a>

            @if($user->isTresorier())
                <a class="nav__item {{ $isTresCotisations ? 'is-active' : '' }}" href="{{ route('tresorerie.cotisations.index') }}">
                    <span class="nav__icon"><x-icon name="receipt"/></span>
                    <span>Cotisations</span>
                </a>
            @endif

            @if($user->isChefTresorier())
                <a class="nav__item {{ $isTresConfig ? 'is-active' : '' }}" href="{{ route('tresorerie.config.edit') }}">
                    <span class="nav__icon"><x-icon name="settings"/></span>
                    <span>Montants cotisation</span>
                </a>
            @endif

            @if($user->isChefTresorier() || $user->isCommissaireComptes())
                <a class="nav__item {{ $isTresCaisse ? 'is-active' : '' }}" href="{{ route('tresorerie.caisse.index') }}">
                    <span class="nav__icon"><x-icon name="bank"/></span>
                    <span>Caisse</span>
                </a>
            @endif

            @if($user->isCommissaireComptes())
                <a class="nav__item {{ $isTresDepenses ? 'is-active' : '' }}" href="{{ route('tresorerie.depenses.index') }}">
                    <span class="nav__icon"><x-icon name="banknote"/></span>
                    <span>Dépenses</span>
                </a>
            @endif

            @if($user->is_admin)
                <a class="nav__item" href="{{ route('admin.dashboard') }}">
                    <span class="nav__icon"><x-icon name="settings"/></span>
                    <span>Espace admin</span>
                </a>
            @endif
        </nav>

        <div class="sidebar__bottom">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn--primary" style="width:100%; display: flex; align-items: center; justify-content: center; gap: 8px;">
                    <x-icon name="logout"/> Déconnexion
                </button>
            </form>
        </div>
    </aside>

    <div class="main">
        <header class="topbar">
            <div style="display:flex; align-items:center; gap:10px;">
                <button class="iconbtn" data-drawer-open type="button" aria-label="Ouvrir le menu">
                    <x-icon name="menu"/>
                </button>
                <div class="topbar__title">
                    {{ $header ?? 'Espace membre' }}
                </div>
            </div>

            <div class="topbar__right">
                <a class="iconbtn" href="{{ route('membre.annonces.index') }}" aria-label="Annonces">
                    <x-icon name="bell"/>
                    @if($unreadAnnoncesCount > 0)
                        <span class="dot">{{ $unreadAnnoncesCount }}</span>
                    @endif
                </a>

                <x-avatar :user="$user" :size="35" />
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
                <x-avatar :user="$user" :size="45" />
                <div class="userbox">
                    <div class="userbox__name">{{ $user->name }}</div>
                </div>
            </div>
            <button class="drawer__close" data-drawer-close type="button"><x-icon name="x"/></button>
        </div>

        <nav class="nav">
            <a class="nav__item {{ $isDashboard ? 'is-active' : '' }}" href="{{ route('dashboard') }}"><x-icon name="home"/> Tableau de bord</a>
            <a class="nav__item {{ $isAnnonces ? 'is-active' : '' }}" href="{{ route('membre.annonces.index') }}"><x-icon name="megaphone"/> Annonces</a>
            <a class="nav__item {{ $isCotisation ? 'is-active' : '' }}" href="{{ route('membre.cotisations.index') }}"><x-icon name="wallet"/> Ma cotisation</a>
            <a class="nav__item {{ $isProfile ? 'is-active' : '' }}" href="{{ route('profile.edit') }}"><x-icon name="user"/> Mon profil</a>

            @if($user->isTresorier())
                <a class="nav__item {{ $isTresCotisations ? 'is-active' : '' }}" href="{{ route('tresorerie.cotisations.index') }}"><x-icon name="receipt"/> Cotisations</a>
            @endif
            @if($user->isChefTresorier())
                <a class="nav__item {{ $isTresConfig ? 'is-active' : '' }}" href="{{ route('tresorerie.config.edit') }}"><x-icon name="settings"/> Montants cotisation</a>
            @endif
            @if($user->isChefTresorier() || $user->isCommissaireComptes())
                <a class="nav__item {{ $isTresCaisse ? 'is-active' : '' }}" href="{{ route('tresorerie.caisse.index') }}"><x-icon name="bank"/> Caisse</a>
            @endif
            @if($user->isCommissaireComptes())
                <a class="nav__item {{ $isTresDepenses ? 'is-active' : '' }}" href="{{ route('tresorerie.depenses.index') }}"><x-icon name="banknote"/> Dépenses</a>
            @endif
            @if($user->is_admin)
                <a class="nav__item" href="{{ route('admin.dashboard') }}"><x-icon name="settings"/> Espace admin</a>
            @endif
        </nav>

        <div class="drawer__bottom">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn--primary" style="width:100%; display: flex; align-items: center; justify-content: center; gap: 8px;">
                    <x-icon name="logout"/> Déconnexion
                </button>
            </form>
        </div>
    </div>
</div>

</body>
</html>