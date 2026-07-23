<?php

namespace App\Http\Controllers\Tresorerie;

use App\Http\Controllers\Controller;
use App\Models\Cotisation;
use App\Models\Depense;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepenseController extends Controller
{
    public function index()
    {
        $depenses = Depense::with('commissaire')
            ->orderByDesc('date_depense')
            ->paginate(15);

        return view('tresorerie.depenses.index', compact('depenses'));
    }

    public function create()
    {
        return view('tresorerie.depenses.create');
    }

    public function store(Request $request)
    {
        $data = $this->validerDepense($request);

        DB::transaction(function () use ($data, $request) {
            $depense = Depense::create([
                'nom_evenement' => $data['nom_evenement'],
                'date_depense' => $data['date_depense'],
                'created_by' => $request->user()->id,
                'montant_total' => 0,
            ]);

            foreach ($data['lignes'] as $ligne) {
                $depense->lignes()->create($ligne);
            }

            $depense->recalculerTotal();
            $depense->save();
        });

        return redirect()->route('tresorerie.depenses.index')->with('success', 'Dépense enregistrée.');
    }

    public function edit(Depense $depense)
    {
        $depense->load('lignes');

        return view('tresorerie.depenses.edit', compact('depense'));
    }

    public function update(Request $request, Depense $depense)
    {
        $data = $this->validerDepense($request);

        DB::transaction(function () use ($data, $depense) {
            $depense->nom_evenement = $data['nom_evenement'];
            $depense->date_depense = $data['date_depense'];

            $depense->lignes()->delete();
            foreach ($data['lignes'] as $ligne) {
                $depense->lignes()->create($ligne);
            }

            $depense->recalculerTotal();
            $depense->save();
        });

        return redirect()->route('tresorerie.depenses.index')->with('success', 'Dépense mise à jour.');
    }

    public function destroy(Depense $depense)
    {
        $depense->delete();

        return redirect()->route('tresorerie.depenses.index')->with('success', 'Dépense supprimée.');
    }

    public function rapportPdf()
    {
        $depenses = Depense::with('lignes', 'commissaire')->orderByDesc('date_depense')->get();
        $totalDepenses = (float) $depenses->sum('montant_total');

        $cotisationsParAnnee = Cotisation::select('annee', DB::raw('SUM(montant_paye) as total'))
            ->groupBy('annee')
            ->orderByDesc('annee')
            ->get();
        $totalCotisations = (float) $cotisationsParAnnee->sum('total');

        $solde = $totalCotisations - $totalDepenses;

        $pdf = Pdf::loadView('tresorerie.depenses.rapport-pdf', [
            'depenses' => $depenses,
            'totalDepenses' => $totalDepenses,
            'cotisationsParAnnee' => $cotisationsParAnnee,
            'totalCotisations' => $totalCotisations,
            'solde' => $solde,
            'genereLe' => now(),
        ]);

        return $pdf->download('rapport-financier-aeej-' . now()->format('Y-m-d') . '.pdf');
    }

    private function validerDepense(Request $request): array
    {
        $data = $request->validate([
            'nom_evenement' => ['required', 'string', 'max:255'],
            'date_depense' => ['required', 'date'],
            'lignes' => ['required', 'array', 'min:1'],
            'lignes.*.designation' => ['required', 'string', 'max:255'],
            'lignes.*.montant' => ['required', 'numeric', 'min:0.01'],
        ]);

        $data['lignes'] = array_map(fn ($l) => [
            'designation' => $l['designation'],
            'montant' => $l['montant'],
        ], $data['lignes']);

        return $data;
    }
}
