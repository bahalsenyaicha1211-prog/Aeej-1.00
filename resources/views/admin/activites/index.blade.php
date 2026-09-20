@extends('layouts.admin')

@section('title', 'Admin • Activités')
@section('header', 'Journal des Activités')

@section('content')
<div class="admDash">
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">Activités de l'Association</h1>
            <p class="admDash__sub">Historique et planification des événements et actions réalisées.</p>
        </div>
        <a class="btn" style="background: #22c55e; color: #fff; border-radius: 12px; padding: 10px 20px; font-weight: 800; text-decoration: none;" href="{{ route('admin.activites.create') }}">
            + Nouvelle Activité
        </a>
    </div>

    <form method="GET" data-live-search="#activites-results" action="{{ route('admin.activites.index') }}" style="margin-bottom: 16px; display:flex; gap:10px; align-items:center;">
        <input class="input" type="text" name="q" value="{{ $q }}" placeholder="Rechercher une activité..." style="max-width:320px; width:100%;" autocomplete="off">
        <button class="admQuick__btn" type="submit">Rechercher</button>
        @if($q !== '')
            <a class="admQuick__btn" data-live-search-link="#activites-results" href="{{ route('admin.activites.index') }}" style="text-decoration:none;">Réinitialiser</a>
        @endif
    </form>

    <div id="activites-results">
        @include('admin.activites._results')
    </div>
</div>
@endsection