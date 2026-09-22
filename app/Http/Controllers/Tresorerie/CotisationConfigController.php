<?php

namespace App\Http\Controllers\Tresorerie;

use App\Http\Controllers\Controller;
use App\Models\Cotisation;
use App\Models\CotisationConfig;
use App\Models\CotisationType;
use App\Support\AcademicYear;
use Illuminate\Http\Request;

class CotisationConfigController extends Controller
{
    public function edit()
    {
        $configs = CotisationConfig::orderByDesc('annee')->get();
        $types = CotisationType::withCount('paiements')->orderByDesc('annee')->orderBy('nom')->get()->groupBy('annee');
        $anneeActive = AcademicYear::anneeActive();
        $paiementsParAnnee = Cotisation::selectRaw('annee, COUNT(*) as total')->groupBy('annee')->pluck('total', 'annee');

        return view('tresorerie.config.edit', compact('configs', 'types', 'anneeActive', 'paiementsParAnnee'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'annee' => ['required', 'integer', 'min:2010', 'max:' . (date('Y') + 1)],
            'montant_membre' => ['required', 'numeric', 'min:0'],
            'montant_bureau' => ['required', 'numeric', 'min:0'],
        ]);

        CotisationConfig::updateOrCreate(
            ['annee' => $data['annee']],
            ['montant_membre' => $data['montant_membre'], 'montant_bureau' => $data['montant_bureau']]
        );

        return redirect()->route('tresorerie.config.edit')->with('success', "Montants de cotisation mis à jour pour {$data['annee']}.");
    }

    public function destroy(CotisationConfig $config)
    {
        $label = AcademicYear::label($config->annee);
        $config->delete();

        return redirect()->route('tresorerie.config.edit')->with('success', "Montants de cotisation supprimés pour {$label}. Les paiements déjà enregistrés pour cette année ne sont pas affectés.");
    }

    public function storeType(Request $request)
    {
        $data = $request->validate([
            'annee' => ['required', 'integer', 'min:2010', 'max:' . (date('Y') + 1)],
            'nom' => ['required', 'string', 'max:100'],
            'montant' => ['required', 'numeric', 'min:0'],
        ]);

        $exists = CotisationType::where('annee', $data['annee'])->where('nom', $data['nom'])->exists();
        if ($exists) {
            return back()->withInput()->withErrors([
                'nom' => "Une cotisation volontaire « {$data['nom']} » existe déjà pour {$data['annee']}.",
            ]);
        }

        CotisationType::create($data + ['created_by' => $request->user()->id, 'actif' => true]);

        return redirect()->route('tresorerie.config.edit')->with('success', "Cotisation volontaire « {$data['nom']} » ajoutée pour {$data['annee']}.");
    }

    public function toggleType(CotisationType $type)
    {
        $type->update(['actif' => !$type->actif]);

        return redirect()->route('tresorerie.config.edit')->with(
            'success',
            $type->actif
                ? "« {$type->nom} » est de nouveau proposée."
                : "« {$type->nom} » est retirée des choix disponibles (les paiements déjà enregistrés sont conservés)."
        );
    }

    public function destroyType(CotisationType $type)
    {
        if ($type->paiements()->exists()) {
            return back()->withErrors([
                'nom' => "Impossible de supprimer « {$type->nom} » : des paiements y sont déjà rattachés. Désactivez-la plutôt.",
            ]);
        }

        $nom = $type->nom;
        $type->delete();

        return redirect()->route('tresorerie.config.edit')->with('success', "« {$nom} » supprimée.");
    }
}
