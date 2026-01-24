{{-- resources/views/dashboard.blade.php --}}
<x-member-layout :unreadAnnoncesCount="$unreadAnnoncesCount ?? 0">
    <x-slot name="header">Tableau de bord</x-slot>

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
