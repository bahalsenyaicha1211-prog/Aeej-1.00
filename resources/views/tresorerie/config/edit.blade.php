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
                Un membre du bureau paie un montant différent d'un membre simple. Année académique en cours : <strong>{{ \App\Support\AcademicYear::label($anneeActive) }}</strong> — c'est la seule utilisable pour un nouveau paiement.
            </p>

            <form method="POST" action="{{ route('tresorerie.config.update') }}">
                @csrf
                <div class="field">
                    <label>Année (de début) *</label>
                    <input class="input" type="number" name="annee" value="{{ old('annee', $anneeActive) }}" min="2010" max="{{ now()->year + 1 }}" required>
                    <span class="field__hint">Ex. saisir 2026 pour l'année académique 2026-2027.</span>
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
                            <td style="font-weight:800;">
                                {{ \App\Support\AcademicYear::label($c->annee) }}
                                @if($c->annee === $anneeActive)
                                    <span class="tag" style="margin-left:6px; background:#dcfce7; color:#15803d;">en cours</span>
                                @endif
                            </td>
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
                <div class="section__title">Ajouter une cotisation volontaire</div>
            </div>
            <p style="color:var(--muted); font-size:13px; margin-top:-6px; margin-bottom:16px;">
                Pour une activité optionnelle (camping, sortie…) que seuls certains membres paient — en plus de la cotisation annuelle obligatoire. Le montant est fixe ; la date de paiement reste libre.
            </p>

            <form method="POST" action="{{ route('tresorerie.config.types.store') }}">
                @csrf
                <div class="field">
                    <label>Année *</label>
                    <input class="input" type="number" name="annee" value="{{ old('annee', $anneeActive) }}" min="2010" max="{{ now()->year + 1 }}" required>
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
