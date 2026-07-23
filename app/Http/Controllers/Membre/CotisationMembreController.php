<?php

namespace App\Http\Controllers\Membre;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CotisationMembreController extends Controller
{
    public function index(Request $request)
    {
        $membre = $request->user()->membre;

        $cotisations = $membre
            ? $membre->cotisations()->orderByDesc('annee')->get()
            : collect();

        $anneeActuelle = now()->year;
        $cotisationActuelle = $cotisations->firstWhere('annee', $anneeActuelle);

        return view('membre.cotisations.index', compact('cotisations', 'cotisationActuelle', 'anneeActuelle'));
    }
}
