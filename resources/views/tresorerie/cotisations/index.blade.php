@extends('layouts.tresorerie')

@section('title', 'Trésorerie • Cotisations')
@section('header', 'Cotisations')

@section('content')
@php $user = auth()->user(); @endphp
<div class="admDash">
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">{{ $user->isChefTresorier() ? 'Toutes les cotisations' : 'Mes cotisations enregistrées' }}</h1>
            <p class="admDash__sub">Paiements de cotisation annuelle des membres.</p>
        </div>
        <a class="btn" style="background: #22c55e; color: #fff; border-radius: 12px; padding: 10px 20px; font-weight: 800; text-decoration: none;" href="{{ route('tresorerie.cotisations.create') }}">
            + Enregistrer un paiement
        </a>
    </div>

    <form method="GET" style="margin-bottom: 16px; display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
        <input class="input" type="text" name="q" value="{{ $q }}" placeholder="Rechercher par nom ou matricule..." style="max-width:280px; width:100%;">
        <input class="input" type="number" name="annee" value="{{ request('annee') }}" placeholder="Filtrer par année" style="max-width:180px;">
        <button class="admQuick__btn" type="submit">Filtrer</button>
        @if(request('annee') || $q !== '')
            <a class="admQuick__btn" href="{{ route('tresorerie.cotisations.index') }}" style="text-decoration:none;">Réinitialiser</a>
        @endif
    </form>

    <div class="admPanel admPanel--full">
        <div class="admPanel__body" style="padding: 0;">
            <div class="table-wrap">
                <table class="table" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: rgba(255,255,255,0.02); text-align: left;">
                            <th style="padding: 15px; color: #64748b; font-size: 11px; text-transform: uppercase;">Membre</th>
                            <th style="padding: 15px; color: #64748b; font-size: 11px; text-transform: uppercase;">Année</th>
                            <th style="padding: 15px; color: #64748b; font-size: 11px; text-transform: uppercase;">Catégorie</th>
                            <th style="padding: 15px; color: #64748b; font-size: 11px; text-transform: uppercase; text-align:right;">Dû</th>
                            <th style="padding: 15px; color: #64748b; font-size: 11px; text-transform: uppercase; text-align:right;">Payé</th>
                            <th style="padding: 15px; color: #64748b; font-size: 11px; text-transform: uppercase; text-align:right;">Reste</th>
                            <th style="padding: 15px; color: #64748b; font-size: 11px; text-transform: uppercase;">Date</th>
                            @if($user->isChefTresorier())
                                <th style="padding: 15px; color: #64748b; font-size: 11px; text-transform: uppercase;">Trésorier</th>
                            @endif
                            <th style="padding: 15px; color: #64748b; font-size: 11px; text-transform: uppercase; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="admRows">
                        @forelse($cotisations as $c)
                        <tr class="admRow" style="display: table-row; background: transparent; border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <td style="padding: 15px;">
                                <div style="font-weight: 800; color: #fff;">{{ $c->membre->prenom ?? '?' }} {{ $c->membre->nom ?? '' }}</div>
                                <div style="font-size: 11px; color: #64748b; font-family: monospace;">{{ $c->matricule }}</div>
                            </td>
                            <td style="padding: 15px; color:#e2e8f0;">{{ $c->annee }}</td>
                            <td style="padding: 15px;">
                                <span style="padding: 4px 10px; border-radius: 8px; font-size: 10px; font-weight: 900; background: {{ $c->categorie === 'bureau' ? 'rgba(139,92,246,0.1)' : 'rgba(255,255,255,0.05)' }}; color: {{ $c->categorie === 'bureau' ? '#a78bfa' : '#94a3b8' }};">
                                    {{ $c->categorie === 'bureau' ? 'BUREAU' : 'MEMBRE' }}
                                </span>
                            </td>
                            <td style="padding: 15px; text-align:right; color:#e2e8f0;">{{ number_format($c->montant_du, 2, ',', ' ') }}</td>
                            <td style="padding: 15px; text-align:right; color:#4ade80; font-weight:700;">{{ number_format($c->montant_paye, 2, ',', ' ') }}</td>
                            <td style="padding: 15px; text-align:right; color:{{ $c->reste > 0 ? '#fbbf24' : '#64748b' }}; font-weight:700;">{{ number_format($c->reste, 2, ',', ' ') }}</td>
                            <td style="padding: 15px; color:#64748b; font-size:12px;">{{ $c->date_paiement->format('d/m/Y') }}</td>
                            @if($user->isChefTresorier())
                                <td style="padding: 15px; color:#64748b; font-size:12px;">{{ $c->tresorier->name ?? '—' }}</td>
                            @endif
                            <td style="padding: 15px; text-align: right;">
                                <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                    <a class="admQuick__btn" href="{{ route('tresorerie.cotisations.edit', $c) }}" style="padding: 6px 12px; font-size: 12px; text-decoration: none;">Modifier</a>
                                    <form method="POST" action="{{ route('tresorerie.cotisations.destroy', $c) }}" onsubmit="return confirm('Supprimer cet enregistrement ?')">
                                        @csrf @method('DELETE')
                                        <button class="admQuick__btn" style="border-color: rgba(239,68,68,0.3); color: #f87171; background: rgba(239,68,68,0.05); padding: 6px 12px; font-size: 12px;">Supprimer</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="9" style="padding: 40px; text-align: center; color: #64748b;">{{ $q !== '' ? 'Aucune cotisation ne correspond à « '.$q.' ».' : 'Aucune cotisation enregistrée.' }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div style="margin-top: 15px;">{{ $cotisations->links('vendor.pagination.admin') }}</div>
</div>
@endsection
