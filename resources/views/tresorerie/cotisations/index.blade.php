<x-member-layout>
    <x-slot name="header">Cotisations</x-slot>

    @php $user = auth()->user(); @endphp

    <div class="card" style="margin-bottom:16px;">
        <div class="section__head">
            <div class="section__title">{{ $user->isChefTresorier() ? 'Toutes les cotisations' : 'Mes cotisations enregistrées' }}</div>
            <a class="btn btn--primary" href="{{ route('tresorerie.cotisations.create') }}">+ Enregistrer un paiement</a>
        </div>

        <form method="GET" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap; margin-bottom:14px;">
            <input class="input" type="text" name="q" value="{{ $q }}" placeholder="Rechercher par nom ou matricule..." style="max-width:260px;">
            <input class="input" type="number" name="annee" value="{{ request('annee') }}" placeholder="Année" style="max-width:140px;">
            <button class="btn btn--ghost" type="submit">Filtrer</button>
            @if(request('annee') || $q !== '')
                <a class="btn btn--ghost" href="{{ route('tresorerie.cotisations.index') }}">Réinitialiser</a>
            @endif
        </form>

        <div style="overflow-x:auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Membre</th>
                        <th>Année</th>
                        <th>Catégorie</th>
                        <th style="text-align:right;">Dû</th>
                        <th style="text-align:right;">Payé</th>
                        <th style="text-align:right;">Reste</th>
                        <th>Date</th>
                        @if($user->isChefTresorier())
                            <th>Trésorier</th>
                        @endif
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cotisations as $c)
                    <tr>
                        <td>
                            <div style="font-weight:800;">{{ $c->membre->prenom ?? '?' }} {{ $c->membre->nom ?? '' }}</div>
                            <div style="font-size:11px; color:var(--muted); font-family:monospace;">{{ $c->matricule }}</div>
                        </td>
                        <td>{{ $c->annee }}</td>
                        <td>
                            <span class="tag" style="{{ $c->categorie === 'bureau' ? 'background:#ede9fe; color:#6d28d9;' : '' }}">
                                {{ $c->categorie === 'bureau' ? 'BUREAU' : 'MEMBRE' }}
                            </span>
                        </td>
                        <td style="text-align:right;">{{ number_format($c->montant_du, 2, ',', ' ') }}</td>
                        <td style="text-align:right; color:#16a34a; font-weight:700;">{{ number_format($c->montant_paye, 2, ',', ' ') }}</td>
                        <td style="text-align:right; color:{{ $c->reste > 0 ? '#d97706' : 'var(--muted)' }}; font-weight:700;">{{ number_format($c->reste, 2, ',', ' ') }}</td>
                        <td>{{ $c->date_paiement->format('d/m/Y') }}</td>
                        @if($user->isChefTresorier())
                            <td>{{ $c->tresorier->name ?? '—' }}</td>
                        @endif
                        <td style="text-align:right;">
                            <div style="display:flex; gap:8px; justify-content:flex-end;">
                                <a class="btn btn--ghost" style="padding:6px 12px; font-size:12px;" href="{{ route('tresorerie.cotisations.edit', $c) }}">Modifier</a>
                                <form method="POST" action="{{ route('tresorerie.cotisations.destroy', $c) }}" onsubmit="return confirm('Supprimer cet enregistrement ?')">
                                    @csrf @method('DELETE')
                                    <button class="btn" style="padding:6px 12px; font-size:12px; background:#fef2f2; color:#b91c1c; border:1px solid #fecaca;">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" style="padding:40px; text-align:center; color:var(--muted);">{{ $q !== '' ? 'Aucune cotisation ne correspond à « '.$q.' ».' : 'Aucune cotisation enregistrée.' }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $cotisations->links('vendor.pagination.member') }}
    </div>
</x-member-layout>
