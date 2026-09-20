@extends('layouts.admin')

@section('title', 'Admin • Admins')
@section('header', 'Sécurité & Accès')

@section('content')
<div class="admDash">
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">Gestion des Administrateurs</h1>
            <p class="admDash__sub">Contrôlez les accès à l'interface de gestion de l'association.</p>
        </div>
        <a class="btn" style="background: #22c55e; color: #fff; border-radius: 12px; padding: 10px 20px; font-weight: 800; text-decoration: none;" href="{{ route('admin.admins.create') }}">
            + Ajouter un admin
        </a>
    </div>

    <form method="GET" data-live-search="#admins-results" action="{{ route('admin.admins.index') }}" style="margin-bottom: 16px; display:flex; gap:10px; align-items:center;">
        <input class="input" type="text" name="q" value="{{ $q }}" placeholder="Rechercher par nom ou email..." style="max-width:320px; width:100%;" autocomplete="off">
        <button class="admQuick__btn" type="submit">Rechercher</button>
        @if($q !== '')
            <a class="admQuick__btn" data-live-search-link="#admins-results" href="{{ route('admin.admins.index') }}" style="text-decoration:none;">Réinitialiser</a>
        @endif
    </form>

    <div id="admins-results">
        @include('admin.admins._results')
    </div>
</div>
@endsection