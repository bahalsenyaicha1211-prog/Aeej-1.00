{{-- resources/views/dashboard.blade.php --}}
<x-member-layout :unreadAnnoncesCount="$unreadAnnoncesCount ?? 0">
    <x-slot name="header">Tableau de bord</x-slot>

    <style>
        /* Libellé court des boutons d'action : masqué par défaut (desktop). */
        .dash-action__mini { display: none; }

        /* Mobile : on garde 2 cartes par ligne pour économiser de l'espace,
           au lieu d'empiler des cartes pleine largeur. */
        @media (max-width: 520px) {
            /* Solde de la caisse + action (dépense / paiement) sur la même ligne */
            .dash-tres.grid { grid-template-columns: repeat(2, minmax(0, 1fr)); align-items: stretch; gap: 12px; }
            .dash-tres .card { padding: 14px; }
            .dash-tres .kpi__value { font-size: 22px; }
            .dash-tres .dash-action {
                background: none; border: none; box-shadow: none; padding: 0;
                display: flex; align-items: center; justify-content: center;
            }
            .dash-tres .dash-action .kpi__label { display: none; }
            .dash-tres .dash-action .btn {
                margin-top: 0 !important;
                padding: 10px 12px;
                font-size: 12px;
            }
            .dash-tres .dash-action__full { display: none; }
            .dash-tres .dash-action__mini { display: inline; }

            /* KPIs : Total membres + Pays, puis Hommes + Femmes */
            .dash-kpis.grid { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; }
            .dash-kpis .card { padding: 14px; }
            .dash-kpis .kpi__value { font-size: 24px; }
        }
    </style>

    @if($caisseSolde !== null || $mesCotisationsCount !== null)
    <div class="grid grid-3 dash-tres" style="margin-bottom:16px;">
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
        <div class="card dash-action">
            <div class="kpi__label">Cotisations</div>
            <a class="btn btn--primary" href="{{ route('tresorerie.cotisations.create') }}" style="display:inline-flex; align-items:center; gap:6px; margin-top:10px;">
                <x-icon name="coins"/>
                <span class="dash-action__full">Enregistrer un paiement</span>
                <span class="dash-action__mini">Paiement</span>
            </a>
        </div>
        @endif

        @if($user->isCommissaireComptes())
        <div class="card dash-action">
            <div class="kpi__label">Dépenses</div>
            <a class="btn btn--primary" href="{{ route('tresorerie.depenses.create') }}" style="display:inline-flex; align-items:center; gap:6px; margin-top:10px;">
                <x-icon name="banknote"/>
                <span class="dash-action__full">Enregistrer une dépense</span>
                <span class="dash-action__mini">Dépense</span>
            </a>
        </div>
        @endif
    </div>
    @endif

    {{-- KPIs --}}
    {{-- Ordre pensé pour le mobile (grille 2 colonnes) :
         ligne 1 = Total membres / Pays, ligne 2 = Hommes / Femmes. --}}
    <div class="grid grid-4 dash-kpis" style="margin-bottom:16px;">
        <div class="card">
            <div class="kpi__label">Total membres</div>
            <div class="kpi__value">{{ $totalMembres }}</div>
        </div>

        <div class="card">
            <div class="kpi__label">Pays représentés</div>
            <div class="kpi__value">{{ $paysCount }}</div>
        </div>

        <div class="card">
            <div class="kpi__label">Hommes</div>
            <div class="kpi__value">{{ $parSexe['M'] ?? 0 }}</div>
        </div>

        <div class="card">
            <div class="kpi__label">Femmes</div>
            <div class="kpi__value">{{ $parSexe['F'] ?? 0 }}</div>
        </div>
    </div>

    {{-- Dernières annonces --}}
    <div class="card" style="margin-bottom:16px;">
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

    {{-- Membres par pays --}}
    <div class="card">
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
