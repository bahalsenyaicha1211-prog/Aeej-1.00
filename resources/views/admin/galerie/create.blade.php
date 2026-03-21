@extends('layouts.admin')

@section('title', 'Admin • Ajouter photos')
@section('header', 'Nouvel Upload')

@section('content')
<div class="admDash">
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">Ajouter des photos</h1>
            <p class="admDash__sub">Publication multiple possible (JPG, PNG, WEBP).</p>
        </div>
        <a class="admQuick__btn" href="{{ route('admin.galerie.index') }}" style="text-decoration:none;">← Retour</a>
    </div>

    <div class="admPanel admPanel--full">
        <div class="admPanel__body">
            <form method="POST" action="{{ route('admin.galerie.store') }}" enctype="multipart/form-data">
                @csrf
                <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap:25px;">
                    
                    {{-- Catégorie --}}
                    <div class="field">
                        <label class="admKpi__label text-white">Catégorie *</label>
                        <input class="input" name="category" value="{{ old('category') }}" placeholder="ex: Formation, Visite..." required>
                    </div>

                    {{-- Date --}}
                    <div class="field">
                        <label class="admKpi__label text-white">Date de l'événement *</label>
                        <input class="input" type="date" name="event_date" value="{{ old('event_date') }}" required>
                    </div>

                    {{-- Titre --}}
                    <div class="field" style="grid-column: span 2;">
                        <label class="admKpi__label text-white">Titre de l'album / de la photo</label>
                        <input class="input" name="title" value="{{ old('title') }}" placeholder="Ex: Cérémonie de remise des diplômes 2026">
                    </div>

                    {{-- Description --}}
                    <div class="field" style="grid-column: span 2;">
                        <label class="admKpi__label text-white">Description (optionnel)</label>
                        <textarea class="input" name="description" rows="3">{{ old('description') }}</textarea>
                    </div>

                    {{-- Upload --}}
                    <div class="field" style="grid-column: span 2; border: 2px dashed rgba(255,255,255,0.1); padding: 30px; border-radius: 20px; text-align: center;">
                        <label class="admKpi__label text-white" style="margin-bottom:15px; display:block;">Sélectionnez vos fichiers</label>
                        <input type="file" name="images[]" accept="image/*" multiple required style="color:#94a3b8;">
                        <p style="font-size:12px; color:#64748b; margin-top:10px;">Max 4 Mo par image. Maintenez CTRL pour en choisir plusieurs.</p>
                    </div>

                    {{-- Checkbox --}}
                    <div class="admRow" style="grid-column: span 2; justify-content: flex-start; gap: 15px; background: rgba(255,255,255,0.02);">
                        <input type="checkbox" name="is_published" value="1" {{ old('is_published', true) ? 'checked' : '' }} style="width:20px; height:20px; accent-color:#22c55e;">
                        <label>Publier immédiatement sur le site public</label>
                    </div>
                </div>

                <div style="margin-top:30px; display:flex; justify-content:flex-end; gap:12px;">
                    <button type="submit" class="btn" style="background:#22c55e; color:#fff; border-radius:12px; padding:12px 30px; font-weight:800; border:none; cursor:pointer;">Lancer l'importation</button>
                    <a href="{{ route('admin.galerie.index') }}" style="color:#64748b; text-decoration:none; font-weight:600; align-self:center;">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection