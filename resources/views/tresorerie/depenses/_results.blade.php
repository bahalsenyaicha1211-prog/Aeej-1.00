<div style="overflow-x:auto;">
    <table class="table">
        <thead>
            <tr>
                <th>Événement</th>
                <th>Date</th>
                <th>Enregistré par</th>
                <th style="text-align:right;">Montant total</th>
                <th style="text-align:right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($depenses as $d)
            <tr>
                <td style="font-weight:800;">{{ $d->nom_evenement }}</td>
                <td style="color:var(--muted);">{{ $d->date_depense->format('d/m/Y') }}</td>
                <td style="color:var(--muted); font-size:13px;">{{ $d->commissaire->name ?? '—' }}</td>
                <td style="text-align:right; color:#dc2626; font-weight:800;">{{ number_format($d->montant_total, 2, ',', ' ') }} TND</td>
                <td style="text-align:right;">
                    <div style="display:flex; gap:8px; justify-content:flex-end;">
                        <a class="btn btn--ghost" style="padding:6px 12px; font-size:12px;" href="{{ route('tresorerie.depenses.edit', $d) }}">Modifier</a>
                        <form method="POST" action="{{ route('tresorerie.depenses.destroy', $d) }}" onsubmit="return confirm('Supprimer cette dépense ?')">
                            @csrf @method('DELETE')
                            <button class="btn" style="padding:6px 12px; font-size:12px; background:#fef2f2; color:#b91c1c; border:1px solid #fecaca;">Supprimer</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" style="padding:40px; text-align:center; color:var(--muted);">{{ $q !== '' ? 'Aucune dépense ne correspond à « '.$q.' ».' : 'Aucune dépense enregistrée.' }}</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $depenses->links('vendor.pagination.member') }}
