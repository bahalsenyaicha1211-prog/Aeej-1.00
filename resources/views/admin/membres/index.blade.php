@extends('layouts.admin')

@section('title', 'Admin • Membres')
@section('header', 'Répertoire des Membres')

@section('content')
<div class="admDash">
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">Membres</h1>
            <p class="admDash__sub">Consultez et gérez la base de données de tous les inscrits.</p>
        </div>
    </div>

    <form method="GET" data-live-search="#membres-results" action="{{ route('admin.membres.index') }}" style="margin-bottom: 16px; display:flex; gap:10px; align-items:center;">
        <input class="input" type="text" name="q" value="{{ $q }}" placeholder="Rechercher par nom, prénom ou matricule..." style="max-width:320px; width:100%;" autocomplete="off">
        <button class="admQuick__btn" type="submit">Rechercher</button>
        @if($q !== '')
            <a class="admQuick__btn" data-live-search-link="#membres-results" href="{{ route('admin.membres.index') }}" style="text-decoration:none;">Réinitialiser</a>
        @endif
    </form>

    @if(($pendingCount ?? 0) > 0)
        <div style="margin-bottom:16px; padding:12px 16px; border-radius:12px; background:rgba(251,191,36,0.10); border:1px solid rgba(251,191,36,0.30); color:#fcd34d; font-weight:600; font-size:13px;">
            {{ $pendingCount }} inscription{{ $pendingCount > 1 ? 's' : '' }} en attente de validation.
        </div>
    @endif

    <div id="membres-results">
        @include('admin.membres._results')
    </div>
</div>
@endsection