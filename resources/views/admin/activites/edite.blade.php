@extends('layouts.admin')

@section('title', 'Admin • Modifier activité')
@section('header', 'Modifier une activité')

@section('content')
<div class="card">
    <div class="toolbar">
        <div>
            <div style="font-weight:800; font-size:18px;">Modifier</div>
            <div class="help">Met à jour les informations de l’activité.</div>
        </div>
        <a class="btn btn--ghost" href="{{ route('admin.activites.index') }}">← Retour</a>
    </div>

    <form class="form" method="POST" action="{{ route('admin.activites.update', $activite) }}">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="field">
                <label>Libellé</label>
                <input class="input" name="libelle" value="{{ old('libelle', $activite->libelle) }}" required>
                @error('libelle') <div class="help" style="color:#fb7185;">{{ $message }}</div> @enderror
            </div>

            <div class="field">
                <label>Catégorie (optionnel)</label>
                <input class="input" name="categorie" value="{{ old('categorie', $activite->categorie) }}">
                @error('categorie') <div class="help" style="color:#fb7185;">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="row">
            <div class="field">
                <label>Date</label>
                <input class="input" type="date" name="date"
                       value="{{ old('date', \Illuminate\Support\Carbon::parse($activite->date)->format('Y-m-d')) }}" required>
                @error('date') <div class="help" style="color:#fb7185;">{{ $message }}</div> @enderror
            </div>
            <div></div>
        </div>

        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <button class="btn btn--primary" type="submit">Mettre à jour</button>
            <a class="btn" href="{{ route('admin.activites.index') }}">Annuler</a>
        </div>
    </form>
</div>
@endsection
