@extends('layouts.admin')

@section('title', 'Admin • Membres')
@section('header', 'Répertoire des Membres')

@section('content')
<div class="admDash">
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">Membres</h1>
            <p class="admDash__sub">Consultez et gérez la base de données de tous les inscrits.</p>
        </div>
    </div>

    <form method="GET" style="margin-bottom: 16px; display:flex; gap:10px; align-items:center;">
        <input class="input" type="text" name="q" value="{{ $q }}" placeholder="Rechercher par nom, prénom ou matricule..." style="max-width:320px; width:100%;">
        <button class="admQuick__btn" type="submit">Rechercher</button>
        @if($q !== '')
            <a class="admQuick__btn" href="{{ route('admin.membres.index') }}" style="text-decoration:none;">Réinitialiser</a>
        @endif
    </form>

    <div class="admPanel admPanel--full">
        <div class="admPanel__body" style="padding: 0;">
            <div class="table-wrap">
                <table class="table" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: rgba(255,255,255,0.02); text-align: left;">
                            <th style="padding: 18px; color: #64748b; font-size: 11px; text-transform: uppercase;">Identité</th>
                            <th style="padding: 18px; color: #64748b; font-size: 11px; text-transform: uppercase;">Département</th>
                            <th style="padding: 18px; color: #64748b; font-size: 11px; text-transform: uppercase;">Pays</th>
                            <th style="padding: 18px; color: #64748b; font-size: 11px; text-transform: uppercase; text-align: center;">Adhésion</th>
                            <th style="padding: 18px; color: #64748b; font-size: 11px; text-transform: uppercase; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="admRows">
                        @forelse($membres as $m)
                        <tr class="admRow" style="display: table-row; background: transparent; border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <td style="padding: 18px;">
                                <div style="font-weight: 800; color: #fff; font-size: 15px;">{{ $m->prenom }} {{ $m->nom }}</div>
                                <div style="font-size: 11px; color: #4ade80; font-family: monospace;">ID: {{ $m->matricule }}</div>
                            </td>
                            <td style="padding: 18px;">
                                <span style="color: #e2e8f0; font-size: 13px;">{{ $m->departement?->nom ?? '—' }}</span>
                            </td>
                            <td style="padding: 18px;">
                                <div style="display: flex; align-items: center; gap: 6px; color: #94a3b8; font-size: 13px;">
                                    🌍 {{ $m->pays?->nom ?? '—' }}
                                </div>
                            </td>
                            <td style="padding: 18px; text-align: center;">
                                <span style="background: rgba(139, 92, 246, 0.1); color: #a78bfa; padding: 4px 10px; border-radius: 8px; font-weight: 800; font-size: 12px;">
                                    {{ $m->annee_adhesion }}
                                </span>
                            </td>
                            <td style="padding: 18px; text-align: right;">
                                <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                    <a class="admQuick__btn" href="{{ route('admin.membres.show', $m) }}" style="padding: 6px 12px; font-size: 12px; text-decoration: none;">Voir</a>
                                    <a class="admQuick__btn" href="{{ route('admin.membres.edit', $m) }}" style="padding: 6px 12px; font-size: 12px; text-decoration: none; border-color: rgba(59, 130, 246, 0.3); color: #60a5fa;">Modifier</a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" style="padding: 60px; text-align: center; color: #64748b;">{{ $q !== '' ? 'Aucun membre ne correspond à « '.$q.' ».' : 'Aucun membre enregistré.' }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div style="margin-top: 20px;">{{ $membres->links('vendor.pagination.admin') }}</div>
</div>
@endsection