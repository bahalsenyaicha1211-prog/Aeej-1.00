<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $titre }} — AEEJ</title>
    <style>
        @page { size: A4 landscape; margin: 12mm; }
        * { box-sizing: border-box; }
        body { margin: 0; padding: 24px; font-family: Arial, Helvetica, sans-serif; font-size: 11px; color: #111; background: #fff; }
        .barre { display: flex; gap: 10px; margin-bottom: 18px; }
        .barre button { padding: 9px 18px; border: 0; border-radius: 8px; font-weight: 700; font-size: 13px; cursor: pointer; background: #16a34a; color: #fff; }
        .barre button.secondaire { background: #e5e7eb; color: #111; }
        .entete { display: flex; justify-content: space-between; align-items: flex-end; border-bottom: 2px solid #111; padding-bottom: 8px; margin-bottom: 12px; }
        .entete h1 { margin: 0; font-size: 18px; }
        .entete p { margin: 2px 0 0; color: #555; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #bbb; padding: 5px 6px; text-align: left; vertical-align: top; }
        th { background: #f1f5f9; font-size: 10px; text-transform: uppercase; letter-spacing: .03em; }
        tr:nth-child(even) td { background: #fafafa; }
        thead { display: table-header-group; } /* en-tête répété sur chaque page */
        tr { page-break-inside: avoid; }
        .num { width: 32px; text-align: right; color: #555; }
        @media print {
            body { padding: 0; }
            .barre { display: none; }
            th, tr:nth-child(even) td { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>
    <div class="barre">
        <button type="button" onclick="window.print()">Imprimer / Enregistrer en PDF</button>
        <button type="button" class="secondaire" onclick="window.close()">Fermer</button>
    </div>

    <div class="entete">
        <div>
            <h1>{{ $titre }}</h1>
            <p>Association des Étudiants Étrangers à Jendouba (AEEJ)</p>
        </div>
        <p>{{ $resume }} — édité le {{ now()->format('d/m/Y à H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="num">N°</th>
                @foreach($colonnes as $colonne)
                    <th>{{ $colonne }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($lignes as $i => $ligne)
                <tr>
                    <td class="num">{{ $i + 1 }}</td>
                    @foreach($ligne as $valeur)
                        <td>{{ $valeur }}</td>
                    @endforeach
                </tr>
            @empty
                <tr><td colspan="{{ count($colonnes) + 1 }}">Aucune donnée.</td></tr>
            @endforelse
        </tbody>
    </table>

    <script>window.addEventListener('load', function () { window.print(); });</script>
</body>
</html>
