@extends('layouts.admin')

@section('title', 'Admin • Messages')
@section('header', 'Messages de contact')

@section('content')
<div class="admDash">
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">Messages reçus</h1>
            <p class="admDash__sub">Messages envoyés depuis le formulaire de contact du site public.</p>
        </div>
    </div>

    <form method="GET" data-live-search="#messages-results" action="{{ route('admin.messages.index') }}" style="margin-bottom: 16px; display:flex; gap:10px; align-items:center;">
        <input class="input" type="text" name="q" value="{{ $q }}" placeholder="Rechercher par nom ou email..." style="max-width:320px; width:100%;" autocomplete="off">
        <button class="admQuick__btn" type="submit">Rechercher</button>
        @if($q !== '')
            <a class="admQuick__btn" data-live-search-link="#messages-results" href="{{ route('admin.messages.index') }}" style="text-decoration:none;">Réinitialiser</a>
        @endif
    </form>

    <div id="messages-results">
        @include('admin.messages._results')
    </div>
</div>
@endsection
