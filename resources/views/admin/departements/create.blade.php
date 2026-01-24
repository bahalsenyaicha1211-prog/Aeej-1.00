@extends('layouts.admin')

@section('title', 'Admin • Nouveau département')
@section('header', 'Créer un département')

@section('content')
<div class="card">
    <div class="toolbar">
        <div>
            <div style="font-weight:800; font-size:18px;">Nouveau département</div>
            <div class="help">Ajoute un libellé clair et unique.</div>
        </div>
        <a class="btn btn--ghost" href="{{ route('admin.departements.index') }}">← Retour</a>
    </div>

    <form class="form" method="POST" action="{{ route('admin.departements.store') }}">
        @csrf
        <div class="field">
            <label>Nom</label>
            <input class="input" name="nom" value="{{ old('nom') }}" required>
            @error('nom') <div class="help" style="color:#fb7185;">{{ $message }}</div> @enderror
        </div>

        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <button class="btn btn--primary" type="submit">Enregistrer</button>
            <a class="btn" href="{{ route('admin.departements.index') }}">Annuler</a>
        </div>
    </form>
</div>
@endsection
