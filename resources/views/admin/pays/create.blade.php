@extends('layouts.admin')

@section('title', 'Admin • Nouveau pays')
@section('header', 'Ajouter un pays')

@section('content')
<div class="admDash">
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">Nouveau pays</h1>
            <p class="admDash__sub">Enregistrez une nouvelle destination pour vos membres.</p>
        </div>
        <a class="admQuick__btn" href="{{ route('admin.pays.index') }}" style="text-decoration: none;">← Retour à la liste</a>
    </div>

    <div class="admGrid">
        <div class="admPanel" style="grid-column: span 7;">
            <div class="admPanel__body">
                <form class="admRows" method="POST" action="{{ route('admin.pays.store') }}">
                    @csrf
                    <div class="field">
                        <label class="admKpi__label text-white" style="display: block; margin-bottom: 10px;">Nom complet du pays</label>
                        <input class="input" name="nom" value="{{ old('nom') }}" required placeholder="ex: Guinée, France, Sénégal..." style="width: 100%;">
                        @error('nom') 
                            <div style="color:#fb7185; font-size: 12px; margin-top: 8px; font-weight: 600;">⚠️ {{ $message }}</div> 
                        @enderror
                    </div>

                    <div style="margin-top: 30px; display: flex; gap: 12px; align-items: center;">
                        <button class="btn" style="background: #22c55e; color: #fff; border-radius: 12px; padding: 12px 35px; font-weight: 800; cursor: pointer; border: none;" type="submit">
                            Enregistrer le pays
                        </button>
                        <a href="{{ route('admin.pays.index') }}" style="color: #64748b; font-size: 14px; text-decoration: none; font-weight: 600;">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection