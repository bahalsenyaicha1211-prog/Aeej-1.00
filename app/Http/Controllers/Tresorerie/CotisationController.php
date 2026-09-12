<?php

namespace App\Http\Controllers\Tresorerie;

use App\Http\Controllers\Controller;
use App\Models\Cotisation;
use App\Models\CotisationConfig;
use App\Models\CotisationType;
use App\Models\CotisationVolontaire;
use App\Models\Membre;
use App\Support\AcademicYear;
use Illuminate\Http\Request;

class CotisationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $q = trim((string) $request->query('q', ''));
        $tab = $request->query('tab') === 'volontaire' ? 'volontaire' : 'annuelle';

        if ($tab === 'volontaire') {
            $query = CotisationVolontaire::with(['membre', 'type', 'tresorier'])
                ->orderByDesc('date_paiement')
                ->orderByDesc('created_at');

            if (!$user->isChefTresorier()) {
                $query->where('created_by', $user->id);
            }

            if ($request->filled('annee')) {
                $annee = (int) $request->input('annee');
                $query->whereHas('type', fn ($t) => $t->where('annee', $annee));
            }

            if ($q !== '') {
                $query->where(function ($sub) use ($q) {
                    $sub->where('matricule', 'like', "%{$q}%")
                        ->orWhereHas('membre', function ($m) use ($q) {
                            $m->where('nom', 'like', "%{$q}%")
                              ->orWhere('prenom', 'like', "%{$q}%")
                              ->orWhereRaw("CONCAT(prenom, ' ', nom) like ?", ["%{$q}%"])
                              ->orWhereRaw("CONCAT(nom, ' ', prenom) like ?", ["%{$q}%"]);
                        })
                        ->orWhereHas('type', fn ($t) => $t->where('nom', 'like', "%{$q}%"));
                });
            }

            $cotisationsVolontaires = $query->paginate(20)->withQueryString();

            return view('tresorerie.cotisations.index', ['tab' => $tab, 'q' => $q, 'cotisationsVolontaires' => $cotisationsVolontaires]);
        }

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

        return view('tresorerie.cotisations.index', ['tab' => $tab, 'q' => $q, 'cotisations' => $cotisations]);
    }

    public function create(Request $request)
    {
        $membres = Membre::with('pays')->orderBy('prenom')->orderBy('nom')->get();
        $anneeActive = AcademicYear::anneeActive();
        $config = CotisationConfig::pourAnnee($anneeActive);
        $membresBureau = \App\Models\BureauMembre::where('is_actif', true)->pluck('matricule')->all();
        $typesVolontaires = CotisationType::actifs()->orderByDesc('annee')->orderBy('nom')->get();

        return view('tresorerie.cotisations.create', compact('membres', 'anneeActive', 'config', 'membresBureau', 'typesVolontaires'));
    }

    public function store(Request $request)
    {
        // L'année n'est jamais un choix libre : seule l'année académique en
        // cours (ex. 2026-2027) est éligible à un nouveau paiement annuel.
        $annee = AcademicYear::anneeActive();

        $data = $request->validate([
            'matricule' => ['required', 'exists:membres,matricule'],
            'montant_paye' => ['required', 'numeric', 'min:0'],
            'date_paiement' => ['required', 'date'],
        ]);

        if (Cotisation::where('matricule', $data['matricule'])->where('annee', $annee)->exists()) {
            return back()->withInput()->withErrors([
                'matricule' => 'Une cotisation existe déjà pour ce membre en ' . AcademicYear::label($annee) . '. Modifiez-la plutôt depuis la liste.',
            ]);
        }

        $config = CotisationConfig::pourAnnee($annee);
        if (!$config) {
            return back()->withInput()->withErrors([
                'matricule' => "Aucun montant de cotisation n'est configuré pour l'année " . AcademicYear::label($annee) . ". Demandez au chef trésorier de le définir.",
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
            'annee' => $annee,
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
