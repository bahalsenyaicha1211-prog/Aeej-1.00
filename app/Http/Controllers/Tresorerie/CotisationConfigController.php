<?php

namespace App\Http\Controllers\Tresorerie;

use App\Http\Controllers\Controller;
use App\Models\CotisationConfig;
use App\Models\CotisationDate;
use Illuminate\Http\Request;

class CotisationConfigController extends Controller
{
    public function edit()
    {
        $configs = CotisationConfig::orderByDesc('annee')->get();
        $dates = CotisationDate::orderByDesc('annee')->orderBy('date_collecte')->get()->groupBy('annee');

        return view('tresorerie.config.edit', compact('configs', 'dates'));
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

    public function storeDate(Request $request)
    {
        $data = $request->validate([
            'annee' => ['required', 'integer', 'min:2010', 'max:' . (date('Y') + 1)],
            'date_collecte' => ['required', 'date'],
        ]);

        $date = CotisationDate::firstOrCreate($data);

        return redirect()->route('tresorerie.config.edit')->with(
            $date->wasRecentlyCreated ? 'success' : 'error',
            $date->wasRecentlyCreated
                ? "Date de collecte ajoutée pour {$data['annee']}."
                : 'Cette date de collecte est déjà enregistrée pour cette année.'
        );
    }

    public function destroyDate(CotisationDate $date)
    {
        $annee = $date->annee;
        $date->delete();

        return redirect()->route('tresorerie.config.edit')->with('success', "Date de collecte retirée pour {$annee}.");
    }
}
