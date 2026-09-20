@extends('layouts.admin')

@section('title', 'Admin • Départements')
@section('header', 'Gestion des Départements')

@section('content')
<div class="admDash">
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">Départements</h1>
            <p class="admDash__sub">Structurez l'organisation en gérant les différents secteurs d'activité.</p>
        </div>
        <a class="btn" style="background: #22c55e; color: #fff; border-radius: 12px; padding: 10px 20px; font-weight: 800;" href="{{ route('admin.departements.create') }}">
            + Nouveau Département
        </a>
    </div>

    <form method="GET" data-live-search="#departements-results" action="{{ route('admin.departements.index') }}" style="margin-bottom: 16px; display:flex; gap:10px; align-items:center;">
        <input class="input" type="text" name="q" value="{{ $q }}" placeholder="Rechercher un département..." style="max-width:320px; width:100%;" autocomplete="off">
        <button class="admQuick__btn" type="submit">Rechercher</button>
        @if($q !== '')
            <a class="admQuick__btn" data-live-search-link="#departements-results" href="{{ route('admin.departements.index') }}" style="text-decoration:none;">Réinitialiser</a>
        @endif
    </form>

    <div id="departements-results">
        @include('admin.departements._results')
    </div>
</div>
@endsection