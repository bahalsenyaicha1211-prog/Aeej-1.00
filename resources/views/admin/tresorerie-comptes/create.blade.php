@extends('layouts.admin')

@section('title', 'Admin • Nouveau compte trésorerie')
@section('header', 'Trésorerie')

@section('content')
<div class="admDash">
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">Ajouter un compte trésorerie</h1>
            <p class="admDash__sub">Trésorier, chef trésorier ou commissaire aux comptes.</p>
        </div>
        <a class="admQuick__btn" href="{{ route('admin.tresorerie-comptes.index') }}" style="text-decoration: none;">← Retour</a>
    </div>

    <div class="admGrid">
        <div class="admPanel" style="grid-column: span 8;">
            <div class="admPanel__body">
                <form class="admRows" method="POST" action="{{ route('admin.tresorerie-comptes.store') }}">
                    @csrf
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="field">
                            <label class="admKpi__label text-white">Nom complet *</label>
                            <input class="input" name="name" value="{{ old('name') }}" required placeholder="ex: Mariam Diallo">
                            @error('name') <div style="color:#fb7185; font-size: 11px; margin-top: 5px;">⚠️ {{ $message }}</div> @enderror
                        </div>

                        <div class="field">
                            <label class="admKpi__label text-white">Adresse Email *</label>
                            <input class="input" type="email" name="email" value="{{ old('email') }}" required placeholder="tresorier@aeej.test">
                            @error('email') <div style="color:#fb7185; font-size: 11px; margin-top: 5px;">⚠️ {{ $message }}</div> @enderror
                        </div>

                        <div class="field">
                            <label class="admKpi__label text-white">Mot de passe *</label>
                            <input class="input" type="password" name="password" required placeholder="••••••••">
                            @error('password') <div style="color:#fb7185; font-size: 11px; margin-top: 5px;">⚠️ {{ $message }}</div> @enderror
                        </div>

                        <div class="field">
                            <label class="admKpi__label text-white">Confirmation *</label>
                            <input class="input" type="password" name="password_confirmation" required placeholder="••••••••">
                        </div>
                    </div>

                    <div class="field" style="margin-top: 20px;">
                        <label class="admKpi__label text-white" style="display:block; margin-bottom: 10px;">Rôle *</label>
                        <div style="display: flex; flex-direction: column; gap: 10px;">
                            <label style="display:flex; align-items:center; gap:10px; color:#e2e8f0; font-size:13px; cursor:pointer;">
                                <input type="radio" name="role_tresorier" value="tresorier" {{ old('role_tresorier') === 'tresorier' ? 'checked' : '' }} required>
                                Trésorier — peut enregistrer des paiements de cotisation
                            </label>
                            <label style="display:flex; align-items:center; gap:10px; color:#e2e8f0; font-size:13px; cursor:pointer;">
                                <input type="radio" name="role_tresorier" value="chef_tresorier" {{ old('role_tresorier') === 'chef_tresorier' ? 'checked' : '' }}>
                                Chef trésorier — voit tous les paiements et la caisse (un seul à la fois, remplace l'ancien chef)
                            </label>
                            <label style="display:flex; align-items:center; gap:10px; color:#e2e8f0; font-size:13px; cursor:pointer;">
                                <input type="radio" name="role_tresorier" value="commissaire" {{ old('role_tresorier') === 'commissaire' ? 'checked' : '' }}>
                                Commissaire aux comptes — enregistre les dépenses et voit la caisse
                            </label>
                        </div>
                        @error('role_tresorier') <div style="color:#fb7185; font-size: 11px; margin-top: 8px;">⚠️ {{ $message }}</div> @enderror
                    </div>

                    <div style="margin-top: 30px; display: flex; gap: 12px; align-items: center;">
                        <button class="btn" style="background: #22c55e; color: #fff; border-radius: 12px; padding: 12px 30px; font-weight: 800; border: none; cursor: pointer;" type="submit">
                            Créer le compte
                        </button>
                        <a href="{{ route('admin.tresorerie-comptes.index') }}" style="color: #64748b; font-size: 14px; text-decoration: none; font-weight: 600;">Annuler</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="admPanel" style="grid-column: span 4; background: rgba(59, 130, 246, 0.05); border-color: rgba(59, 130, 246, 0.2);">
            <div class="admPanel__body">
                <h3 style="color: #60a5fa; font-size: 14px; font-weight: 800; margin-bottom: 10px;">💰 Trésorerie</h3>
                <p style="color: #94a3b8; font-size: 12px; line-height: 1.6;">
                    Désigner un nouveau chef trésorier retire automatiquement ce statut à l'ancien (il redevient trésorier simple).
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
