<?php

namespace App\Http\Controllers\Tresorerie;

use App\Http\Controllers\Controller;
use App\Models\Cotisation;
use App\Models\CotisationConfig;
use App\Models\Membre;
use Illuminate\Http\Request;

class CotisationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Cotisation::with(['membre', 'tresorier'])
            ->orderByDesc('annee')
            ->orderByDesc('created_at');

        if (!$user->isChefTresorier()) {
            $query->where('created_by', $user->id);
        }

        if ($request->filled('annee')) {
            $query->where('annee', (int) $request->input('annee'));
        }

        $cotisations = $query->paginate(20)->withQueryString();

        return view('tresorerie.cotisations.index', compact('cotisations'));
    }

    public function create(Request $request)
    {
        $membres = Membre::with('pays')->orderBy('prenom')->orderBy('nom')->get();
        $configs = CotisationConfig::orderByDesc('annee')->get(['annee', 'montant_membre', 'montant_bureau']);
        $membresBureau = \App\Models\BureauMembre::where('is_actif', true)->pluck('matricule')->all();

        return view('tresorerie.cotisations.create', compact('membres', 'configs', 'membresBureau'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'matricule' => ['required', 'exists:membres,matricule'],
            'annee' => ['required', 'integer', 'min:2010', 'max:' . (date('Y') + 1)],
            'montant_paye' => ['required', 'numeric', 'min:0'],
            'date_paiement' => ['required', 'date'],
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

        return view('tresorerie.cotisations.edit', compact('cotisation'));
    }

    public function update(Request $request, Cotisation $cotisation)
    {
        $this->autoriserGestion($request, $cotisation);

        $data = $request->validate([
            'montant_paye' => ['required', 'numeric', 'min:0'],
            'date_paiement' => ['required', 'date'],
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
}
