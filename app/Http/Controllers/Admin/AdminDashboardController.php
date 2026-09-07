<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\Membre;
use App\Models\Departement;
use App\Models\Pays;
use App\Models\Annonce;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // ~10 requêtes d'agrégat sur la base distante : mises en cache 5 min,
        // vidées dès qu'une donnée change (App\Support\StatsCache::flush()).
        $data = Cache::remember('admin.dashboard', now()->addMinutes(5), fn () => $this->compute());

        return view('admin.dashboard', $data);
    }

    private function compute(): array
    {
        // 1. Stats Globales (KPI)
        $stats = [
            'total_membres' => Membre::count(),
            'hommes'        => Membre::where('sexe', 'M')->count(),
            'femmes'        => Membre::where('sexe', 'F')->count(),
            'departements'  => Departement::count(),
            'pays'          => Pays::count(),
            'annonces'      => class_exists(Annonce::class) ? Annonce::count() : 0,
        ];

        // 2. Membres par pays
        $parPays = DB::table('membres')
            ->join('pays', 'membres.idpays', '=', 'pays.idpays')
            ->select('pays.nom as label', DB::raw('COUNT(*) as total'))
            ->groupBy('pays.nom')
            ->orderByDesc('total')
            ->get();

        // 3. Membres par département
        $parDepartement = DB::table('membres')
            ->join('departements', 'membres.iddep', '=', 'departements.iddep')
            ->select('departements.nom as label', DB::raw('COUNT(*) as total'))
            ->groupBy('departements.nom')
            ->orderByDesc('total')
            ->get();

        // 4. Membres par année
        $parAnnee = DB::table('membres')
            ->select('annee_adhesion as label', DB::raw('COUNT(*) as total'))
            ->groupBy('annee_adhesion')
            ->orderByDesc('annee_adhesion')
            ->get();

        // 5. RÉPARTITION SEXE PAR PAYS (Correction des noms de colonnes)
        $sexeParPays = DB::table('membres')
            ->join('pays', 'membres.idpays', '=', 'pays.idpays')
            ->select('pays.nom as pays_nom', 'membres.sexe', DB::raw('count(*) as total'))
            ->groupBy('pays.nom', 'membres.sexe')
            ->get() 
            ->groupBy('pays_nom');

        // 6. Communautés par pays (Pays + Département)
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
            ->groupBy('pays');

        // 7. Données prêtes pour la vue
        return compact(
            'stats',
            'parPays',
            'parDepartement',
            'parAnnee',
            'sexeParPays', // Correction ici (doit correspondre au nom dans la vue)
            'communauteParPays'
        );
    }
}