<x-member-layout>
    <x-slot name="header">Montants de cotisation</x-slot>

    @if(session('success'))
        <div class="alert alert--success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert--danger">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert--danger">
            @foreach($errors->all() as $error)
                <div>⚠️ {{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="grid grid-2">
        <div class="card">
            <div class="section__head">
                <div class="section__title">Définir / mettre à jour une année</div>
            </div>
            <p style="color:var(--muted); font-size:13px; margin-top:-6px; margin-bottom:16px;">
                Un membre du bureau paie un montant différent d'un membre simple.
            </p>

            <form method="POST" action="{{ route('tresorerie.config.update') }}">
                @csrf
                <div class="field">
                    <label>Année *</label>
                    <input class="input" type="number" name="annee" value="{{ old('annee', now()->year) }}" min="2010" max="{{ now()->year + 1 }}" required>
                </div>
                <div class="field" style="margin-top:16px;">
                    <label>Montant membre simple (TND) *</label>
                    <input class="input" type="number" step="0.01" min="0" name="montant_membre" value="{{ old('montant_membre') }}" required>
                </div>
                <div class="field" style="margin-top:16px;">
                    <label>Montant membre du bureau (TND) *</label>
                    <input class="input" type="number" step="0.01" min="0" name="montant_bureau" value="{{ old('montant_bureau') }}" required>
                </div>

                <div style="margin-top:20px;">
                    <button class="btn btn--primary" type="submit">Enregistrer</button>
                </div>
            </form>
        </div>

        <div class="card">
            <div class="section__head">
                <div class="section__title">Historique</div>
            </div>
            <div style="overflow-x:auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Année</th>
                            <th style="text-align:right;">Membre simple</th>
                            <th style="text-align:right;">Membre du bureau</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($configs as $c)
                        <tr>
                            <td style="font-weight:800;">{{ $c->annee }}</td>
                            <td style="text-align:right;">{{ number_format($c->montant_membre, 2, ',', ' ') }} TND</td>
                            <td style="text-align:right; color:#6d28d9;">{{ number_format($c->montant_bureau, 2, ',', ' ') }} TND</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" style="padding:40px; text-align:center; color:var(--muted);">Aucun montant configuré pour le moment.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="grid grid-2" style="margin-top:20px;">
        <div class="card">
            <div class="section__head">
                <div class="section__title">Ajouter une date de collecte</div>
            </div>
            <p style="color:var(--muted); font-size:13px; margin-top:-6px; margin-bottom:16px;">
                Les paiements de cotisation ne pourront être datés que sur l'une de ces dates.
            </p>

            <form method="POST" action="{{ route('tresorerie.config.dates.store') }}">
                @csrf
                <div class="field">
                    <label>Année *</label>
                    <input class="input" type="number" name="annee" value="{{ old('annee', now()->year) }}" min="2010" max="{{ now()->year + 1 }}" required>
                </div>
                <div class="field" style="margin-top:16px;">
                    <label>Date de collecte *</label>
                    <input class="input" type="date" name="date_collecte" value="{{ old('date_collecte') }}" required>
                </div>

                <div style="margin-top:20px;">
                    <button class="btn btn--primary" type="submit">Ajouter la date</button>
                </div>
            </form>
        </div>

        <div class="card">
            <div class="section__head">
                <div class="section__title">Dates configurées</div>
            </div>

            @forelse($dates as $annee => $datesAnnee)
                <div style="margin-bottom:16px;">
                    <div style="font-weight:800; margin-bottom:8px;">{{ $annee }}</div>
                    <div style="display:flex; flex-wrap:wrap; gap:8px;">
                        @foreach($datesAnnee as $d)
                            <form method="POST" action="{{ route('tresorerie.config.dates.destroy', $d) }}"
                                  onsubmit="return confirm('Retirer la date du {{ $d->date_collecte->format('d/m/Y') }} ?');"
                                  style="display:inline-flex; align-items:center; gap:6px; padding:6px 6px 6px 12px; border-radius:999px; border:1px solid var(--border); background:#f9fafb; font-size:13px; font-weight:700;">
                                @csrf @method('DELETE')
                                <span>{{ $d->date_collecte->format('d/m/Y') }}</span>
                                <button type="submit" aria-label="Retirer cette date"
                                        style="width:20px; height:20px; border-radius:999px; border:0; background:#fee2e2; color:#b91c1c; font-weight:900; line-height:1; cursor:pointer;">×</button>
                            </form>
                        @endforeach
                    </div>
                </div>
            @empty
                <p style="color:var(--muted); font-size:13px;">Aucune date de collecte configurée pour le moment.</p>
            @endforelse
        </div>
    </div>

    <div class="grid grid-2" style="margin-top:20px;">
        <div class="card">
            <div class="section__head">
                <div class="section__title">Ajouter une cotisation volontaire</div>
            </div>
            <p style="color:var(--muted); font-size:13px; margin-top:-6px; margin-bottom:16px;">
                Pour une activité optionnelle (camping, sortie…) que seuls certains membres paient — en plus de la cotisation annuelle obligatoire. Le montant est fixe ; la date de paiement reste libre.
            </p>

            <form method="POST" action="{{ route('tresorerie.config.types.store') }}">
                @csrf
                <div class="field">
                    <label>Année *</label>
                    <input class="input" type="number" name="annee" value="{{ old('annee', now()->year) }}" min="2010" max="{{ now()->year + 1 }}" required>
                </div>
                <div class="field" style="margin-top:16px;">
                    <label>Motif *</label>
                    <input class="input" type="text" name="nom" value="{{ old('nom') }}" placeholder="Ex. Camping 2026" required maxlength="100">
                </div>
                <div class="field" style="margin-top:16px;">
                    <label>Montant (TND) *</label>
                    <input class="input" type="number" step="0.01" min="0" name="montant" value="{{ old('montant') }}" required>
                </div>

                <div style="margin-top:20px;">
                    <button class="btn btn--primary" type="submit">Ajouter</button>
                </div>
            </form>
        </div>

        <div class="card">
            <div class="section__head">
                <div class="section__title">Cotisations volontaires configurées</div>
            </div>

            @forelse($types as $annee => $typesAnnee)
                <div style="margin-bottom:16px;">
                    <div style="font-weight:800; margin-bottom:8px;">{{ $annee }}</div>
                    <div class="list">
                        @foreach($typesAnnee as $t)
                            <div class="item" style="display:flex; justify-content:space-between; align-items:center; gap:10px; {{ $t->actif ? '' : 'opacity:.55;' }}">
                                <div>
                                    <div style="font-weight:800;">{{ $t->nom }}</div>
                                    <div style="color:var(--muted); font-size:12px;">
                                        {{ number_format($t->montant, 2, ',', ' ') }} TND
                                        · {{ $t->paiements_count }} paiement{{ $t->paiements_count > 1 ? 's' : '' }}
                                        @unless($t->actif) · <span style="color:#b91c1c; font-weight:700;">désactivée</span> @endunless
                                    </div>
                                </div>
                                <div style="display:flex; gap:6px;">
                                    <form method="POST" action="{{ route('tresorerie.config.types.toggle', $t) }}">
                                        @csrf @method('PATCH')
                                        <button class="btn btn--ghost" style="padding:6px 10px; font-size:12px;" type="submit">
                                            {{ $t->actif ? 'Désactiver' : 'Réactiver' }}
                                        </button>
                                    </form>
                                    @if($t->paiements_count === 0)
                                        <form method="POST" action="{{ route('tresorerie.config.types.destroy', $t) }}" onsubmit="return confirm('Supprimer « {{ $t->nom }} » ?');">
                                            @csrf @method('DELETE')
                                            <button class="btn" style="padding:6px 10px; font-size:12px; background:#fef2f2; color:#b91c1c; border:1px solid #fecaca;" type="submit">Suppr.</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <p style="color:var(--muted); font-size:13px;">Aucune cotisation volontaire configurée pour le moment.</p>
            @endforelse
        </div>
    </div>
</x-member-layout>
