<?php

namespace App\Http\Controllers\Tresorerie;

use App\Http\Controllers\Controller;
use App\Models\Cotisation;
use App\Models\CotisationConfig;
use App\Models\CotisationDate;
use App\Models\Membre;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CotisationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $q = trim((string) $request->query('q', ''));

        $query = Cotisation::with(['membre', 'tresorier'])
            ->orderByDesc('annee')
            ->orderByDesc('created_at');

        if (!$user->isChefTresorier()) {
            $query->where('created_by', $user->id);
        }

        if ($request->filled('annee')) {
            $query->where('annee', (int) $request->input('annee'));
        }

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('matricule', 'like', "%{$q}%")
                    ->orWhereHas('membre', function ($m) use ($q) {
                        $m->where('nom', 'like', "%{$q}%")
                          ->orWhere('prenom', 'like', "%{$q}%")
                          ->orWhereRaw("CONCAT(prenom, ' ', nom) like ?", ["%{$q}%"])
                          ->orWhereRaw("CONCAT(nom, ' ', prenom) like ?", ["%{$q}%"]);
                    });
            });
        }

        $cotisations = $query->paginate(20)->withQueryString();

        return view('tresorerie.cotisations.index', compact('cotisations', 'q'));
    }

    public function create(Request $request)
    {
        $membres = Membre::with('pays')->orderBy('prenom')->orderBy('nom')->get();
        $configs = CotisationConfig::orderByDesc('annee')->get(['annee', 'montant_membre', 'montant_bureau']);
        $dates = CotisationDate::orderBy('annee')->orderBy('date_collecte')->get()->groupBy('annee');
        $membresBureau = \App\Models\BureauMembre::where('is_actif', true)->pluck('matricule')->all();

        return view('tresorerie.cotisations.create', compact('membres', 'configs', 'dates', 'membresBureau'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'matricule' => ['required', 'exists:membres,matricule'],
            'annee' => ['required', 'integer', 'min:2010', 'max:' . (date('Y') + 1)],
            'montant_paye' => ['required', 'numeric', 'min:0'],
            'date_paiement' => ['required', 'date', Rule::in($this->datesValides((int) $request->input('annee')))],
        ], [
            'date_paiement.in' => "Cette date ne fait pas partie des dates de collecte configurées pour cette année.",
        ]);

        if (Cotisation::where('matricule', $data['matricule'])->where('annee', $data['annee'])->exists()) {
            return back()->withInput()->withErrors([
                'matricule' => 'Une cotisation existe déjà pour ce membre en ' . $data['annee'] . '. Modifiez-la plutôt depuis la liste.',
            ]);
        }

        $config = CotisationConfig::pourAnnee($data['annee']);
        if (!$config) {
            return back()->withInput()->withErrors([
                'annee' => "Aucun montant de cotisation n'est configuré pour l'année {$data['annee']}. Demandez au chef trésorier de le définir.",
            ]);
        }

        $membre = Membre::findOrFail($data['matricule']);
        $categorie = $membre->estMembreDuBureau() ? 'bureau' : 'membre';
        $montantDu = $categorie === 'bureau' ? $config->montant_bureau : $config->montant_membre;

        if ($data['montant_paye'] > $montantDu) {
            return back()->withInput()->withErrors([
                'montant_paye' => 'Le montant payé ne peut pas dépasser le montant dû (' . number_format($montantDu, 2, ',', ' ') . ' TND).',
            ]);
        }

        $cotisation = new Cotisation([
            'matricule' => $data['matricule'],
            'annee' => $data['annee'],
            'categorie' => $categorie,
            'montant_du' => $montantDu,
            'montant_paye' => $data['montant_paye'],
            'date_paiement' => $data['date_paiement'],
            'created_by' => $request->user()->id,
        ]);
        $cotisation->recalculerReste();
        $cotisation->save();

        return redirect()->route('tresorerie.cotisations.index')->with('success', 'Paiement de cotisation enregistré.');
    }

    public function edit(Request $request, Cotisation $cotisation)
    {
        $this->autoriserGestion($request, $cotisation);

        $cotisation->load('membre.pays');
        $dates = CotisationDate::where('annee', $cotisation->annee)->orderBy('date_collecte')->get();

        return view('tresorerie.cotisations.edit', compact('cotisation', 'dates'));
    }

    public function update(Request $request, Cotisation $cotisation)
    {
        $this->autoriserGestion($request, $cotisation);

        // L'ancienne date reste acceptée même si elle a depuis été retirée de la
        // liste des dates de collecte, pour ne pas bloquer la modification d'une
        // cotisation existante ni faire disparaître silencieusement sa date.
        $datesAcceptees = $this->datesValides($cotisation->annee)
            ->push($cotisation->date_paiement->toDateString())
            ->unique();

        $data = $request->validate([
            'montant_paye' => ['required', 'numeric', 'min:0'],
            'date_paiement' => ['required', 'date', Rule::in($datesAcceptees)],
        ], [
            'date_paiement.in' => "Cette date ne fait pas partie des dates de collecte configurées pour cette année.",
        ]);

        if ($data['montant_paye'] > $cotisation->montant_du) {
            return back()->withInput()->withErrors([
                'montant_paye' => 'Le montant payé ne peut pas dépasser le montant dû (' . number_format($cotisation->montant_du, 2, ',', ' ') . ' TND).',
            ]);
        }

        $cotisation->montant_paye = $data['montant_paye'];
        $cotisation->date_paiement = $data['date_paiement'];
        $cotisation->updated_by = $request->user()->id;
        $cotisation->recalculerReste();
        $cotisation->save();

        return redirect()->route('tresorerie.cotisations.index')->with('success', 'Cotisation mise à jour.');
    }

    public function destroy(Request $request, Cotisation $cotisation)
    {
        $this->autoriserGestion($request, $cotisation);

        $cotisation->delete();

        return redirect()->route('tresorerie.cotisations.index')->with('success', 'Enregistrement supprimé.');
    }

    private function autoriserGestion(Request $request, Cotisation $cotisation): void
    {
        $user = $request->user();

        if (!$user->isChefTresorier() && $cotisation->created_by !== $user->id) {
            abort(403, "Vous ne pouvez modifier que vos propres enregistrements.");
        }
    }

    /**
     * Dates de collecte configurées pour une année, au format Y-m-d.
     */
    private function datesValides(int $annee)
    {
        // pluck() renvoie une Collection de base (pas Eloquent) : ->unique()
        // ailleurs ne tente pas d'appeler getKey() sur de simples chaînes.
        return CotisationDate::where('annee', $annee)
            ->orderBy('date_collecte')
            ->pluck('date_collecte')
            ->map(fn ($d) => $d->toDateString());
    }
}
