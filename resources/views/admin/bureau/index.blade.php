@extends('layouts.admin')

@section('title', 'Gestion du Bureau')
@section('header', 'Structure du Bureau')

@section('content')
<div class="admDash">
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">Gestion du Bureau</h1>
            <p class="admDash__sub">Organisez la hiérarchie et l'affichage des membres du bureau public.</p>
        </div>
        <a class="btn" style="background: #22c55e; color: #fff; border-radius: 12px; padding: 10px 20px; font-weight: 800;" href="{{ route('admin.bureau.create') }}">
            + Ajouter un membre
        </a>
    </div>

    <form method="GET" data-live-search="#bureau-results" action="{{ route('admin.bureau.index') }}" style="margin-bottom: 16px; display:flex; gap:10px; align-items:center;">
        <input class="input" type="text" name="q" value="{{ $q }}" placeholder="Rechercher par nom, matricule ou poste..." style="max-width:320px; width:100%;" autocomplete="off">
        <button class="admQuick__btn" type="submit">Rechercher</button>
        @if($q !== '')
            <a class="admQuick__btn" data-live-search-link="#bureau-results" href="{{ route('admin.bureau.index') }}" style="text-decoration:none;">Réinitialiser</a>
        @endif
    </form>

    <div id="bureau-results">
        @include('admin.bureau._results')
    </div>
</div>
@endsection