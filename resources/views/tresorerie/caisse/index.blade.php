@extends('layouts.tresorerie')

@section('title', 'Trésorerie • Caisse')
@section('header', 'Caisse de l\'association')

@section('content')
<div class="admDash">
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">Caisse de l'association</h1>
            <p class="admDash__sub">Total des cotisations encaissées moins les dépenses.</p>
        </div>
    </div>

    <div class="admGrid">
        <div class="admPanel" style="grid-column: span 4; background: rgba(34,197,94,0.06); border-color: rgba(34,197,94,0.25);">
            <div class="admPanel__body">
                <div style="color:#64748b; font-size:12px; text-transform:uppercase; font-weight:700;">Solde actuel</div>
                <div style="font-size:36px; font-weight:900; color:#4ade80; margin-top:8px;">{{ number_format($solde, 2, ',', ' ') }} TND</div>
            </div>
        </div>

        <div class="admPanel" style="grid-column: span 4;">
            <div class="admPanel__body">
                <div style="color:#64748b; font-size:12px; text-transform:uppercase; font-weight:700;">Total cotisations encaissées</div>
                <div style="font-size:28px; font-weight:900; color:#60a5fa; margin-top:8px;">{{ number_format($totalCotisations, 2, ',', ' ') }} TND</div>
            </div>
        </div>

        <div class="admPanel" style="grid-column: span 4;">
            <div class="admPanel__body">
                <div style="color:#64748b; font-size:12px; text-transform:uppercase; font-weight:700;">Total dépenses</div>
                <div style="font-size:28px; font-weight:900; color:#f87171; margin-top:8px;">{{ number_format($totalDepenses, 2, ',', ' ') }} TND</div>
            </div>
        </div>

        <div class="admPanel" style="grid-column: span 6;">
            <div class="admPanel__head"><h2 class="admPanel__h text-white">Cotisations encaissées par année</h2></div>
            <div class="admPanel__body" style="padding:0;">
                <div class="table-wrap">
                    <table class="table" style="width:100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: rgba(255,255,255,0.02); text-align: left;">
                                <th style="padding: 12px 15px; color: #64748b; font-size: 11px; text-transform: uppercase;">Année</th>
                                <th style="padding: 12px 15px; color: #64748b; font-size: 11px; text-transform: uppercase;">Paiements</th>
                                <th style="padding: 12px 15px; color: #64748b; font-size: 11px; text-transform: uppercase; text-align:right;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cotisationsParAnnee as $row)
                            <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                <td style="padding: 12px 15px; color:#e2e8f0; font-weight:800;">{{ $row->annee }}</td>
                                <td style="padding: 12px 15px; color:#94a3b8;">{{ $row->nb }}</td>
                                <td style="padding: 12px 15px; text-align:right; color:#4ade80; font-weight:700;">{{ number_format($row->total, 2, ',', ' ') }} TND</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" style="padding: 30px; text-align: center; color: #64748b;">Aucune cotisation enregistrée.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="admPanel" style="grid-column: span 6;">
            <div class="admPanel__head"><h2 class="admPanel__h text-white">Dépenses récentes</h2></div>
            <div class="admPanel__body" style="padding:0;">
                <div class="table-wrap">
                    <table class="table" style="width:100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: rgba(255,255,255,0.02); text-align: left;">
                                <th style="padding: 12px 15px; color: #64748b; font-size: 11px; text-transform: uppercase;">Événement</th>
                                <th style="padding: 12px 15px; color: #64748b; font-size: 11px; text-transform: uppercase;">Date</th>
                                <th style="padding: 12px 15px; color: #64748b; font-size: 11px; text-transform: uppercase; text-align:right;">Montant</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($depensesRecentes as $d)
                            <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                <td style="padding: 12px 15px; color:#e2e8f0; font-weight:700;">{{ $d->nom_evenement }}</td>
                                <td style="padding: 12px 15px; color:#94a3b8;">{{ $d->date_depense->format('d/m/Y') }}</td>
                                <td style="padding: 12px 15px; text-align:right; color:#f87171; font-weight:700;">{{ number_format($d->montant_total, 2, ',', ' ') }} TND</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" style="padding: 30px; text-align: center; color: #64748b;">Aucune dépense enregistrée.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
