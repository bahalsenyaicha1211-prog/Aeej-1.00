@extends('layouts.admin')

@section('title', 'Admin • Nouvelle annonce')
@section('header', 'Créer une annonce')

@section('content')
<div class="card">
    <div class="toolbar">
        <div>
            <div style="font-weight:800; font-size:18px;">Nouvelle annonce</div>
            <div class="help">Tu peux publier maintenant ou garder en brouillon.</div>
        </div>
        <a class="btn btn--ghost" href="{{ route('admin.annonces.index') }}">← Retour</a>
    </div>

    <form class="form" method="POST" action="{{ route('admin.annonces.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="field">
            <label>Contenu</label>
            <textarea class="textarea" name="contenu" required>{{ old('contenu') }}</textarea>
            @error('contenu') <div class="help" style="color:#fb7185;">{{ $message }}</div> @enderror
        </div>

        <div class="row">
            <div class="field">
                <label>Image (optionnel)</label>
                <input class="input" type="file" name="image" accept="image/*">
                <div class="help">JPG/PNG/WEBP • 2Mo max</div>
                @error('image') <div class="help" style="color:#fb7185;">{{ $message }}</div> @enderror
            </div>

            <div class="field">
                <label>Options</label>
                <div style="display:flex; flex-direction:column; gap:10px;">
                    <label style="display:flex; gap:10px; align-items:center;">
                        <input type="checkbox" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }}>
                        <span>Publier</span>
                    </label>
                    <label style="display:flex; gap:10px; align-items:center;">
                        <input type="checkbox" name="is_pinned" value="1" {{ old('is_pinned') ? 'checked' : '' }}>
                        <span>Épingler</span>
                    </label>
                </div>
            </div>
        </div>

        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <button class="btn btn--primary" type="submit">Enregistrer</button>
            <a class="btn" href="{{ route('admin.annonces.index') }}">Annuler</a>
        </div>
    </form>
</div>
@endsection
