<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Membre;
use App\Models\Departement;
use App\Models\Pays;
use App\Models\Annonce;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_membres' => Membre::count(),
            'hommes'        => Membre::where('sexe', 'M')->count(),
            'femmes'        => Membre::where('sexe', 'F')->count(),
            'departements'  => Departement::count(),
            'pays'          => Pays::count(),
            'annonces'      => class_exists(Annonce::class) ? Annonce::count() : 0,
        ];

        // Membres par pays
        $parPays = DB::table('membres')
            ->join('pays', 'membres.idpays', '=', 'pays.idpays')
            ->select('pays.nom as label', DB::raw('COUNT(*) as total'))
            ->groupBy('pays.nom')
            ->orderByDesc('total')
            ->get();

        // Membres par département
        $parDepartement = DB::table('membres')
            ->join('departements', 'membres.iddep', '=', 'departements.iddep')
            ->select('departements.nom as label', DB::raw('COUNT(*) as total'))
            ->groupBy('departements.nom')
            ->orderByDesc('total')
            ->get();

        // Membres par année d’adhésion
        $parAnnee = DB::table('membres')
            ->select('annee_adhesion as label', DB::raw('COUNT(*) as total'))
            ->groupBy('annee_adhesion')
            ->orderByDesc('annee_adhesion')
            ->get();

        // Membres par sexe (utile si demain tu ajoutes autre chose que M/F)
        $parSexe = DB::table('membres')
            ->select('sexe as label', DB::raw('COUNT(*) as total'))
            ->groupBy('sexe')
            ->orderByDesc('total')
            ->get();

        /**
         * “Communauté par pays”
         * IMPORTANT : dans ton modèle actuel, on n’a pas une colonne explicite "communaute".
         * Donc je te propose 2 interprétations possibles :
         *
         * A) Communauté = Pays (même chose que $parPays) -> alors inutile.
         * B) Communauté = (Pays + Département) => combien de membres par département DANS chaque pays.
         *
         * Je mets ici l’option B (plus utile).
         */
        $communauteParPays = DB::table('membres')
            ->join('pays', 'membres.idpays', '=', 'pays.idpays')
            ->join('departements', 'membres.iddep', '=', 'departements.iddep')
            ->select(
                'pays.nom as pays',
                'departements.nom as communaute',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('pays.nom', 'departements.nom')
            ->orderBy('pays.nom')
            ->orderByDesc('total')
            ->get()
            ->groupBy('pays'); // pour l’affichage (sections par pays)

        return view('admin.dashboard', compact(
            'stats',
            'parPays',
            'parDepartement',
            'parAnnee',
            'parSexe',
            'communauteParPays'
        ));
    }
}
