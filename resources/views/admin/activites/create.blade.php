@extends('layouts.admin')

@section('title', 'Admin • Nouvelle activité')
@section('header', 'Créer une activité')

@section('content')
<div class="card">
    <div class="toolbar">
        <div>
            <div style="font-weight:800; font-size:18px;">Nouvelle activité</div>
            <div class="help">Renseigne les infos, puis enregistre.</div>
        </div>
        <a class="btn btn--ghost" href="{{ route('admin.activites.index') }}">← Retour</a>
    </div>

    <form class="form" method="POST" action="{{ route('admin.activites.store') }}">
        @csrf

        <div class="row">
            <div class="field">
                <label>Libellé</label>
                <input class="input" name="libelle" value="{{ old('libelle') }}" required>
                @error('libelle') <div class="help" style="color:#fb7185;">{{ $message }}</div> @enderror
            </div>

            <div class="field">
                <label>Catégorie (optionnel)</label>
                <input class="input" name="categorie" value="{{ old('categorie') }}">
                @error('categorie') <div class="help" style="color:#fb7185;">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="row">
            <div class="field">
                <label>Date</label>
                <input class="input" type="date" name="date" value="{{ old('date') }}" required>
                @error('date') <div class="help" style="color:#fb7185;">{{ $message }}</div> @enderror
            </div>
            <div></div>
        </div>

        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <button class="btn btn--primary" type="submit">Enregistrer</button>
            <a class="btn" href="{{ route('admin.activites.index') }}">Annuler</a>
        </div>
    </form>
</div>
@endsection
