@extends('layouts.admin')

@section('title', 'Admin • Ajouter photos')
@section('header', 'Galerie - Ajouter')

@section('content')

<div class="page__head">
    <div>
        <h1 class="page__title">Ajouter des photos</h1>
        <p class="page__subtitle">Tu peux sélectionner plusieurs images et les publier en une seule fois.</p>
    </div>
    <div class="page__actions">
        <a class="btn" href="{{ route('admin.galerie.index') }}">← Retour</a>
    </div>
</div>

<div class="card">
    <div class="card__body">

        @if ($errors->any())
            <div class="flash flash--error" style="margin-bottom:12px;">
                <strong>Erreurs :</strong>
                <ul style="margin:8px 0 0; padding-left:18px;">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.galerie.store') }}" enctype="multipart/form-data" class="form">
            @csrf

            <div class="grid grid--2">
                <div class="field">
                    <label class="label">Catégorie <span class="req">*</span></label>
                    <input class="input" name="category" value="{{ old('category') }}" placeholder="formation, fete, visite..." required>
                    <div class="hint">Exemple : formation, fête, visite, sport, conférence…</div>
                </div>

                <div class="field">
                    <label class="label">Date de l’événement <span class="req">*</span></label>
                    <input class="input" type="date" name="event_date" value="{{ old('event_date') }}" required>
                </div>

                <div class="field field--full">
                    <label class="label">Titre (optionnel)</label>
                    <input class="input" name="title" value="{{ old('title') }}" placeholder="Ex: Formation Laravel - Jour 1">
                </div>

                <div class="field field--full">
                    <label class="label">Description (optionnel)</label>
                    <textarea class="input" name="description" rows="4" placeholder="Quelques détails…">{{ old('description') }}</textarea>
                </div>

                <div class="field field--full">
                    <label class="label">Images <span class="req">*</span></label>
                    <input class="input" type="file" name="images[]" accept="image/*" multiple required>
                    <div class="hint">JPG/PNG/WEBP, max 4 Mo par image. Tu peux en sélectionner plusieurs.</div>
                </div>

                <div class="field">
                    <label class="label">Publier maintenant</label>
                    <label style="display:flex; gap:10px; align-items:center;">
                        <input type="checkbox" name="is_published" value="1" {{ old('is_published', true) ? 'checked' : '' }}>
                        <span class="hint">Si décoché : les photos restent invisibles sur le public.</span>
                    </label>
                </div>
            </div>

            <div class="card__foot" style="padding:0; margin-top:12px; justify-content:flex-end;">
                <button class="btn btn--primary" type="submit">Enregistrer</button>
                <a class="btn" href="{{ route('admin.galerie.index') }}">Annuler</a>
            </div>
        </form>
    </div>
</div>

@endsection
