<?php

namespace App\Http\Controllers\Tresorerie;

use App\Http\Controllers\Controller;
use App\Models\CotisationVolontaire;
use App\Models\CotisationType;
use App\Models\Membre;
use Illuminate\Http\Request;

class CotisationVolontaireController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'matricule' => ['required', 'exists:membres,matricule'],
            'cotisation_type_id' => ['required', 'exists:cotisation_types,id'],
            'montant_paye' => ['required', 'numeric', 'min:0'],
            'date_paiement' => ['required', 'date'],
        ]);

        $type = CotisationType::findOrFail($data['cotisation_type_id']);

        CotisationVolontaire::create([
            'matricule' => $data['matricule'],
            'cotisation_type_id' => $type->id,
            'montant_paye' => $data['montant_paye'],
            'date_paiement' => $data['date_paiement'],
            'created_by' => $request->user()->id,
        ]);

        return redirect()->route('tresorerie.cotisations.index', ['tab' => 'volontaire'])
            ->with('success', "Paiement « {$type->nom} » enregistré pour {$type->annee}.");
    }

    public function edit(Request $request, CotisationVolontaire $volontaire)
    {
        $this->autoriserGestion($request, $volontaire);

        $volontaire->load('membre.pays', 'type');

        return view('tresorerie.cotisations.edit-volontaire', compact('volontaire'));
    }

    public function update(Request $request, CotisationVolontaire $volontaire)
    {
        $this->autoriserGestion($request, $volontaire);

        $data = $request->validate([
            'montant_paye' => ['required', 'numeric', 'min:0'],
            'date_paiement' => ['required', 'date'],
        ]);

        $volontaire->update($data + ['updated_by' => $request->user()->id]);

        return redirect()->route('tresorerie.cotisations.index', ['tab' => 'volontaire'])
            ->with('success', 'Paiement mis à jour.');
    }

    public function destroy(Request $request, CotisationVolontaire $volontaire)
    {
        $this->autoriserGestion($request, $volontaire);

        $volontaire->delete();

        return redirect()->route('tresorerie.cotisations.index', ['tab' => 'volontaire'])
            ->with('success', 'Enregistrement supprimé.');
    }

    private function autoriserGestion(Request $request, CotisationVolontaire $volontaire): void
    {
        $user = $request->user();

        if (!$user->isChefTresorier() && $volontaire->created_by !== $user->id) {
            abort(403, "Vous ne pouvez modifier que vos propres enregistrements.");
        }
    }
}
