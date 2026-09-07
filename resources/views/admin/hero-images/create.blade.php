@extends('layouts.admin')

@section('title', 'Admin • Ajouter des photos d’accueil')
@section('header', 'Nouvelles photos du diaporama')

@section('styles')
    <script src="{{ asset('js/bulk-upload.js') }}?v={{ filemtime(public_path('js/bulk-upload.js')) }}" defer></script>
@endsection

@section('content')
<div class="admDash">
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">Ajouter des photos au diaporama</h1>
            <p class="admDash__sub">Ajout multiple possible (JPG, PNG, WEBP — max 4 Mo par image).</p>
        </div>
        <x-adm-back :href="route('admin.hero-images.index')" />
    </div>

    <div class="admPanel admPanel--full">
        <div class="admPanel__body">
            @if($errors->any())
                <div style="margin-bottom:20px; padding:12px 16px; border-radius:12px; background:rgba(239,68,68,0.08); border:1px solid rgba(239,68,68,0.25); color:#fb7185; font-size:13px; font-weight:600;">
                    ⚠️ {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.hero-images.store') }}" enctype="multipart/form-data"
                  data-bulk-upload
                  data-cloud-name="{{ config('services.cloudinary.cloud_name') }}"
                  data-upload-preset="{{ config('services.cloudinary.upload_preset') }}"
                  data-folder="accueil">
                @csrf
                <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap:25px;">

                    {{-- Texte alternatif --}}
                    <div class="field" style="grid-column: span 2;">
                        <label class="admKpi__label text-white">Texte alternatif (optionnel)</label>
                        <input class="input" name="alt" value="{{ old('alt') }}" placeholder="Ex: Soirée d'intégration 2026">
                        <p style="font-size:12px; color:#64748b; margin-top:6px;">Décrit l'image pour l'accessibilité. Appliqué à toutes les images de cet envoi.</p>
                    </div>

                    {{-- Upload --}}
                    <div class="field" style="grid-column: span 2; border: 2px dashed rgba(255,255,255,0.1); padding: 30px; border-radius: 20px; text-align: center;">
                        <label class="admKpi__label text-white" style="margin-bottom:15px; display:block;">Sélectionnez vos fichiers</label>
                        <input type="file" name="images[]" accept="image/*" multiple required style="color:#94a3b8;">
                        <p style="font-size:12px; color:#64748b; margin-top:10px;">Maintenez CTRL pour en choisir plusieurs. Les nouvelles photos sont ajoutées à la fin du diaporama.</p>
                        @error('images.*') <div style="color:#fb7185; font-size: 12px; margin-top: 8px;">⚠️ {{ $message }}</div> @enderror
                        @error('images') <div style="color:#fb7185; font-size: 12px; margin-top: 8px;">⚠️ {{ $message }}</div> @enderror
                    </div>

                    {{-- Checkbox --}}
                    <div class="admRow" style="grid-column: span 2; justify-content: flex-start; gap: 15px; background: rgba(255,255,255,0.02);">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} style="width:20px; height:20px; accent-color:#22c55e;">
                        <label>Afficher immédiatement sur la page d'accueil</label>
                    </div>
                </div>

                <div style="margin-top:30px; display:flex; justify-content:flex-end; gap:12px;">
                    <button type="submit" class="btn" style="background:#22c55e; color:#fff; border-radius:12px; padding:12px 30px; font-weight:800; border:none; cursor:pointer;">Ajouter au diaporama</button>
                    <a href="{{ route('admin.hero-images.index') }}" style="color:#64748b; text-decoration:none; font-weight:600; align-self:center;">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
