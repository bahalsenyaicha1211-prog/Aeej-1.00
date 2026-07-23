<?php

namespace App\Http\Controllers\Tresorerie;

use App\Http\Controllers\Controller;
use App\Models\CotisationConfig;
use Illuminate\Http\Request;

class CotisationConfigController extends Controller
{
    public function edit()
    {
        $configs = CotisationConfig::orderByDesc('annee')->get();

        return view('tresorerie.config.edit', compact('configs'));
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
}
