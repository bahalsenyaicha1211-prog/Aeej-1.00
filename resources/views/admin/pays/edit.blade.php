@extends('layouts.admin')

@section('title', 'Admin • Modifier pays')
@section('header', 'Mise à jour pays')

@section('content')
<div class="admDash">
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">Modifier le pays</h1>
            <p class="admDash__sub">Édition de l'entrée : <span style="color: #3b82f6;">{{ $pays->nom }}</span></p>
        </div>
        <a class="admQuick__btn" href="{{ route('admin.pays.index') }}" style="text-decoration: none;">← Retour</a>
    </div>

    <div class="admGrid">
        <div class="admPanel" style="grid-column: span 7;">
            <div class="admPanel__body">
                <form class="admRows" method="POST" action="{{ route('admin.pays.update', $pays) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="field">
                        <label class="admKpi__label text-white" style="display: block; margin-bottom: 10px;">Nom du pays</label>
                        <input class="input" name="nom" value="{{ old('nom', $pays->nom) }}" required style="width: 100%;">
                        @error('nom') 
                            <div style="color:#fb7185; font-size: 12px; margin-top: 8px; font-weight: 600;">⚠️ {{ $message }}</div> 
                        @enderror
                    </div>
                    <!-- signature -->
                    <div class="field" style="margin-top: 20px;">
                        <label class="admKpi__label text-white" style="display: block; margin-bottom: 10px;">Signature du matricule</label>
                        <input class="input" name="signature" value="{{ old('signature', $pays->signature) }}" required maxlength="2" style="width: 100px; text-transform: uppercase;">
                        @error('signature') 
                            <div style="color:#fb7185; font-size: 12px; margin-top: 8px; font-weight: 600;">⚠️ {{ $message }}</div> 
                        @enderror
                    </div>

                    <div style="margin-top: 30px; display: flex; gap: 12px; align-items: center;">
                        <button class="btn" style="background: #3b82f6; color: #fff; border-radius: 12px; padding: 12px 35px; font-weight: 800; cursor: pointer; border: none;" type="submit">
                            Mettre à jour
                        </button>
                        <a href="{{ route('admin.pays.index') }}" style="color: #64748b; font-size: 14px; text-decoration: none; font-weight: 600;">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection