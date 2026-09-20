@extends('layouts.admin')

@section('title', 'Admin • Pays')
@section('header', 'Gestion géographique')

@section('content')
<div class="admDash">
    {{-- Header --}}
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">Référentiel des Pays</h1>
            <p class="admDash__sub">Gérez la liste des pays de résidence disponibles pour les membres.</p>
        </div>
        <a class="btn" style="background: #22c55e; color: #fff; border-radius: 12px; padding: 10px 20px; font-weight: 800; text-decoration: none;" href="{{ route('admin.pays.create') }}">
            + Nouveau Pays
        </a>
    </div>

    <form method="GET" data-live-search="#pays-results" action="{{ route('admin.pays.index') }}" style="margin-bottom: 16px; display:flex; gap:10px; align-items:center;">
        <input class="input" type="text" name="q" value="{{ $q }}" placeholder="Rechercher un pays..." style="max-width:320px; width:100%;" autocomplete="off">
        <button class="admQuick__btn" type="submit">Rechercher</button>
        @if($q !== '')
            <a class="admQuick__btn" data-live-search-link="#pays-results" href="{{ route('admin.pays.index') }}" style="text-decoration:none;">Réinitialiser</a>
        @endif
    </form>

    <div id="pays-results">
        @include('admin.pays._results')
    </div>
</div>
@endsection