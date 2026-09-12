<?php

namespace App\Http\Controllers\Membre;

use App\Http\Controllers\Controller;
use App\Support\AcademicYear;
use Illuminate\Http\Request;

class CotisationMembreController extends Controller
{
    public function index(Request $request)
    {
        $membre = $request->user()->membre;

        $cotisations = $membre
            ? $membre->cotisations()->orderByDesc('annee')->get()
            : collect();

        $anneeActuelle = AcademicYear::anneeActive();
        $cotisationActuelle = $cotisations->firstWhere('annee', $anneeActuelle);

        $cotisationsVolontaires = $membre
            ? $membre->cotisationsVolontaires()->with('type')->orderByDesc('date_paiement')->get()
            : collect();

        return view('membre.cotisations.index', compact('cotisations', 'cotisationActuelle', 'anneeActuelle', 'cotisationsVolontaires'));
    }
}
