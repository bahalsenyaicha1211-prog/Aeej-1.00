@extends('layouts.tresorerie')

@section('title', 'Trésorerie • Montants cotisation')
@section('header', 'Montants de cotisation')

@section('content')
<div class="admDash">
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">Montants de cotisation</h1>
            <p class="admDash__sub">Définissez le montant annuel dû par catégorie de membre. Un membre du bureau paie un montant différent d'un membre simple.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="admPanel admPanel--full" style="border-color: rgba(239,68,68,0.3); background: rgba(239,68,68,0.05); margin-bottom: 16px;">
            <div class="admPanel__body">
                @foreach($errors->all() as $error)
                    <div style="color:#fb7185; font-size: 13px; font-weight: 600;">⚠️ {{ $error }}</div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="admGrid">
        <div class="admPanel" style="grid-column: span 5;">
            <div class="admPanel__head"><h2 class="admPanel__h text-white">Définir / mettre à jour une année</h2></div>
            <div class="admPanel__body">
                <form class="admRows" method="POST" action="{{ route('tresorerie.config.update') }}">
                    @csrf
                    <div class="field">
                        <label class="admKpi__label text-white">Année *</label>
                        <input class="input" type="number" name="annee" value="{{ old('annee', now()->year) }}" min="2010" max="{{ now()->year + 1 }}" required style="width:100%;">
                    </div>
                    <div class="field" style="margin-top:16px;">
                        <label class="admKpi__label text-white">Montant membre simple (TND) *</label>
                        <input class="input" type="number" step="0.01" min="0" name="montant_membre" value="{{ old('montant_membre') }}" required style="width:100%;">
                    </div>
                    <div class="field" style="margin-top:16px;">
                        <label class="admKpi__label text-white">Montant membre du bureau (TND) *</label>
                        <input class="input" type="number" step="0.01" min="0" name="montant_bureau" value="{{ old('montant_bureau') }}" required style="width:100%;">
                    </div>

                    <div style="margin-top: 24px;">
                        <button class="btn" style="background: #22c55e; color: #fff; border-radius: 12px; padding: 12px 30px; font-weight: 800; border: none; cursor: pointer;" type="submit">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="admPanel admPanel--full" style="grid-column: span 7;">
            <div class="admPanel__head"><h2 class="admPanel__h text-white">Historique</h2></div>
            <div class="admPanel__body" style="padding:0;">
                <div class="table-wrap">
                    <table class="table" style="width:100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: rgba(255,255,255,0.02); text-align: left;">
                                <th style="padding: 15px; color: #64748b; font-size: 11px; text-transform: uppercase;">Année</th>
                                <th style="padding: 15px; color: #64748b; font-size: 11px; text-transform: uppercase; text-align:right;">Membre simple</th>
                                <th style="padding: 15px; color: #64748b; font-size: 11px; text-transform: uppercase; text-align:right;">Membre du bureau</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($configs as $c)
                            <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                <td style="padding: 15px; color:#e2e8f0; font-weight:800;">{{ $c->annee }}</td>
                                <td style="padding: 15px; text-align:right; color:#e2e8f0;">{{ number_format($c->montant_membre, 2, ',', ' ') }} TND</td>
                                <td style="padding: 15px; text-align:right; color:#a78bfa;">{{ number_format($c->montant_bureau, 2, ',', ' ') }} TND</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" style="padding: 40px; text-align: center; color: #64748b;">Aucun montant configuré pour le moment.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
