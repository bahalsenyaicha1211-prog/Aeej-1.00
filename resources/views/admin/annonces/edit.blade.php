@extends('layouts.admin')

@section('title', 'Admin • Modifier annonce')
@section('header', 'Édition')

@section('content')
<div class="admDash">
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">Modifier l'annonce</h1>
            <p class="admDash__sub">Mise à jour d'un message existant.</p>
        </div>
        <a class="admQuick__btn" href="{{ route('admin.annonces.index') }}" style="text-decoration: none;">← Annuler</a>
    </div>

    <form method="POST" action="{{ route('admin.annonces.update', $annonce) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="admGrid">
            <div class="admPanel" style="grid-column: span 8;">
                <div class="admPanel__head"><h2 class="admPanel__h text-white">Contenu du message</h2></div>
                <div class="admPanel__body">
                    <textarea class="input" name="contenu" rows="12" required style="width: 100%; line-height: 1.6; font-size: 15px; background: rgba(0,0,0,0.2);">{{ old('contenu', $annonce->contenu) }}</textarea>
                </div>
            </div>

            <div class="admPanel" style="grid-column: span 4;">
                <div class="admPanel__head"><h2 class="admPanel__h text-white">Options d'affichage</h2></div>
                <div class="admPanel__body">
                    {{-- Image actuelle --}}
                    @if($annonce->image_path)
                        <div style="margin-bottom: 20px;">
                            <label class="admKpi__label text-white">Image actuelle</label>
                            <img src="{{ asset('storage/'.$annonce->image_path) }}" style="width:100%; border-radius:12px; margin-top:10px; border:1px solid rgba(255,255,255,0.1);">
                            <label style="display:flex; gap:8px; align-items:center; margin-top:10px; color:#f87171; font-size:12px;">
                                <input type="checkbox" name="remove_image" value="1"> Retirer cette image
                            </label>
                        </div>
                    @endif

                    <div class="field" style="margin-bottom: 25px;">
                        <label class="admKpi__label text-white">Changer l'image</label>
                        <input class="input" type="file" name="image" accept="image/*" style="font-size:12px; margin-top:10px;">
                    </div>

                    <div class="admRows" style="gap: 15px;">
                        <div class="admRow"><label style="display:flex; gap:10px; align-items:center;"><input type="checkbox" name="is_published" value="1" {{ $annonce->is_published ? 'checked' : '' }}> <span class="text-white">Publiée</span></label></div>
                        <div class="admRow"><label style="display:flex; gap:10px; align-items:center;"><input type="checkbox" name="is_pinned" value="1" {{ $annonce->is_pinned ? 'checked' : '' }}> <span class="text-white">📌 Épinglée</span></label></div>
                    </div>

                    <button type="submit" class="btn" style="width: 100%; background: #3b82f6; color: #fff; border-radius: 12px; padding: 15px; font-weight: 800; border: none; margin-top: 25px;">
                        Sauvegarder les changements
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection