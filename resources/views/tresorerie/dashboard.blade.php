@extends('layouts.tresorerie')

@section('title', 'Trésorerie • Accueil')
@section('header', 'Tableau de bord')

@section('content')
@php $user = auth()->user(); @endphp
<div class="admDash">
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">Bonjour {{ $user->name }} 👋</h1>
            <p class="admDash__sub">
                @if($user->isChefTresorier()) Chef trésorier
                @elseif($user->isTresorier()) Trésorier
                @elseif($user->isCommissaireComptes()) Commissaire aux comptes
                @endif
            </p>
        </div>
    </div>

    <div class="admGrid">
        @if($caisse !== null)
        <div class="admPanel" style="grid-column: span 4;">
            <div class="admPanel__body">
                <div style="color:#64748b; font-size:12px; text-transform:uppercase; font-weight:700;">Solde de la caisse</div>
                <div style="font-size:32px; font-weight:900; color:#4ade80; margin-top:8px;">{{ number_format($caisse, 2, ',', ' ') }} TND</div>
                <a href="{{ route('tresorerie.caisse.index') }}" style="color:#60a5fa; font-size:12px; text-decoration:none;">Voir le détail →</a>
            </div>
        </div>
        @endif

        @if($mesCotisationsCount !== null)
        <div class="admPanel" style="grid-column: span 4;">
            <div class="admPanel__body">
                <div style="color:#64748b; font-size:12px; text-transform:uppercase; font-weight:700;">Mes cotisations enregistrées ({{ now()->year }})</div>
                <div style="font-size:32px; font-weight:900; color:#e2e8f0; margin-top:8px;">{{ $mesCotisationsCount }}</div>
                <a href="{{ route('tresorerie.cotisations.index') }}" style="color:#60a5fa; font-size:12px; text-decoration:none;">Voir la liste →</a>
            </div>
        </div>
        @endif

        @if($user->isTresorier())
        <div class="admPanel" style="grid-column: span 4;">
            <div class="admPanel__body">
                <a href="{{ route('tresorerie.cotisations.create') }}" style="text-decoration:none; color:#22c55e; font-weight:800; font-size:14px;">+ Enregistrer un paiement de cotisation</a>
            </div>
        </div>
        @endif

        @if($user->isCommissaireComptes())
        <div class="admPanel" style="grid-column: span 4;">
            <div class="admPanel__body">
                <a href="{{ route('tresorerie.depenses.create') }}" style="text-decoration:none; color:#22c55e; font-weight:800; font-size:14px;">+ Enregistrer une dépense</a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
