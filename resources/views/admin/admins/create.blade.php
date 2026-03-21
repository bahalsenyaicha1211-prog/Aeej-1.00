@extends('layouts.admin')

@section('title', 'Admin • Nouveau compte')
@section('header', 'Sécurité')

@section('content')
<div class="admDash">
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">Ajouter un administrateur</h1>
            <p class="admDash__sub">Créez un nouvel accès privilégié à la plateforme.</p>
        </div>
        <a class="admQuick__btn" href="{{ route('admin.admins.index') }}" style="text-decoration: none;">← Retour</a>
    </div>

    <div class="admGrid">
        <div class="admPanel" style="grid-column: span 8;">
            <div class="admPanel__body">
                <form class="admRows" method="POST" action="{{ route('admin.admins.store') }}">
                    @csrf
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="field">
                            <label class="admKpi__label text-white">Nom complet *</label>
                            <input class="input" name="name" value="{{ old('name') }}" required placeholder="ex: Alseny Bah">
                            @error('name') <div style="color:#fb7185; font-size: 11px; margin-top: 5px;">⚠️ {{ $message }}</div> @enderror
                        </div>

                        <div class="field">
                            <label class="admKpi__label text-white">Adresse Email *</label>
                            <input class="input" type="email" name="email" value="{{ old('email') }}" required placeholder="admin@aeeg.test">
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

                    <div style="margin-top: 30px; display: flex; gap: 12px; align-items: center;">
                        <button class="btn" style="background: #22c55e; color: #fff; border-radius: 12px; padding: 12px 30px; font-weight: 800; border: none; cursor: pointer;" type="submit">
                            Créer le compte
                        </button>
                        <a href="{{ route('admin.admins.index') }}" style="color: #64748b; font-size: 14px; text-decoration: none; font-weight: 600;">Annuler</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="admPanel" style="grid-column: span 4; background: rgba(59, 130, 246, 0.05); border-color: rgba(59, 130, 246, 0.2);">
            <div class="admPanel__body">
                <h3 style="color: #60a5fa; font-size: 14px; font-weight: 800; margin-bottom: 10px;">🛡️ Sécurité</h3>
                <p style="color: #94a3b8; font-size: 12px; line-height: 1.6;">
                    Le mot de passe doit contenir au moins 8 caractères. Par défaut, le nouvel utilisateur sera créé avec un rôle <strong>ADMIN standard</strong>. Seul un Super Admin pourra modifier son rôle ultérieurement.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection