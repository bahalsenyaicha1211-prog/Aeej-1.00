<?php

namespace App\Http\Controllers\Tresorerie;

use App\Http\Controllers\Controller;
use App\Models\Cotisation;
use App\Models\Depense;
use Illuminate\Support\Facades\DB;

class CaisseController extends Controller
{
    public function index()
    {
        $totalCotisations = (float) Cotisation::sum('montant_paye');
        $totalDepenses = (float) Depense::sum('montant_total');
        $solde = $totalCotisations - $totalDepenses;

        $cotisationsParAnnee = Cotisation::select('annee', DB::raw('SUM(montant_paye) as total'), DB::raw('COUNT(*) as nb'))
            ->groupBy('annee')
            ->orderByDesc('annee')
            ->get();

        $depensesRecentes = Depense::orderByDesc('date_depense')->take(10)->get();

        return view('tresorerie.caisse.index', compact('solde', 'totalCotisations', 'totalDepenses', 'cotisationsParAnnee', 'depensesRecentes'));
    }
}
