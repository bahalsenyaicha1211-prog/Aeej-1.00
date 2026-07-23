@extends('layouts.admin')

@section('title', 'Admin • Modifier rôle trésorerie')
@section('header', 'Trésorerie')

@php
    $roleActuel = $compte->is_chef_tresorier ? 'chef_tresorier' : ($compte->is_commissaire_comptes ? 'commissaire' : 'tresorier');
@endphp

@section('content')
<div class="admDash">
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">Modifier le rôle</h1>
            <p class="admDash__sub">{{ $compte->name }} @if($compte->matricule) — {{ $compte->matricule }} @endif</p>
        </div>
        <a class="admQuick__btn" href="{{ route('admin.tresorerie-comptes.index') }}" style="text-decoration: none;">← Retour</a>
    </div>

    @if($errors->any())
        <div class="admPanel admPanel--full" style="border-color: rgba(239,68,68,0.3); background: rgba(239,68,68,0.05); margin-bottom: 16px;">
            <div class="admPanel__body">
                @foreach($errors->all() as $error)
                    <div style="color:#fb7185; font-size: 13px; font-weight: 600;">⚠️ {{ $error }}</div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="admGrid">
        <div class="admPanel" style="grid-column: span 8;">
            <div class="admPanel__body">
                <form class="admRows" method="POST" action="{{ route('admin.tresorerie-comptes.update', $compte) }}">
                    @csrf
                    @method('PUT')

                    <div class="field" style="margin-bottom: 20px;">
                        <label class="admKpi__label text-white">Membre</label>
                        <input class="input" value="{{ $compte->name }} ({{ $compte->email }})" disabled style="width:100%; opacity:0.7;">
                    </div>

                    <div class="field">
                        <label class="admKpi__label text-white" style="display:block; margin-bottom: 10px;">Rôle *</label>
                        <div style="display: flex; flex-direction: column; gap: 10px;">
                            <label style="display:flex; align-items:center; gap:10px; color:#e2e8f0; font-size:13px; cursor:pointer;">
                                <input type="radio" name="role_tresorier" value="tresorier" {{ old('role_tresorier', $roleActuel) === 'tresorier' ? 'checked' : '' }}>
                                Trésorier — peut enregistrer des paiements de cotisation
                            </label>
                            <label style="display:flex; align-items:center; gap:10px; color:#e2e8f0; font-size:13px; cursor:pointer;">
                                <input type="radio" name="role_tresorier" value="chef_tresorier" {{ old('role_tresorier', $roleActuel) === 'chef_tresorier' ? 'checked' : '' }}>
                                Chef trésorier — voit tous les paiements et la caisse (un seul à la fois, remplace l'ancien chef)
                            </label>
                            <label style="display:flex; align-items:center; gap:10px; color:#e2e8f0; font-size:13px; cursor:pointer;">
                                <input type="radio" name="role_tresorier" value="commissaire" {{ old('role_tresorier', $roleActuel) === 'commissaire' ? 'checked' : '' }}>
                                Commissaire aux comptes — enregistre les dépenses et voit la caisse
                            </label>
                        </div>
                    </div>

                    <p style="color:#64748b; font-size:12px; margin-top:16px;">
                        Passer à "Trésorier" ou "Chef trésorier" n'est possible que si ce membre a le poste Trésorier/Trésorière dans le bureau.
                    </p>

                    <div style="margin-top: 20px; display: flex; gap: 12px; align-items: center;">
                        <button class="btn" style="background: #3b82f6; color: #fff; border-radius: 12px; padding: 12px 30px; font-weight: 800; border: none; cursor: pointer;" type="submit">
                            Mettre à jour
                        </button>
                        <a href="{{ route('admin.tresorerie-comptes.index') }}" style="color: #64748b; font-size: 14px; text-decoration: none; font-weight: 600;">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
