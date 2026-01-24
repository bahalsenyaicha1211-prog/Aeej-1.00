@extends('layouts.admin')

@section('title', 'Admin • Modifier photo')
@section('header', 'Galerie - Modifier')

@section('content')

<div class="page__head">
    <div>
        <h1 class="page__title">Modifier une photo</h1>
        <p class="page__subtitle">Mets à jour la catégorie, la date, et le statut de publication.</p>
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

        <div class="gal-edit">
            <div class="gal-edit__preview">
                <img src="{{ $photo->image_url }}" alt="Photo">
            </div>

            <form method="POST" action="{{ route('admin.galerie.update', $photo) }}"
                  enctype="multipart/form-data" class="form">
                @csrf
                @method('PUT')

                <div class="grid grid--2">
                    <div class="field">
                        <label class="label">Catégorie <span class="req">*</span></label>
                        <input class="input" name="category" value="{{ old('category', $photo->category) }}" required>
                    </div>

                    <div class="field">
                        <label class="label">Date de l’événement <span class="req">*</span></label>
                        <input class="input" type="date" name="event_date"
                               value="{{ old('event_date', optional($photo->event_date)->format('Y-m-d')) }}" required>
                    </div>

                    <div class="field field--full">
                        <label class="label">Titre</label>
                        <input class="input" name="title" value="{{ old('title', $photo->title) }}">
                    </div>

                    <div class="field field--full">
                        <label class="label">Description</label>
                        <textarea class="input" name="description" rows="4">{{ old('description', $photo->description) }}</textarea>
                    </div>

                    <div class="field field--full">
                        <label class="label">Remplacer l’image (optionnel)</label>
                        <input class="input" type="file" name="image" accept="image/*">
                        <div class="hint">Si tu choisis une nouvelle image, l’ancienne sera supprimée du storage.</div>
                    </div>

                    <div class="field">
                        <label class="label">Publier</label>
                        <label style="display:flex; gap:10px; align-items:center;">
                            <input type="checkbox" name="is_published" value="1" {{ old('is_published', $photo->is_published) ? 'checked' : '' }}>
                            <span class="hint">Visible sur le public.</span>
                        </label>
                    </div>
                </div>

                <div class="actions" style="justify-content:flex-end; margin-top:12px;">
                    <button class="btn btn--primary" type="submit">Enregistrer</button>
                    <a class="btn" href="{{ route('admin.galerie.index') }}">Annuler</a>
                </div>
            </form>
        </div>

    </div>
</div>

@endsection
