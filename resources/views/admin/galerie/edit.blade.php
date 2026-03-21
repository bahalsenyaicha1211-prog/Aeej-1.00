@extends('layouts.admin')

@section('title', 'Admin • Modifier photo')
@section('header', 'Édition Galerie')

@section('content')
<div class="admDash">
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">Modifier la photo</h1>
            <p class="admDash__sub">Mise à jour des métadonnées de l'image.</p>
        </div>
        <a class="admQuick__btn" href="{{ route('admin.galerie.index') }}" style="text-decoration:none;">← Retour</a>
    </div>

    <div class="admGrid">
        {{-- Preview --}}
        <div class="admPanel" style="grid-column: span 5;">
            <div class="admPanel__head"><h2 class="admPanel__h text-white">Aperçu actuel</h2></div>
            <div class="admPanel__body" style="text-align:center;">
                <img src="{{ $photo->image_url }}" alt="Photo" style="width:100%; border-radius:15px; border:1px solid rgba(255,255,255,0.1); box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
            </div>
        </div>

        {{-- Form --}}
        <div class="admPanel" style="grid-column: span 7;">
            <div class="admPanel__body">
                <form method="POST" action="{{ route('admin.galerie.update', $photo) }}" enctype="multipart/form-data" class="admRows">
                    @csrf @method('PUT')

                    <div class="field">
                        <label class="admKpi__label text-white">Titre</label>
                        <input class="input" name="title" value="{{ old('title', $photo->title) }}">
                    </div>

                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px;">
                        <div class="field">
                            <label class="admKpi__label text-white">Catégorie</label>
                            <input class="input" name="category" value="{{ old('category', $photo->category) }}" required>
                        </div>
                        <div class="field">
                            <label class="admKpi__label text-white">Date</label>
                            <input class="input" type="date" name="event_date" value="{{ old('event_date', optional($photo->event_date)->format('Y-m-d')) }}" required>
                        </div>
                    </div>

                    <div class="field">
                        <label class="admKpi__label text-white">Description</label>
                        <textarea class="input" name="description" rows="3">{{ old('description', $photo->description) }}</textarea>
                    </div>

                    <div class="field">
                        <label class="admKpi__label text-white">Remplacer l'image (optionnel)</label>
                        <input class="input" type="file" name="image" accept="image/*" style="font-size:12px;">
                    </div>

                    <div class="admRow" style="justify-content: flex-start; gap: 15px;">
                        <input type="checkbox" name="is_published" value="1" {{ old('is_published', $photo->is_published) ? 'checked' : '' }} style="width:20px; height:20px; accent-color:#3b82f6;">
                        <label class="text-white">Photo publiée</label>
                    </div>

                    <div style="margin-top:20px; display:flex; gap:12px;">
                        <button type="submit" class="btn" style="background:#3b82f6; color:#fff; border-radius:12px; padding:12px 30px; font-weight:800; border:none; cursor:pointer;">Enregistrer les modifications</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection