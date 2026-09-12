<x-member-layout>
    <x-slot name="header">Cotisations</x-slot>

    @php $user = auth()->user(); @endphp

    @if(session('success'))
        <div class="alert alert--success" style="margin-bottom:16px;">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert--danger" style="margin-bottom:16px;">
            @foreach($errors->all() as $error)
                <div>⚠️ {{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="cotTabs" role="tablist" style="margin-bottom:16px;">
        <a class="cotTabs__btn {{ $tab === 'annuelle' ? 'is-active' : '' }}" href="{{ route('tresorerie.cotisations.index', ['tab' => 'annuelle']) }}">Cotisation annuelle</a>
        <a class="cotTabs__btn {{ $tab === 'volontaire' ? 'is-active' : '' }}" href="{{ route('tresorerie.cotisations.index', ['tab' => 'volontaire']) }}">Cotisations volontaires</a>
    </div>

    @if($tab === 'annuelle')
        <div class="card" style="margin-bottom:16px;">
            <div class="section__head">
                <div class="section__title">{{ $user->isChefTresorier() ? 'Toutes les cotisations annuelles' : 'Mes cotisations enregistrées' }}</div>
                <a class="btn btn--primary" href="{{ route('tresorerie.cotisations.create') }}">+ Enregistrer un paiement</a>
            </div>

            <form method="GET" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap; margin-bottom:14px;">
                <input type="hidden" name="tab" value="annuelle">
                <input class="input" type="text" name="q" value="{{ $q }}" placeholder="Rechercher par nom ou matricule..." style="max-width:260px;">
                <input class="input" type="number" name="annee" value="{{ request('annee') }}" placeholder="Année" style="max-width:140px;">
                <button class="btn btn--ghost" type="submit">Filtrer</button>
                @if(request('annee') || $q !== '')
                    <a class="btn btn--ghost" href="{{ route('tresorerie.cotisations.index', ['tab' => 'annuelle']) }}">Réinitialiser</a>
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
    @else
        <div class="card" style="margin-bottom:16px;">
            <div class="section__head">
                <div class="section__title">{{ $user->isChefTresorier() ? 'Toutes les cotisations volontaires' : 'Mes paiements volontaires enregistrés' }}</div>
                <a class="btn btn--primary" href="{{ route('tresorerie.cotisations.create') }}">+ Enregistrer un paiement</a>
            </div>
            <p style="color:var(--muted); font-size:13px; margin-top:-6px; margin-bottom:16px;">
                Activités optionnelles (camping, sorties…) payées en plus de la cotisation annuelle. Configurable depuis « Montants cotisation ».
            </p>

            <form method="GET" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap; margin-bottom:14px;">
                <input type="hidden" name="tab" value="volontaire">
                <input class="input" type="text" name="q" value="{{ $q }}" placeholder="Rechercher par nom, matricule ou motif..." style="max-width:280px;">
                <input class="input" type="number" name="annee" value="{{ request('annee') }}" placeholder="Année" style="max-width:140px;">
                <button class="btn btn--ghost" type="submit">Filtrer</button>
                @if(request('annee') || $q !== '')
                    <a class="btn btn--ghost" href="{{ route('tresorerie.cotisations.index', ['tab' => 'volontaire']) }}">Réinitialiser</a>
                @endif
            </form>

            <div style="overflow-x:auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Membre</th>
                            <th>Cotisation</th>
                            <th style="text-align:right;">Payé</th>
                            <th>Date</th>
                            @if($user->isChefTresorier())
                                <th>Trésorier</th>
                            @endif
                            <th style="text-align:right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cotisationsVolontaires as $c)
                        <tr>
                            <td>
                                <div style="font-weight:800;">{{ $c->membre->prenom ?? '?' }} {{ $c->membre->nom ?? '' }}</div>
                                <div style="font-size:11px; color:var(--muted); font-family:monospace;">{{ $c->matricule }}</div>
                            </td>
                            <td>
                                <span class="tag">{{ $c->type->nom ?? '—' }}</span>
                                <div style="font-size:11px; color:var(--muted); margin-top:2px;">{{ $c->type->annee ?? '' }}</div>
                            </td>
                            <td style="text-align:right; color:#16a34a; font-weight:700;">{{ number_format($c->montant_paye, 2, ',', ' ') }}</td>
                            <td>{{ $c->date_paiement->format('d/m/Y') }}</td>
                            @if($user->isChefTresorier())
                                <td>{{ $c->tresorier->name ?? '—' }}</td>
                            @endif
                            <td style="text-align:right;">
                                <div style="display:flex; gap:8px; justify-content:flex-end;">
                                    <a class="btn btn--ghost" style="padding:6px 12px; font-size:12px;" href="{{ route('tresorerie.cotisations-volontaires.edit', $c) }}">Modifier</a>
                                    <form method="POST" action="{{ route('tresorerie.cotisations-volontaires.destroy', $c) }}" onsubmit="return confirm('Supprimer cet enregistrement ?')">
                                        @csrf @method('DELETE')
                                        <button class="btn" style="padding:6px 12px; font-size:12px; background:#fef2f2; color:#b91c1c; border:1px solid #fecaca;">Supprimer</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" style="padding:40px; text-align:center; color:var(--muted);">{{ $q !== '' ? 'Aucun paiement ne correspond à « '.$q.' ».' : 'Aucun paiement volontaire enregistré.' }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $cotisationsVolontaires->links('vendor.pagination.member') }}
        </div>
    @endif

<style>
.cotTabs{ display:flex; gap:8px; border-bottom:1px solid var(--border); }
.cotTabs__btn{
    display:inline-block;
    padding:10px 16px; border:0; background:none; cursor:pointer;
    font-size:14px; font-weight:800; color:var(--muted); text-decoration:none;
    border-bottom:2px solid transparent; margin-bottom:-1px;
    transition: color .15s ease, border-color .15s ease;
}
.cotTabs__btn:hover{ color:var(--text); }
.cotTabs__btn.is-active{ color:var(--brand2); border-color:var(--brand2); }
</style>
</x-member-layout>
