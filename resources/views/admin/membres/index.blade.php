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

    @if(($pendingCount ?? 0) > 0)
        <div style="margin-bottom:16px; padding:12px 16px; border-radius:12px; background:rgba(251,191,36,0.10); border:1px solid rgba(251,191,36,0.30); color:#fcd34d; font-weight:600; font-size:13px;">
            {{ $pendingCount }} inscription{{ $pendingCount > 1 ? 's' : '' }} en attente de validation.
        </div>
    @endif

    <div class="admPanel admPanel--full">
        <div class="admPanel__body" style="padding: 0;">
            <div class="table-wrap">
                <table class="table table--compact-rows" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: rgba(255,255,255,0.02); text-align: left;">
                            <th style="color: #64748b; font-size: 11px; text-transform: uppercase;">Identité</th>
                            <th style="color: #64748b; font-size: 11px; text-transform: uppercase;">Département</th>
                            <th style="color: #64748b; font-size: 11px; text-transform: uppercase;">Pays</th>
                            <th style="color: #64748b; font-size: 11px; text-transform: uppercase; text-align: center;">Adhésion</th>
                            <th style="color: #64748b; font-size: 11px; text-transform: uppercase; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="admRows">
                        @forelse($membres as $m)
                        <tr class="admRow" style="background: transparent; border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <td>
                                <div style="font-weight: 800; color: #fff; font-size: 15px;">{{ $m->prenom }} {{ $m->nom }}</div>
                                <div style="font-size: 11px; color: #4ade80; font-family: monospace;">ID: {{ $m->matricule }}</div>
                                @if($m->user && $m->user->approved_at === null)
                                    <span style="display:inline-block; margin-top:4px; font-size:10px; font-weight:800; letter-spacing:.04em; text-transform:uppercase; padding:2px 8px; border-radius:999px; background:rgba(251,191,36,0.12); color:#fcd34d; border:1px solid rgba(251,191,36,0.3);">En attente</span>
                                @endif
                            </td>
                            <td>
                                <span style="color: #e2e8f0; font-size: 13px;">{{ $m->departement?->nom ?? '—' }}</span>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 6px; color: #94a3b8; font-size: 13px;">
                                    🌍 {{ $m->pays?->nom ?? '—' }}
                                </div>
                            </td>
                            <td style="text-align: center;">
                                <span style="background: rgba(139, 92, 246, 0.1); color: #a78bfa; padding: 4px 10px; border-radius: 8px; font-weight: 800; font-size: 12px;">
                                    {{ $m->annee_adhesion }}
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <div style="display: flex; gap: 8px; justify-content: flex-end; flex-wrap: wrap;">
                                    @if($m->user && $m->user->approved_at === null)
                                        <form method="POST" action="{{ route('admin.membres.approve', $m) }}" onsubmit="return confirm('Valider l’inscription de {{ $m->prenom }} {{ $m->nom }} ?');">
                                            @csrf @method('PATCH')
                                            <button class="admQuick__btn" style="padding: 6px 12px; font-size: 12px; border-color: rgba(34,197,94,0.35); color: #4ade80; background: rgba(34,197,94,0.06); cursor:pointer;">Approuver</button>
                                        </form>
                                    @endif
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