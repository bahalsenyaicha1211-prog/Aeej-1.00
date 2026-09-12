@extends('layouts.admin')

@section('title', 'Admin • Modifier photo')
@section('header', 'Édition Galerie')

@section('styles')
    <script src="{{ asset_v('js/image-upload.js') }}?v={{ filemtime(public_path('js/image-upload.js')) }}" defer></script>
@endsection

@section('content')
<div class="admDash">
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">Modifier la photo</h1>
            <p class="admDash__sub">Mise à jour des métadonnées de l'image.</p>
        </div>
        <x-adm-back :href="route('admin.galerie.index')" />
    </div>

    <div class="admPanel admPanel--full">
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
                        <label class="admKpi__label text-white">Image</label>
                        <x-image-upload name="image" folder="galerie" label="Photo de la galerie"
                                        :current="$photo->image_url"
                                        hint="JPG, PNG ou WEBP — 4 Mo max." />
                        @error('image') <div style="color:#fb7185; font-size: 12px; margin-top: 5px;">⚠️ {{ $message }}</div> @enderror
                        @error('image_url') <div style="color:#fb7185; font-size: 12px; margin-top: 5px;">⚠️ {{ $message }}</div> @enderror
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
@endsection