@extends('layouts.admin')

@section('title', 'Admin • Détails membre')
@section('header', 'Détails du membre')

@section('content')
<div class="card">
    <div class="toolbar">
        <div>
            <div style="font-weight:800; font-size:18px;">
                {{ $membre->prenom }} {{ $membre->nom }}
            </div>
            <div class="help">Matricule : {{ $membre->matricule }}</div>
        </div>

        <div style="display:flex; gap:10px; flex-wrap:wrap;">
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
}
</style>
@endsection
