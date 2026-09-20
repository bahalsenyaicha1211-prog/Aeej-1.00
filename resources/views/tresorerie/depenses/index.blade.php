<x-member-layout>
    <x-slot name="header">Dépenses</x-slot>

    <div class="card">
        <div class="section__head">
            <div class="section__title">Dépenses enregistrées</div>
            <div style="display:flex; gap:10px;">
                <a class="btn btn--ghost" href="{{ route('tresorerie.depenses.rapport-pdf') }}">📄 Rapport PDF</a>
                <a class="btn btn--primary" href="{{ route('tresorerie.depenses.create') }}">+ Nouvelle dépense</a>
            </div>
        </div>

        <form method="GET" data-live-search="#depenses-results" action="{{ route('tresorerie.depenses.index') }}" style="display:flex; gap:10px; align-items:center; margin-bottom:14px;">
            <input class="input" type="text" name="q" value="{{ $q }}" placeholder="Rechercher un événement..." style="max-width:300px;" autocomplete="off">
            <button class="btn btn--ghost" type="submit">Rechercher</button>
            @if($q !== '')
                <a class="btn btn--ghost" data-live-search-link="#depenses-results" href="{{ route('tresorerie.depenses.index') }}">Réinitialiser</a>
            @endif
        </form>

        <div id="depenses-results">
            @include('tresorerie.depenses._results')
        </div>
    </div>
</x-member-layout>
