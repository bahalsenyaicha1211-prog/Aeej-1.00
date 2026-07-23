<x-member-layout>
    <x-slot name="header">Ma cotisation</x-slot>

    <div class="container" style="padding:0;">

        @if($cotisationActuelle)
            <div class="card" style="margin-bottom:20px;">
                <div class="section__head">
                    <div class="section__title">Cotisation {{ $anneeActuelle }}</div>
                </div>

                <div style="display:grid; grid-template-columns: repeat(3, 1fr); gap:16px; padding:16px 0;">
                    <div>
                        <div style="color:#718096; font-size:12px; text-transform:uppercase; font-weight:700;">Montant dû</div>
                        <div style="font-size:22px; font-weight:900; color:#1a202c;">{{ number_format($cotisationActuelle->montant_du, 2, ',', ' ') }} TND</div>
                    </div>
                    <div>
                        <div style="color:#718096; font-size:12px; text-transform:uppercase; font-weight:700;">Déjà payé</div>
                        <div style="font-size:22px; font-weight:900; color:#22a559;">{{ number_format($cotisationActuelle->montant_paye, 2, ',', ' ') }} TND</div>
                    </div>
                    <div>
                        <div style="color:#718096; font-size:12px; text-transform:uppercase; font-weight:700;">Reste à payer</div>
                        <div style="font-size:22px; font-weight:900; color:{{ $cotisationActuelle->reste > 0 ? '#d97706' : '#22a559' }};">{{ number_format($cotisationActuelle->reste, 2, ',', ' ') }} TND</div>
                    </div>
                </div>

                @if($cotisationActuelle->reste > 0)
                    <div style="background:#fffbeb; border:1px solid #fde68a; color:#92400e; padding:12px 16px; border-radius:10px; font-size:13px; font-weight:600;">
                        Il vous reste {{ number_format($cotisationActuelle->reste, 2, ',', ' ') }} TND à régler pour {{ $anneeActuelle }}. Contactez un trésorier pour compléter votre paiement.
                    </div>
                @else
                    <div style="background:#f0fff4; border:1px solid #9ae6b4; color:#276749; padding:12px 16px; border-radius:10px; font-size:13px; font-weight:600;">
                        Votre cotisation {{ $anneeActuelle }} est entièrement réglée. Merci !
                    </div>
                @endif
            </div>
        @else
            <div class="card" style="margin-bottom:20px;">
                <div style="padding:20px; color:#718096; text-align:center;">
                    Aucun paiement de cotisation enregistré pour {{ $anneeActuelle }} pour le moment.
                </div>
            </div>
        @endif

        <div class="card">
            <div class="section__head">
                <div class="section__title">Historique</div>
            </div>

            <div class="list">
                @forelse($cotisations as $c)
                    <div class="item" style="display:flex; justify-content:space-between; align-items:center; gap:16px;">
                        <div>
                            <div style="font-weight:900;">Année {{ $c->annee }}</div>
                            <div style="color:#718096; font-size:12px;">Payé le {{ $c->date_paiement->format('d/m/Y') }}</div>
                        </div>
                        <div style="text-align:right;">
                            <div style="font-weight:800; color:#22a559;">{{ number_format($c->montant_paye, 2, ',', ' ') }} / {{ number_format($c->montant_du, 2, ',', ' ') }} TND</div>
                            @if($c->reste > 0)
                                <div style="color:#d97706; font-size:12px; font-weight:700;">Reste {{ number_format($c->reste, 2, ',', ' ') }} TND</div>
                            @else
                                <div style="color:#22a559; font-size:12px; font-weight:700;">Réglée</div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="item" style="color:var(--muted); text-align:center;">Aucun historique de cotisation.</div>
                @endforelse
            </div>
        </div>
    </div>
</x-member-layout>
