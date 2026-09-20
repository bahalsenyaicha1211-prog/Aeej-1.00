@extends('layouts.admin')

@section('title', 'Admin • Annonces')
@section('header', 'Gestion des communications')

@section('content')
<div class="admDash">
    {{-- Header --}}
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">Annonces & Flashs</h1>
            <p class="admDash__sub">Gérez les messages diffusés sur le tableau de bord des membres.</p>
        </div>
        <a class="btn" style="background: #22c55e; color: #fff; border-radius: 12px; padding: 10px 20px; font-weight: 800; text-decoration: none;" href="{{ route('admin.annonces.create') }}">
            + Nouvelle Annonce
        </a>
    </div>

    <form method="GET" data-live-search="#annonces-results" action="{{ route('admin.annonces.index') }}" style="margin-bottom: 16px; display:flex; gap:10px; align-items:center;">
        <input class="input" type="text" name="q" value="{{ $q }}" placeholder="Rechercher dans le contenu des annonces..." style="max-width:320px; width:100%;" autocomplete="off">
        <button class="admQuick__btn" type="submit">Rechercher</button>
        @if($q !== '')
            <a class="admQuick__btn" data-live-search-link="#annonces-results" href="{{ route('admin.annonces.index') }}" style="text-decoration:none;">Réinitialiser</a>
        @endif
    </form>

    <div id="annonces-results">
        @include('admin.annonces._results')
    </div>
</div>
@endsection