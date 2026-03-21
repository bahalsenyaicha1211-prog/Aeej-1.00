@extends('layouts.admin')

@section('title', 'Admin • Profil')
@section('header', 'Fiche Membre')

@section('content')
@php
    $u = $membre->user ?? null;
    $avatarName = trim(($membre->prenom ?? '').' '.($membre->nom ?? ''));
    $nameParts = explode(' ', $avatarName);
    $initials = strtoupper(substr($nameParts[0], 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : ''));
@endphp

<div class="admDash">
    <div class="admDash__head">
        <div style="display:flex; gap:20px; align-items:center;">
            {{-- Avatar Initials --}}
            <div style="width:70px; height:70px; border-radius:20px; background:linear-gradient(45deg, #2563eb, #3b82f6); color:#fff; display:flex; align-items:center; justify-content:center; font-size:24px; font-weight:900; box-shadow: 0 10px 20px rgba(37, 99, 235, 0.2);">
                {{ $initials }}
            </div>
            <div>
                <h1 class="admDash__title text-white">{{ $membre->prenom }} {{ $membre->nom }}</h1>
                <p class="admDash__sub">Matricule : <span style="color:#4ade80; font-family:monospace;">{{ $membre->matricule }}</span></p>
            </div>
        </div>
        <div style="display:flex; gap:10px;">
            <a class="admQuick__btn" href="{{ route('admin.membres.edit', $membre) }}" style="background:rgba(59, 130, 246, 0.1); color:#60a5fa; text-decoration:none;">Modifier le profil</a>
            <a class="admQuick__btn" href="{{ route('admin.membres.index') }}" style="text-decoration:none;">← Retour</a>
        </div>
    </div>

    <div class="admGrid">
        {{-- Colonne Infos --}}
        <div class="admPanel">
            <div class="admPanel__head"><h2 class="admPanel__h text-white">Informations Générales</h2></div>
            <div class="admPanel__body">
                <div class="admRows">
                    <div class="admRow"><span style="color:#64748b;">Sexe</span> <span class="text-white font-bold">{{ $membre->sexe }}</span></div>
                    <div class="admRow"><span style="color:#64748b;">Département</span> <span class="text-white font-bold">{{ $membre->departement?->nom ?? '—' }}</span></div>
                    <div class="admRow"><span style="color:#64748b;">Pays de résidence</span> <span class="text-white font-bold">{{ $membre->pays?->nom ?? '—' }}</span></div>
                    <div class="admRow"><span style="color:#64748b;">Année d'adhésion</span> <span style="color:#a78bfa; font-weight:800;">{{ $membre->annee_adhesion }}</span></div>
                </div>
            </div>
        </div>

        {{-- Colonne Contact --}}
        <div class="admPanel">
            <div class="admPanel__head"><h2 class="admPanel__h text-white">Coordonnées & Compte</h2></div>
            <div class="admPanel__body">
                <div class="admRows">
                    <div class="admRow"><span style="color:#64748b;">Téléphone</span> <span class="text-white font-bold">{{ $membre->telephone ?: '—' }}</span></div>
                    <div class="admRow"><span style="color:#64748b;">Email</span> <span class="text-white font-bold">{{ $membre->email ?: '—' }}</span></div>
                    <div class="admRow">
                        <span style="color:#64748b;">Compte lié</span>
                        @if($u) <span style="color:#4ade80; font-size:12px;">✅ {{ $u->email }}</span>
                        @else <span style="color:#fb7185; font-size:12px;">❌ Aucun compte</span> @endif
                    </div>
                    <div class="admRow" style="flex-direction:column; align-items:flex-start;">
                        <span style="color:#64748b; margin-bottom:5px;">Adresse</span>
                        <span class="text-white" style="font-size:13px; line-height:1.4;">{{ $membre->adresse ?: 'Non renseignée' }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Zone de Danger --}}
        <div class="admPanel admPanel--full" style="border-color: rgba(239, 68, 68, 0.2); background: rgba(239, 68, 68, 0.02);">
            <div class="admPanel__body" style="display:flex; justify-content:space-between; align-items:center;">
                <div>
                    <h3 style="color:#f87171; font-weight:800; margin:0;">Zone critique</h3>
                    <p style="color:#64748b; font-size:12px; margin:0;">La suppression d'un membre est irréversible.</p>
                </div>
                <form method="POST" action="{{ route('admin.membres.destroy', $membre) }}" onsubmit="return confirm('Supprimer définitivement ce membre ?');">
                    @csrf @method('DELETE')
                    <button class="btn" style="background:#dc2626; color:#fff; border-radius:10px; padding:10px 20px; font-weight:800; cursor:pointer; border:none;">Supprimer ce membre</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection