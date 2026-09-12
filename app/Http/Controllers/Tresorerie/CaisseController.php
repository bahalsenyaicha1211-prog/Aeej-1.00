<?php

namespace App\Http\Controllers\Tresorerie;

use App\Http\Controllers\Controller;
use App\Models\Cotisation;
use App\Models\CotisationVolontaire;
use App\Models\Depense;
use Illuminate\Support\Facades\DB;

class CaisseController extends Controller
{
    public function index()
    {
        $totalCotisationsAnnuelles = (float) Cotisation::sum('montant_paye');
        $totalCotisationsVolontaires = (float) CotisationVolontaire::sum('montant_paye');
        $totalCotisations = $totalCotisationsAnnuelles + $totalCotisationsVolontaires;
        $totalDepenses = (float) Depense::sum('montant_total');
        $solde = $totalCotisations - $totalDepenses;

        $cotisationsParAnnee = Cotisation::select('annee', DB::raw('SUM(montant_paye) as total'), DB::raw('COUNT(*) as nb'))
            ->groupBy('annee')
            ->orderByDesc('annee')
            ->get();

        $depensesRecentes = Depense::orderByDesc('date_depense')->take(10)->get();

        return view('tresorerie.caisse.index', compact(
            'solde',
            'totalCotisations',
            'totalCotisationsAnnuelles',
            'totalCotisationsVolontaires',
            'totalDepenses',
            'cotisationsParAnnee',
            'depensesRecentes'
        ));
    }
}
