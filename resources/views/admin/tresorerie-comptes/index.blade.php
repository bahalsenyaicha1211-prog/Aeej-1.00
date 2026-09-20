@extends('layouts.admin')

@section('title', 'Admin • Trésorerie')
@section('header', 'Comptes trésorerie')

@section('content')
<div class="admDash">
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">Comptes trésorerie</h1>
            <p class="admDash__sub">Membres ayant un rôle trésorier, chef trésorier ou commissaire aux comptes.</p>
        </div>
        <a class="btn" style="background: #22c55e; color: #fff; border-radius: 12px; padding: 10px 20px; font-weight: 800; text-decoration: none;" href="{{ route('admin.tresorerie-comptes.create') }}">
            + Attribuer un rôle
        </a>
    </div>

    <form method="GET" data-live-search="#tresorerie-comptes-results" action="{{ route('admin.tresorerie-comptes.index') }}" style="margin-bottom: 16px; display:flex; gap:10px; align-items:center;">
        <input class="input" type="text" name="q" value="{{ $q }}" placeholder="Rechercher par nom ou email..." style="max-width:320px; width:100%;" autocomplete="off">
        <button class="admQuick__btn" type="submit">Rechercher</button>
        @if($q !== '')
            <a class="admQuick__btn" data-live-search-link="#tresorerie-comptes-results" href="{{ route('admin.tresorerie-comptes.index') }}" style="text-decoration:none;">Réinitialiser</a>
        @endif
    </form>

    <div id="tresorerie-comptes-results">
        @include('admin.tresorerie-comptes._results')
    </div>
</div>
@endsection
