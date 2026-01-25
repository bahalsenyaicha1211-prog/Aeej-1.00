@extends('layouts.admin')

@section('title', 'Admin • Modifier annonce')
@section('header', 'Annonces')

@section('content')

<div class="page__head">
    <div>
        <h1 class="page__title">Modifier l’annonce</h1>
        <p class="page__subtitle">
            Mets à jour le contenu, l’image et le statut de publication.
        </p>
    </div>

    <div class="page__actions">
        <a href="{{ route('admin.annonces.index') }}" class="btn">
            ← Retour aux annonces
        </a>
    </div>
</div>

<div class="card">
    <div class="card__body">

        {{-- Erreurs --}}
        @if ($errors->any())
            <div class="flash flash--error" style="margin-bottom:14px;">
                <strong>Erreurs :</strong>
                <ul style="margin:8px 0 0; padding-left:18px;">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST"
              action="{{ route('admin.annonces.update', $annonce) }}"
              enctype="multipart/form-data"
              class="form">

            @csrf
            @method('PUT')

            {{-- Contenu --}}
            <div class="field">
                <label class="label">Contenu <span class="req">*</span></label>
                <textarea name="contenu"
                          class="input"
                          rows="6"
                          required>{{ old('contenu', $annonce->contenu) }}</textarea>
            </div>

            {{-- Image existante --}}
            @if($annonce->image_path)
                <div class="field">
                    <label class="label">Image actuelle</label>

                    <div style="margin-bottom:10px;">
                        <img src="{{ asset('storage/'.$annonce->image_path) }}"
                             alt="Image annonce"
                             style="max-width:100%; border-radius:14px; border:1px solid rgba(255,255,255,.12)">
                    </div>

                    <label style="display:flex; gap:8px; align-items:center;">
                        <input type="checkbox" name="remove_image" value="1">
                        <span class="hint">Supprimer l’image actuelle</span>
                    </label>
                </div>
            @endif

            {{-- Nouvelle image --}}
            <div class="field">
                <label class="label">Remplacer / ajouter une image</label>
                <input type="file" name="image" class="input" accept="image/*">
                <div class="hint">JPG, PNG, WEBP — max 2 Mo</div>
            </div>

            {{-- Options --}}
            <div class="grid grid--2">

                <div class="field">
                    <label class="label">Publier</label>
                    <label style="display:flex; gap:10px; align-items:center;">
                        <input type="checkbox"
                               name="is_published"
                               value="1"
                               {{ old('is_published', $annonce->is_published) ? 'checked' : '' }}>
                        <span class="hint">Visible pour les membres</span>
                    </label>
                </div>

                <div class="field">
                    <label class="label">Épingler</label>
                    <label style="display:flex; gap:10px; align-items:center;">
                        <input type="checkbox"
                               name="is_pinned"
                               value="1"
                               {{ old('is_pinned', $annonce->is_pinned) ? 'checked' : '' }}>
                        <span class="hint">Annonce mise en avant</span>
                    </label>
                </div>

            </div>

            {{-- Actions --}}
            <div class="card__foot">
                <button type="submit" class="btn btn--primary">
                    Enregistrer les modifications
                </button>

                <a href="{{ route('admin.annonces.index') }}" class="btn">
                    Annuler
                </a>
            </div>

        </form>

    </div>
</div>

@endsection
