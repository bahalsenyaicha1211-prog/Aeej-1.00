<?php

namespace App\Http\Controllers;

use App\Models\Membre;
use App\Models\Annonce;
use Illuminate\Support\Facades\DB;

class TableauController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $membre = $user->membre()->with(['departement', 'pays'])->first();

        $totalMembres = Membre::count();
        $hommes = Membre::where('sexe', 'M')->count();
        $femmes = Membre::where('sexe', 'F')->count();

        $parSexe = [
            'M' => $hommes,
            'F' => $femmes,
        ];

        $annonces = Annonce::where('is_published', true)
            ->orderByDesc('is_pinned')
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        $parPays = DB::table('membres')
            ->join('pays', 'membres.idpays', '=', 'pays.idpays')
            ->select('pays.nom', DB::raw('COUNT(*) as total'))
            ->groupBy('pays.nom')
            ->orderByDesc('total')
            ->get();

        // Badge notifications (table notifications)
        $unreadAnnoncesCount = $user->unreadNotifications()->count();

        return view('dashboard', compact(
            'user',
            'membre',
            'totalMembres',
            'parSexe',
            'annonces',
            'parPays',
            'unreadAnnoncesCount'
        ));
    }
}
