{{-- resources/views/dashboard.blade.php --}}
<x-member-layout :unreadAnnoncesCount="$unreadAnnoncesCount ?? 0">
    <x-slot name="header">Tableau de bord</x-slot>

    @if($caisseSolde !== null || $mesCotisationsCount !== null)
    <div class="grid grid-3" style="margin-bottom:16px;">
        @if($caisseSolde !== null)
        <div class="card" style="background:#f0fdf4; border-color:#bbf7d0;">
            <div class="kpi__label">Solde de la caisse</div>
            <div style="display:flex; align-items:center; gap:10px;">
                <div class="kpi__value" id="caisse-valeur" data-valeur="{{ number_format($caisseSolde, 2, ',', ' ') }} TND" style="color:#16a34a;">••••••</div>
                <button type="button" id="caisse-toggle" aria-label="Afficher le solde" style="background:none; border:none; cursor:pointer; padding:4px; opacity:0.65; color:#16a34a;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
            </div>
            <a class="section__link" href="{{ route('tresorerie.caisse.index') }}">Voir le détail →</a>
        </div>
        <script>
        (() => {
            const valeur = document.getElementById('caisse-valeur');
            const btn = document.getElementById('caisse-toggle');
            const masque = '••••••';
            let visible = false;

            btn.addEventListener('click', () => {
                visible = !visible;
                valeur.textContent = visible ? valeur.dataset.valeur : masque;
                btn.setAttribute('aria-label', visible ? 'Masquer le solde' : 'Afficher le solde');
            });
        })();
        </script>
        @endif

        @if($mesCotisationsCount !== null)
        <div class="card">
            <div class="kpi__label">Mes cotisations enregistrées ({{ now()->year }})</div>
            <div class="kpi__value">{{ $mesCotisationsCount }}</div>
            <a class="section__link" href="{{ route('tresorerie.cotisations.index') }}">Voir la liste →</a>
        </div>
        @endif

        @if($user->isTresorier())
        <div class="card">
            <div class="kpi__label">Cotisations</div>
            <a class="btn btn--primary" href="{{ route('tresorerie.cotisations.create') }}" style="display:inline-block; margin-top:10px;">+ Enregistrer un paiement</a>
        </div>
        @endif

        @if($user->isCommissaireComptes())
        <div class="card">
            <div class="kpi__label">Dépenses</div>
            <a class="btn btn--primary" href="{{ route('tresorerie.depenses.create') }}" style="display:inline-block; margin-top:10px;">+ Enregistrer une dépense</a>
        </div>
        @endif
    </div>
    @endif

    {{-- KPIs --}}
    <div class="grid grid-4" style="margin-bottom:16px;">
        <div class="card">
            <div class="kpi__label">Total membres</div>
            <div class="kpi__value">{{ $totalMembres }}</div>
        </div>

        <div class="card">
            <div class="kpi__label">Hommes</div>
            <div class="kpi__value">{{ $parSexe['M'] ?? 0 }}</div>
        </div>

        <div class="card">
            <div class="kpi__label">Femmes</div>
            <div class="kpi__value">{{ $parSexe['F'] ?? 0 }}</div>
        </div>

        <div class="card">
            <div class="kpi__label">Mon matricule</div>
            <div class="kpi__value small">{{ $membre?->matricule ?? '—' }}</div>
        </div>
    </div>

    <div class="grid grid-2">
        {{-- Profil rapide --}}
        <div class="card">
            <div class="section__head">
                <div class="section__title">Mes informations</div>
                <a class="section__link" href="{{ route('profile.edit') }}">Modifier</a>
            </div>

            <table class="table">
                <tbody>
                <tr>
                    <th style="width:40%;">Nom</th>
                    <td>{{ $membre?->prenom }} {{ $membre?->nom }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $user->email }}</td>
                </tr>
                <tr>
                    <th>Département</th>
                    <td>{{ $membre?->departement?->nom ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Pays</th>
                    <td>{{ $membre?->pays?->nom ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Téléphone</th>
                    <td>{{ $membre?->telephone ?? '—' }}</td>
                </tr>
                </tbody>
            </table>
        </div>

        {{-- Dernières annonces --}}
        <div class="card">
            <div class="section__head">
                <div class="section__title">Dernières annonces</div>
                <a class="section__link" href="{{ route('membre.annonces.index') }}">Voir tout</a>
            </div>

            <div class="list">
                @forelse($annonces as $a)
                    <a class="item" href="{{ route('membre.annonces.show', $a) }}">
                        <div class="meta">
                            <div style="display:flex; align-items:center; gap:8px;">
                                @if($a->is_pinned)
                                    <span class="tag">Épinglée</span>
                                @endif
                            </div>
                            <div>
                                {{ optional($a->published_at ?? $a->created_at)->format('d/m/Y') }}
                            </div>
                        </div>

                        <div style="font-weight:900;">
                            {{ \Illuminate\Support\Str::limit($a->contenu, 90) }}
                        </div>

                        @if($a->image_url)
                            <div style="margin-top:10px;">
                                <img src="{{ $a->image_url }}" alt="Image annonce" style="border-radius:14px; border:1px solid var(--border); max-height:220px; width:100%; object-fit:cover;">
                            </div>
                        @endif
                    </a>
                @empty
                    <div class="item" style="color:var(--muted); text-align:center;">Aucune annonce pour le moment.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Membres par pays --}}
    <div class="card" style="margin-top:16px;">
        <div class="section__head">
            <div class="section__title">Membres par pays</div>
            <div class="section__link">Lecture seule</div>
        </div>

        <table class="table">
            <thead>
            <tr>
                <th>Pays</th>
                <th>Total</th>
            </tr>
            </thead>
            <tbody>
            @foreach($parPays as $row)
                <tr>
                    <td style="font-weight:900;">{{ $row->nom }}</td>
                    <td>{{ $row->total }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</x-member-layout>
