@extends('layouts.admin')

@section('title', 'Admin • Modifier une photo d’accueil')
@section('header', 'Édition diaporama')

@section('content')
<div class="admDash">
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">Modifier la photo</h1>
            <p class="admDash__sub">Ordre d'affichage, visibilité et remplacement de l'image.</p>
        </div>
        <x-adm-back :href="route('admin.hero-images.index')" />
    </div>

    <div class="admGrid">
        {{-- Aperçu --}}
        <div class="admPanel" style="grid-column: span 5;">
            <div class="admPanel__head"><h2 class="admPanel__h text-white">Aperçu actuel</h2></div>
            <div class="admPanel__body" style="text-align:center;">
                <img src="{{ $image->image_url }}" alt="{{ $image->alt }}" style="width:100%; border-radius:15px; border:1px solid rgba(255,255,255,0.1); box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
            </div>
        </div>

        {{-- Formulaire --}}
        <div class="admPanel" style="grid-column: span 7;">
            <div class="admPanel__body">
                <form method="POST" action="{{ route('admin.hero-images.update', $image) }}" enctype="multipart/form-data" class="admRows">
                    @csrf @method('PUT')

                    <div class="field">
                        <label class="admKpi__label text-white">Texte alternatif (accessibilité)</label>
                        <input class="input" name="alt" value="{{ old('alt', $image->alt) }}" placeholder="Ex: Soirée d'intégration 2026">
                        @error('alt') <div style="color:#fb7185; font-size: 12px; margin-top: 5px;">⚠️ {{ $message }}</div> @enderror
                    </div>

                    <div class="field">
                        <label class="admKpi__label text-white">Ordre d'affichage</label>
                        <input class="input" type="number" name="position" min="0" value="{{ old('position', $image->position) }}" required>
                        <p style="font-size:12px; color:#64748b; margin-top:6px;">Plus le nombre est petit, plus la photo apparaît tôt dans le diaporama.</p>
                        @error('position') <div style="color:#fb7185; font-size: 12px; margin-top: 5px;">⚠️ {{ $message }}</div> @enderror
                    </div>

                    <div class="field">
                        <label class="admKpi__label text-white">Remplacer l'image (optionnel)</label>
                        <input class="input" type="file" name="image" accept="image/*" style="font-size:12px;">
                        @error('image') <div style="color:#fb7185; font-size: 12px; margin-top: 5px;">⚠️ {{ $message }}</div> @enderror
                    </div>

                    <div class="admRow" style="justify-content: flex-start; gap: 15px;">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $image->is_active) ? 'checked' : '' }} style="width:20px; height:20px; accent-color:#3b82f6;">
                        <label class="text-white">Photo affichée sur la page d'accueil</label>
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
