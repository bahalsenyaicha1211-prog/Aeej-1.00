@extends('layouts.admin')

@section('title', 'Admin • Modifier département')
@section('header', 'Mise à jour')

@section('content')
<div class="admDash">
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">Modifier le département</h1>
            <p class="admDash__sub">Édition de : <span style="color: #4ade80;">{{ $departement->nom }}</span></p>
        </div>
        <a class="admQuick__btn" href="{{ route('admin.departements.index') }}" style="text-decoration: none;">← Retour</a>
    </div>

    <div class="admGrid">
        <div class="admPanel" style="grid-column: span 7;">
            <div class="admPanel__body">
                <form class="admRows" method="POST" action="{{ route('admin.departements.update', $departement) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="field">
                        <label class="admKpi__label text-white" style="display: block; margin-bottom: 10px;">Nom du département</label>
                        <input class="input" name="nom" value="{{ old('nom', $departement->nom) }}" required style="width: 100%;">
                        @error('nom') 
                            <div style="color:#fb7185; font-size: 12px; margin-top: 8px; font-weight: 600;">⚠️ {{ $message }}</div> 
                        @enderror
                    </div>

                    <div style="margin-top: 30px; display: flex; gap: 12px; align-items: center;">
                        <button class="btn" style="background: #3b82f6; color: #fff; border-radius: 12px; padding: 12px 30px; font-weight: 800; cursor: pointer; border: none;" type="submit">
                            Mettre à jour
                        </button>
                        <a href="{{ route('admin.departements.index') }}" style="color: #64748b; font-size: 14px; text-decoration: none; font-weight: 600;">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection