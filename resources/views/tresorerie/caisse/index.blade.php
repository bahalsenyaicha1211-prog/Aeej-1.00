<x-member-layout>
    <x-slot name="header">Caisse de l'association</x-slot>

    <div class="grid grid-3" style="margin-bottom:16px;">
        <div class="card" style="background:#f0fdf4; border-color:#bbf7d0;">
            <div class="kpi__label">Solde actuel</div>
            <div class="kpi__value" style="color:#16a34a;">{{ number_format($solde, 2, ',', ' ') }} TND</div>
        </div>

        <div class="card">
            <div class="kpi__label">Total cotisations encaissées</div>
            <div class="kpi__value" style="color:#2563eb;">{{ number_format($totalCotisations, 2, ',', ' ') }} TND</div>
        </div>

        <div class="card">
            <div class="kpi__label">Total dépenses</div>
            <div class="kpi__value" style="color:#dc2626;">{{ number_format($totalDepenses, 2, ',', ' ') }} TND</div>
        </div>
    </div>

    <div class="grid grid-2">
        <div class="card">
            <div class="section__head">
                <div class="section__title">Cotisations encaissées par année</div>
            </div>
            <div style="overflow-x:auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Année</th>
                            <th>Paiements</th>
                            <th style="text-align:right;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cotisationsParAnnee as $row)
                        <tr>
                            <td style="font-weight:800;">{{ $row->annee }}</td>
                            <td style="color:var(--muted);">{{ $row->nb }}</td>
                            <td style="text-align:right; color:#16a34a; font-weight:700;">{{ number_format($row->total, 2, ',', ' ') }} TND</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" style="padding:30px; text-align:center; color:var(--muted);">Aucune cotisation enregistrée.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="section__head">
                <div class="section__title">Dépenses récentes</div>
            </div>
            <div style="overflow-x:auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Événement</th>
                            <th>Date</th>
                            <th style="text-align:right;">Montant</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($depensesRecentes as $d)
                        <tr>
                            <td style="font-weight:700;">{{ $d->nom_evenement }}</td>
                            <td style="color:var(--muted);">{{ $d->date_depense->format('d/m/Y') }}</td>
                            <td style="text-align:right; color:#dc2626; font-weight:700;">{{ number_format($d->montant_total, 2, ',', ' ') }} TND</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" style="padding:30px; text-align:center; color:var(--muted);">Aucune dépense enregistrée.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-member-layout>
