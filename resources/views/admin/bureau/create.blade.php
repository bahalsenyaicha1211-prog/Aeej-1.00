@extends('layouts.admin')

@section('title', 'Admin • Ajouter bureau')
@section('header', 'Ajouter un membre du bureau')

@section('content')
<div class="card">
    <div class="toolbar">
        <div>
            <div style="font-weight:800; font-size:18px;">Ajouter</div>
            <div class="help">Associe un membre existant, puis définis poste/ordre.</div>
        </div>
        <a class="btn btn--ghost" href="{{ route('admin.bureau.index') }}">← Retour</a>
    </div>

    <form class="form" method="POST" action="{{ route('admin.bureau.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="field">
                <label>Membre (matricule)</label>
                <select class="select" name="matricule" required>
                    <option value="">— Choisir —</option>
                    @foreach($membres as $m)
                        <option value="{{ $m->matricule }}" {{ old('matricule') == $m->matricule ? 'selected' : '' }}>
                            {{ $m->prenom }} {{ $m->nom }} • {{ $m->matricule }}
                        </option>
                    @endforeach
                </select>
                @error('matricule') <div class="help" style="color:#fb7185;">{{ $message }}</div> @enderror
            </div>

            <div class="field">
                <label>Poste</label>
                <input class="input" name="poste" value="{{ old('poste') }}" required>
                @error('poste') <div class="help" style="color:#fb7185;">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="row">
            <div class="field">
                <label>Ordre</label>
                <input class="input" type="number" name="ordre" min="0" max="9999" value="{{ old('ordre', 0) }}">
                @error('ordre') <div class="help" style="color:#fb7185;">{{ $message }}</div> @enderror
            </div>

            <div class="field">
                <label>Photo (optionnel)</label>
                <input class="input" type="file" name="photo" accept="image/*">
                @error('photo') <div class="help" style="color:#fb7185;">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="field">
            <label>Activer</label>
            <label style="display:flex; gap:10px; align-items:center;">
                <input type="checkbox" name="is_actif" value="1" {{ old('is_actif') ? 'checked' : '' }}>
                <span>Afficher sur le site public</span>
            </label>
            @error('is_actif') <div class="help" style="color:#fb7185;">{{ $message }}</div> @enderror
        </div>

        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <button class="btn btn--primary" type="submit">Enregistrer</button>
            <a class="btn" href="{{ route('admin.bureau.index') }}">Annuler</a>
        </div>
    </form>
</div>
@endsection
