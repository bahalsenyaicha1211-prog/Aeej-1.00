@extends('layouts.admin')

@section('title', 'Admin • Modifier compte trésorerie')
@section('header', 'Trésorerie')

@php
    $roleActuel = $compte->is_chef_tresorier ? 'chef_tresorier' : ($compte->is_commissaire_comptes ? 'commissaire' : 'tresorier');
@endphp

@section('content')
<div class="admDash">
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">Modifier le compte</h1>
            <p class="admDash__sub">{{ $compte->name }}</p>
        </div>
        <a class="admQuick__btn" href="{{ route('admin.tresorerie-comptes.index') }}" style="text-decoration: none;">← Retour</a>
    </div>

    <div class="admGrid">
        <div class="admPanel" style="grid-column: span 8;">
            <div class="admPanel__body">
                <form class="admRows" method="POST" action="{{ route('admin.tresorerie-comptes.update', $compte) }}">
                    @csrf
                    @method('PUT')
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="field">
                            <label class="admKpi__label text-white">Nom complet *</label>
                            <input class="input" name="name" value="{{ old('name', $compte->name) }}" required>
                            @error('name') <div style="color:#fb7185; font-size: 11px; margin-top: 5px;">⚠️ {{ $message }}</div> @enderror
                        </div>

                        <div class="field">
                            <label class="admKpi__label text-white">Adresse Email *</label>
                            <input class="input" type="email" name="email" value="{{ old('email', $compte->email) }}" required>
                            @error('email') <div style="color:#fb7185; font-size: 11px; margin-top: 5px;">⚠️ {{ $message }}</div> @enderror
                        </div>

                        <div class="field">
                            <label class="admKpi__label text-white">Nouveau mot de passe</label>
                            <input class="input" type="password" name="password" placeholder="Laisser vide pour ne pas changer">
                            @error('password') <div style="color:#fb7185; font-size: 11px; margin-top: 5px;">⚠️ {{ $message }}</div> @enderror
                        </div>

                        <div class="field">
                            <label class="admKpi__label text-white">Confirmation</label>
                            <input class="input" type="password" name="password_confirmation">
                        </div>
                    </div>

                    <div class="field" style="margin-top: 20px;">
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
                        @error('role_tresorier') <div style="color:#fb7185; font-size: 11px; margin-top: 8px;">⚠️ {{ $message }}</div> @enderror
                    </div>

                    <div style="margin-top: 30px; display: flex; gap: 12px; align-items: center;">
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
