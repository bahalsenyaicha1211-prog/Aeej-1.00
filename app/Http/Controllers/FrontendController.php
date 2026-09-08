<?php

namespace App\Http\Controllers;

use App\Models\Activite;
use App\Models\Departement;
use App\Models\HeroImage;
use App\Models\Membre;
use App\Models\Pays;
use App\Models\BureauMembre;
use App\Models\GaleriePhoto;
use App\Models\Partner;
use App\Models\PartnerCategory;
use App\Models\ContactMessage;
use App\Rules\MatriculePaysMatch;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;


use Illuminate\Support\Str;

class FrontendController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Pages vitrine
    |--------------------------------------------------------------------------
    */

 public function accueil()
{
    // Une dizaine de count() sur la base distante à chaque visite : mis en cache
    // 10 min, vidé dès qu'une donnée change (App\Support\StatsCache::flush()).
    $stats = Cache::remember('home.stats', now()->addMinutes(10), function () {
        return [
            'membresCount'      => Membre::count(),
            'departementsCount' => Departement::count(),
            'activitesCount'    => Activite::count(),
            'paysCount'         => Pays::count(),
            'bureauCount'       => BureauMembre::count(),
            'inscriptionsRecent' => Membre::whereBetween('created_at', [
                now()->startOfMonth(), now()->endOfMonth(),
            ])->count(),
        ];
    });

    return view('accueil', [
        // Hors cache : léger, et doit refléter l'admin immédiatement.
        'heroImages' => HeroImage::active()->orderBy('position')->orderBy('id')->get(),
    ] + $stats);
}

    public function apropos()
    {
        $pays = Pays::all();
        return view('apropos', compact('pays'));
    }

    public function partenaires(Request $request)
    {
        // Tout le jeu publié est mis en cache une fois (10 min) ; le filtrage
        // et le regroupement se font ensuite en mémoire. Vidé à chaque
        // écriture (voir AppServiceProvider).
        $partenaires = Cache::remember('partners.public', now()->addMinutes(10), function () {
            return Partner::published()->with('categorie')->orderBy('nom')->get();
        });

        $categories = Cache::remember('partners.categories', now()->addMinutes(10), function () {
            return PartnerCategory::orderBy('nom')->get();
        });

        $filtre = trim((string) $request->query('categorie', ''));
        $filtered = $partenaires;
        if ($filtre === 'non-classe') {
            $filtered = $partenaires->whereNull('partner_category_id');
        } elseif ($filtre !== '') {
            $filtered = $partenaires->filter(fn ($p) => $p->categorie?->slug === $filtre);
        }

        // Groupes dans l'ordre des catégories (alpha), « Non classé » en dernier.
        $groupes = collect();
        foreach ($categories as $cat) {
            $items = $filtered->where('partner_category_id', $cat->id)->values();
            if ($items->isNotEmpty()) {
                $groupes[$cat->nom] = $items;
            }
        }
        $nonClasse = $filtered->whereNull('partner_category_id')->values();
        if ($nonClasse->isNotEmpty()) {
            $groupes['Non classé'] = $nonClasse;
        }

        return view('partenaires', [
            'categories' => $categories,
            'groupes'    => $groupes,
            'filtre'     => $filtre,
            'total'      => $partenaires->count(),
        ]);
    }

    public function guideEtudiant()
    {
        return view('guideEtudiant');
    }

    public function activites()
    {
        $activites = Activite::orderByDesc('date')->get();
        return view('activites', compact('activites'));
    }

    public function contact()
    {
        return view('contact');
    }

    public function contactStore(Request $request)
    {
        $validated = $request->validate([
            'nom'       => ['required','string','max:255'],
            'prenom'    => ['required','string','max:255'],
            'email'     => ['required','email','max:255'],
            'telephone' => ['nullable','string','max:20'],
            'message'   => ['required','string','max:2000'],
        ]);

        ContactMessage::create($validated);

        return back()->with('success', 'Message envoyé. Nous vous répondrons bientôt.');
    }


    /*
    |--------------------------------------------------------------------------
    | Inscription membre (public)
    |--------------------------------------------------------------------------
    */

    public function inscription()
    {
        $departements = Departement::orderBy('nom')->get(['iddep', 'nom']);
        $pays         = Pays::orderBy('nom')->get(['idpays', 'nom']);

        return view('inscription', compact('departements', 'pays'));
    }

    /**
     * Crée Membre + User puis envoie un email "Définir mon mot de passe"
     * via le mécanisme reset-password (Breeze).
     */
    public function inscriptionStore(Request $request)
    {
        $validated = $request->validate([
            'matricule'      => ['required', 
                                'string', 'max:50', 
                                'unique:membres,matricule', 
                                'unique:users,matricule', 
                                new MatriculePaysMatch($request->idpays)],
            'idpays'         => ['required', 'exists:pays,idpays'], // pour la règle MatriculePaysMatch

            'nom'            => ['required', 'string', 'max:255'],
            'prenom'         => ['required', 'string', 'max:255'],
            'sexe'           => ['required', 'in:M,F'],

            'iddep'          => ['required', 'exists:departements,iddep'],
       

            'annee_adhesion' => ['required', 'integer', 'min:2010', 'max:' . (date('Y') + 1)],
            'telephone'      => ['nullable', 'string', 'max:20'],
            'email'          => ['required', 'email', 'max:255', 'unique:membres,email', 'unique:users,email'],
            'adresse'        => ['nullable', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($validated) {

            // 1) créer le membre
            $membre = Membre::create([
                'matricule'      => strtoupper($validated['matricule']),
                'nom'            => $validated['nom'],
                'prenom'         => $validated['prenom'],
                'sexe'           => $validated['sexe'],
                'iddep'          => $validated['iddep'],
                'idpays'         => $validated['idpays'],
                'annee_adhesion' => $validated['annee_adhesion'],
                'telephone'      => $validated['telephone'] ?? null,
                'email'          => $validated['email'],
                'adresse'        => $validated['adresse'] ?? null,
            ]);

            // 2) créer le user associé (password aléatoire hashé)
            // Important : password ne doit pas être NULL (migration Breeze)
            $randomPassword = Str::random(32);

            User::create([
                'name'      => $membre->prenom . ' ' . $membre->nom,
                'email'     => $membre->email,
                'matricule' => strtoupper($membre->matricule),
                'role'      => null,
                'is_admin'  => false,
                'password'  => Hash::make($randomPassword),
            ]);
        });

        // 3) envoyer le lien "définir mot de passe"
        // (Breeze fournit forgot/reset-password routes + vues)
        $status = Password::sendResetLink(['email' => $validated['email']]);

        if ($status === Password::RESET_LINK_SENT) {
            return view('auth.messagepourmail', [
                'email' => $validated['email'],
                'nom' => $validated['prenom'].' '. $validated['nom']
            ]);
        }

        // Si l’envoi échoue, le compte existe quand même. Tu affiches un message clair.
        return redirect()->route('accueil')
            ->with('success', 'Inscription réussie, mais l’email n’a pas pu être envoyé. Contactez un administrateur.');
    }

 


   public function galerie(Request $request)
{
    $q = GaleriePhoto::published()
        ->orderByDesc('event_date')
        ->orderByDesc('id');

    $categories = GaleriePhoto::published()
        ->select('category')
        ->distinct()
        ->orderBy('category')
        ->pluck('category');

    if ($request->filled('category')) {
        $q->where('category', $request->string('category'));
    }

    $photos = $q->paginate(24)->withQueryString();

    return view('galerie', compact('photos', 'categories'));
}
}