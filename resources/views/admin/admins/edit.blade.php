@extends('layouts.admin')

@section('title', 'Admin • Modifier admin')
@section('header', 'Mise à jour accès')

@section('content')
<div class="admDash">
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">Modifier l'administrateur</h1>
            <p class="admDash__sub">Édition du compte de <span style="color:#60a5fa;">{{ $admin->name }}</span></p>
        </div>
        <a class="admQuick__btn" href="{{ route('admin.admins.index') }}" style="text-decoration: none;">← Retour</a>
    </div>

    <div class="admGrid">
        <div class="admPanel" style="grid-column: span 8;">
            <div class="admPanel__body">
                <form class="admRows" method="POST" action="{{ route('admin.admins.update', $admin) }}">
                    @csrf @method('PUT')

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
                        <div class="field">
                            <label class="admKpi__label text-white">Nom</label>
                            <input class="input" name="name" value="{{ old('name', $admin->name) }}" required>
                        </div>
                        <div class="field">
                            <label class="admKpi__label text-white">Email</label>
                            <input class="input" type="email" name="email" value="{{ old('email', $admin->email) }}" required>
                        </div>
                    </div>

                    <div style="background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.05); border-radius: 15px; padding: 20px;">
                        <h3 style="color:#fff; font-size:13px; font-weight:800; margin-bottom:15px; text-transform:uppercase; letter-spacing:1px;">Changer le mot de passe</h3>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div class="field">
                                <label class="admKpi__label text-white">Nouveau mot de passe</label>
                                <input class="input" type="password" name="password" placeholder="Laisse vide pour garder l'actuel">
                            </div>
                            <div class="field">
                                <label class="admKpi__label text-white">Confirmer le mot de passe</label>
                                <input class="input" type="password" name="password_confirmation" placeholder="Répéter le mot de passe">
                            </div>
                        </div>
                        <p style="color:#64748b; font-size:11px; margin-top:10px;">Ne remplir que si vous souhaitez forcer un nouveau mot de passe pour cet utilisateur.</p>
                    </div>

                    <div style="margin-top: 30px; display: flex; gap: 12px;">
                        <button class="btn" style="background: #3b82f6; color: #fff; border-radius: 12px; padding: 12px 30px; font-weight: 800; border: none; cursor: pointer;" type="submit">
                            Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection