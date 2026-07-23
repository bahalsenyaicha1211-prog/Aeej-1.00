<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Rapport financier AEEJ</title>
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1e293b; }
    h1 { font-size: 18px; margin-bottom: 2px; }
    .sub { color: #64748b; font-size: 11px; margin-bottom: 20px; }

    .resume { width: 100%; margin-bottom: 24px; border-collapse: collapse; }
    .resume td { padding: 10px; border: 1px solid #cbd5e1; text-align: center; }
    .resume .label { font-size: 9px; text-transform: uppercase; color: #64748b; }
    .resume .value { font-size: 16px; font-weight: bold; margin-top: 4px; }

    h2 { font-size: 13px; margin-top: 24px; margin-bottom: 8px; border-bottom: 2px solid #1e293b; padding-bottom: 4px; }

    table.data { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
    table.data th { background: #f1f5f9; text-align: left; padding: 6px 8px; font-size: 9px; text-transform: uppercase; color: #475569; border: 1px solid #cbd5e1; }
    table.data td { padding: 6px 8px; border: 1px solid #e2e8f0; font-size: 10px; }
    table.data td.right, table.data th.right { text-align: right; }

    .evenement-total { font-weight: bold; }
    .footer { margin-top: 30px; font-size: 9px; color: #94a3b8; }
</style>
</head>
<body>

    <h1>Rapport financier — AEEJ</h1>
    <div class="sub">Généré le {{ $genereLe->format('d/m/Y à H:i') }}</div>

    <table class="resume">
        <tr>
            <td>
                <div class="label">Total cotisations encaissées</div>
                <div class="value">{{ number_format($totalCotisations, 2, ',', ' ') }} TND</div>
            </td>
            <td>
                <div class="label">Total dépenses</div>
                <div class="value">{{ number_format($totalDepenses, 2, ',', ' ') }} TND</div>
            </td>
            <td>
                <div class="label">Solde de la caisse</div>
                <div class="value">{{ number_format($solde, 2, ',', ' ') }} TND</div>
            </td>
        </tr>
    </table>

    <h2>Cotisations encaissées par année</h2>
    <table class="data">
        <thead>
            <tr>
                <th>Année</th>
                <th class="right">Total encaissé</th>
            </tr>
        </thead>
        <tbody>
            @forelse($cotisationsParAnnee as $row)
            <tr>
                <td>{{ $row->annee }}</td>
                <td class="right">{{ number_format($row->total, 2, ',', ' ') }} TND</td>
            </tr>
            @empty
            <tr><td colspan="2">Aucune cotisation enregistrée.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>Détail des dépenses</h2>
    @forelse($depenses as $d)
        <table class="data">
            <thead>
                <tr>
                    <th colspan="2">{{ $d->nom_evenement }} — {{ $d->date_depense->format('d/m/Y') }}</th>
                </tr>
                <tr>
                    <th>Désignation</th>
                    <th class="right">Montant</th>
                </tr>
            </thead>
            <tbody>
                @foreach($d->lignes as $l)
                <tr>
                    <td>{{ $l->designation }}</td>
                    <td class="right">{{ number_format($l->montant, 2, ',', ' ') }} TND</td>
                </tr>
                @endforeach
                <tr class="evenement-total">
                    <td>Total</td>
                    <td class="right">{{ number_format($d->montant_total, 2, ',', ' ') }} TND</td>
                </tr>
            </tbody>
        </table>
    @empty
        <table class="data"><tbody><tr><td>Aucune dépense enregistrée.</td></tr></tbody></table>
    @endforelse

    <div class="footer">Rapport généré automatiquement par la plateforme AEEJ — Association des Étudiants Étrangers à Jendouba.</div>

</body>
</html>
