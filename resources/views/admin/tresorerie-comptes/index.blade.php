@extends('layouts.admin')

@section('title', 'Admin • Trésorerie')
@section('header', 'Comptes trésorerie')

@section('content')
<div class="admDash">
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">Comptes trésorerie</h1>
            <p class="admDash__sub">Membres ayant un rôle trésorier, chef trésorier ou commissaire aux comptes.</p>
        </div>
        <a class="btn" style="background: #22c55e; color: #fff; border-radius: 12px; padding: 10px 20px; font-weight: 800; text-decoration: none;" href="{{ route('admin.tresorerie-comptes.create') }}">
            + Attribuer un rôle
        </a>
    </div>

    <form method="GET" style="margin-bottom: 16px; display:flex; gap:10px; align-items:center;">
        <input class="input" type="text" name="q" value="{{ $q }}" placeholder="Rechercher par nom ou email..." style="max-width:320px; width:100%;">
        <button class="admQuick__btn" type="submit">Rechercher</button>
        @if($q !== '')
            <a class="admQuick__btn" href="{{ route('admin.tresorerie-comptes.index') }}" style="text-decoration:none;">Réinitialiser</a>
        @endif
    </form>

    <div class="admPanel admPanel--full">
        <div class="admPanel__body" style="padding: 0;">
            <div class="table-wrap">
                <table class="table table--compact-rows" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: rgba(255,255,255,0.02); text-align: left;">
                            <th style="color: #64748b; font-size: 11px; text-transform: uppercase;">Compte</th>
                            <th style="color: #64748b; font-size: 11px; text-transform: uppercase;">Rôle</th>
                            <th style="color: #64748b; font-size: 11px; text-transform: uppercase; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="admRows">
                        @forelse($comptes as $c)
                        <tr class="admRow" style="background: transparent; border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <td>
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <img src="{{ $c->avatar_url }}" alt=""
                                         style="width: 45px; height: 45px; border-radius: 12px; object-fit: cover; border: 2px solid rgba(255,255,255,0.1); flex-shrink: 0;">
                                    <div style="min-width: 0;">
                                        <div style="font-weight: 800; color: #fff;">{{ $c->name }}</div>
                                        <div style="font-size: 12px; color: #94a3b8; font-family: monospace; word-break: break-all;">
                                            {{ $c->email }}@if($c->matricule) · {{ $c->matricule }}@endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($c->is_chef_tresorier)
                                    <span style="padding: 4px 10px; border-radius: 8px; font-size: 10px; font-weight: 900; background: rgba(139, 92, 246, 0.1); color: #a78bfa; border: 1px solid rgba(139, 92, 246, 0.2);">CHEF TRÉSORIER</span>
                                @elseif($c->is_tresorier)
                                    <span style="padding: 4px 10px; border-radius: 8px; font-size: 10px; font-weight: 900; background: rgba(96,165,250, 0.1); color: #60a5fa; border: 1px solid rgba(96,165,250, 0.2);">TRÉSORIER</span>
                                @elseif($c->is_commissaire_comptes)
                                    <span style="padding: 4px 10px; border-radius: 8px; font-size: 10px; font-weight: 900; background: rgba(34,197,94, 0.1); color: #4ade80; border: 1px solid rgba(34,197,94, 0.2);">COMMISSAIRE AUX COMPTES</span>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                    <a class="admQuick__btn" href="{{ route('admin.tresorerie-comptes.edit', $c) }}" style="padding: 6px 12px; font-size: 12px; text-decoration: none;">Modifier</a>
                                    <form method="POST" action="{{ route('admin.tresorerie-comptes.destroy', $c) }}" onsubmit="return confirm('Retirer ce rôle trésorerie ? Le compte membre de la personne restera actif.');">
                                        @csrf @method('DELETE')
                                        <button class="admQuick__btn" style="border-color: rgba(239,68,68,0.3); color: #f87171; background: rgba(239,68,68,0.05); padding: 6px 12px; font-size: 12px;">Retirer le rôle</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" style="padding: 50px; text-align: center; color: #64748b;">{{ $q !== '' ? 'Aucun compte ne correspond à « '.$q.' ».' : 'Aucun compte trésorerie pour le moment.' }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div style="margin-top: 20px;">{{ $comptes->links('vendor.pagination.admin') }}</div>
</div>
@endsection
