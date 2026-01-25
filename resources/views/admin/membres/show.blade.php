@extends('layouts.admin')

@section('title', 'Admin • Détails membre')
@section('header', 'Détails du membre')

@section('content')
@php
    // Photo depuis le compte user lié au membre
    $u = $membre->user ?? null;

    $memberAvatar = ($u && $u->profile_photo_path)
        ? asset('storage/'.$u->profile_photo_path)
        : asset('images/default-avatar.png');
@endphp

<div class="card">
    <div class="toolbar" style="align-items:flex-start;">
        <div style="display:flex; gap:12px; align-items:center; min-width:0;">
            <img
                src="{{ $memberAvatar }}"
                alt="Photo de profil"
                style="width:54px; height:54px; border-radius:999px; object-fit:cover; border:1px solid rgba(255,255,255,.14); flex:0 0 auto;"
            >

            <div style="min-width:0;">
                <div style="font-weight:800; font-size:18px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                    {{ $membre->prenom }} {{ $membre->nom }}
                </div>
                <div class="help">Matricule : {{ $membre->matricule }}</div>

                @if($u)
                    <div class="help" style="margin-top:4px;">
                        Compte lié : <strong>{{ $u->email }}</strong>
                    </div>
                @else
                    <div class="help" style="margin-top:4px; color:rgba(251,113,133,.95);">
                        Aucun compte utilisateur lié à ce membre.
                    </div>
                @endif
            </div>
        </div>

        <div style="display:flex; gap:10px; flex-wrap:wrap; justify-content:flex-end;">
            <a class="btn" href="{{ route('admin.membres.edit', $membre) }}">Modifier</a>
            <a class="btn btn--ghost" href="{{ route('admin.membres.index') }}">← Retour</a>
        </div>
    </div>

    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:16px;">
        <div class="card" style="background: rgba(255,255,255,0.03); box-shadow:none;">
            <div style="font-weight:800; margin-bottom:10px;">Informations</div>
            <div style="display:grid; gap:10px;">
                <div><span style="color:rgba(229,231,235,.75);">Sexe :</span> <strong>{{ $membre->sexe }}</strong></div>
                <div><span style="color:rgba(229,231,235,.75);">Département :</span> <strong>{{ $membre->departement?->nom ?? '—' }}</strong></div>
                <div><span style="color:rgba(229,231,235,.75);">Pays :</span> <strong>{{ $membre->pays?->nom ?? '—' }}</strong></div>
                <div><span style="color:rgba(229,231,235,.75);">Année d’adhésion :</span> <strong>{{ $membre->annee_adhesion }}</strong></div>
            </div>
        </div>

        <div class="card" style="background: rgba(255,255,255,0.03); box-shadow:none;">
            <div style="font-weight:800; margin-bottom:10px;">Contact</div>
            <div style="display:grid; gap:10px;">
                <div><span style="color:rgba(229,231,235,.75);">Téléphone :</span> <strong>{{ $membre->telephone ?: '—' }}</strong></div>
                <div><span style="color:rgba(229,231,235,.75);">Mail :</span> <strong>{{ $membre->email ?: '—' }}</strong></div>
                <div><span style="color:rgba(229,231,235,.75);">Adresse :</span> <strong>{{ $membre->adresse ?: '—' }}</strong></div>
            </div>
        </div>
    </div>

    <div style="margin-top:16px; display:flex; gap:10px; flex-wrap:wrap;">
        <form method="POST" action="{{ route('admin.membres.destroy', $membre) }}"
              onsubmit="return confirm('Supprimer définitivement ce membre ?');">
            @csrf
            @method('DELETE')
            <button class="btn btn--danger" type="submit">Supprimer le membre</button>
        </form>
    </div>
</div>

<style>
@media (max-width: 900px){
  .card > div[style*="grid-template-columns"]{ grid-template-columns: 1fr !important; }
  .toolbar{ flex-direction:column; align-items:stretch; }
  .toolbar > div:last-child{ justify-content:flex-start !important; }
}
</style>
@endsection
