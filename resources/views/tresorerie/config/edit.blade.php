<x-member-layout>
    <x-slot name="header">Montants de cotisation</x-slot>

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
</x-member-layout>
