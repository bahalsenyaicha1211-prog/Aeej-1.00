<?php

namespace App\Http\Controllers\Tresorerie;

use App\Http\Controllers\Controller;
use App\Models\Cotisation;
use App\Models\Depense;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $mesCotisationsCount = null;
        $caisse = null;

        if ($user->isTresorier() && !$user->isChefTresorier()) {
            $mesCotisationsCount = Cotisation::where('created_by', $user->id)
                ->where('annee', now()->year)
                ->count();
        }

        if ($user->isChefTresorier() || $user->isCommissaireComptes()) {
            $totalCotisations = Cotisation::sum('montant_paye');
            $totalDepenses = Depense::sum('montant_total');
            $caisse = $totalCotisations - $totalDepenses;
        }

        return view('tresorerie.dashboard', compact('mesCotisationsCount', 'caisse'));
    }
}
