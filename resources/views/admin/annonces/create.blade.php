@extends('layouts.admin')

@section('title', 'Admin • Nouvelle annonce')
@section('header', 'Rédaction')

@section('content')
<div class="admDash">
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">Nouvelle annonce</h1>
            <p class="admDash__sub">Rédigez un message percutant pour la communauté.</p>
        </div>
        <a class="admQuick__btn" href="{{ route('admin.annonces.index') }}" style="text-decoration: none;">← Retour aux annonces</a>
    </div>

    <form method="POST" action="{{ route('admin.annonces.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="admGrid">
            {{-- Zone de texte principale --}}
            <div class="admPanel" style="grid-column: span 8;">
                <div class="admPanel__head"><h2 class="admPanel__h text-white">Corps de l'annonce</h2></div>
                <div class="admPanel__body">
                    <div class="field">
                        <textarea class="input" name="contenu" rows="12" required placeholder="Tapez votre message ici..." style="width: 100%; line-height: 1.6; font-size: 15px; background: rgba(0,0,0,0.2);">{{ old('contenu') }}</textarea>
                        @error('contenu') <div style="color:#fb7185; font-size: 12px; margin-top: 10px;">⚠️ {{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            {{-- Colonne Options Latérale --}}
            <div class="admPanel" style="grid-column: span 4;">
                <div class="admPanel__head"><h2 class="admPanel__h text-white">Paramètres & Image</h2></div>
                <div class="admPanel__body">
                    {{-- Upload Image --}}
                    <div class="field" style="margin-bottom: 25px;">
                        <label class="admKpi__label text-white">Image d'illustration</label>
                        <input class="input" type="file" name="image" accept="image/*" style="font-size: 12px; margin-top: 10px; padding: 10px;">
                        <p style="font-size: 11px; color: #64748b; margin-top: 5px;">Recommandé : 1200x600px • Max 2Mo</p>
                    </div>

                    {{-- Toggles --}}
                    <div class="admRows" style="gap: 15px;">
                        <div class="admRow" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05);">
                            <label style="display:flex; gap:12px; align-items:center; cursor:pointer;">
                                <input type="checkbox" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }} style="width:18px; height:18px; accent-color:#22c55e;">
                                <span class="text-white font-bold">Publier maintenant</span>
                            </label>
                        </div>
                        <div class="admRow" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05);">
                            <label style="display:flex; gap:12px; align-items:center; cursor:pointer;">
                                <input type="checkbox" name="is_pinned" value="1" {{ old('is_pinned') ? 'checked' : '' }} style="width:18px; height:18px; accent-color:#3b82f6;">
                                <span class="text-white font-bold">Épingler en haut</span>
                            </label>
                        </div>
                    </div>

                    <div style="margin-top: 30px;">
                        <button type="submit" class="btn" style="width: 100%; background: #22c55e; color: #fff; border-radius: 12px; padding: 15px; font-weight: 800; border: none; cursor: pointer;">
                            Lancer la publication
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection