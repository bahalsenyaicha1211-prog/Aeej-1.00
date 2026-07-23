@extends('layouts.tresorerie')

@section('title', 'Trésorerie • Dépenses')
@section('header', 'Dépenses')

@section('content')
<div class="admDash">
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">Dépenses enregistrées</h1>
            <p class="admDash__sub">Sommes décaissées pour les activités de l'association.</p>
        </div>
        <div style="display:flex; gap:10px;">
            <a class="admQuick__btn" href="{{ route('tresorerie.depenses.rapport-pdf') }}" style="text-decoration: none;">📄 Rapport PDF</a>
            <a class="btn" style="background: #22c55e; color: #fff; border-radius: 12px; padding: 10px 20px; font-weight: 800; text-decoration: none;" href="{{ route('tresorerie.depenses.create') }}">
                + Nouvelle dépense
            </a>
        </div>
    </div>

    <form method="GET" style="margin-bottom: 16px; display:flex; gap:10px; align-items:center;">
        <input class="input" type="text" name="q" value="{{ $q }}" placeholder="Rechercher un événement..." style="max-width:320px; width:100%;">
        <button class="admQuick__btn" type="submit">Rechercher</button>
        @if($q !== '')
            <a class="admQuick__btn" href="{{ route('tresorerie.depenses.index') }}" style="text-decoration:none;">Réinitialiser</a>
        @endif
    </form>

    <div class="admPanel admPanel--full">
        <div class="admPanel__body" style="padding: 0;">
            <div class="table-wrap">
                <table class="table" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: rgba(255,255,255,0.02); text-align: left;">
                            <th style="padding: 15px; color: #64748b; font-size: 11px; text-transform: uppercase;">Événement</th>
                            <th style="padding: 15px; color: #64748b; font-size: 11px; text-transform: uppercase;">Date</th>
                            <th style="padding: 15px; color: #64748b; font-size: 11px; text-transform: uppercase;">Enregistré par</th>
                            <th style="padding: 15px; color: #64748b; font-size: 11px; text-transform: uppercase; text-align:right;">Montant total</th>
                            <th style="padding: 15px; color: #64748b; font-size: 11px; text-transform: uppercase; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="admRows">
                        @forelse($depenses as $d)
                        <tr class="admRow" style="display: table-row; background: transparent; border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <td style="padding: 15px; font-weight: 800; color: #fff;">{{ $d->nom_evenement }}</td>
                            <td style="padding: 15px; color:#94a3b8; font-size:13px;">{{ $d->date_depense->format('d/m/Y') }}</td>
                            <td style="padding: 15px; color:#64748b; font-size:12px;">{{ $d->commissaire->name ?? '—' }}</td>
                            <td style="padding: 15px; text-align:right; color:#f87171; font-weight:800;">{{ number_format($d->montant_total, 2, ',', ' ') }} TND</td>
                            <td style="padding: 15px; text-align: right;">
                                <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                    <a class="admQuick__btn" href="{{ route('tresorerie.depenses.edit', $d) }}" style="padding: 6px 12px; font-size: 12px; text-decoration: none;">Modifier</a>
                                    <form method="POST" action="{{ route('tresorerie.depenses.destroy', $d) }}" onsubmit="return confirm('Supprimer cette dépense ?')">
                                        @csrf @method('DELETE')
                                        <button class="admQuick__btn" style="border-color: rgba(239,68,68,0.3); color: #f87171; background: rgba(239,68,68,0.05); padding: 6px 12px; font-size: 12px;">Supprimer</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" style="padding: 40px; text-align: center; color: #64748b;">{{ $q !== '' ? 'Aucune dépense ne correspond à « '.$q.' ».' : 'Aucune dépense enregistrée.' }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div style="margin-top: 15px;">{{ $depenses->links('vendor.pagination.admin') }}</div>
</div>
@endsection
