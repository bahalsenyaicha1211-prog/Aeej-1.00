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

    public function jendouba()
    {
        // Chaque bloc d'images est piloté par un dossier : il suffit d'y
        // déposer des fichiers (jpg/jpeg/png/webp/avif) pour alimenter le
        // diaporama correspondant. Simple glob local, pas de cache nécessaire.
        $scan = fn (string $dir): array => collect(glob(public_path("images/jendouba/{$dir}/*"), GLOB_BRACE) ?: [])
            ->filter(fn ($path) => in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'webp', 'avif'], true))
            ->unique(fn ($path) => strtolower(basename($path)))
            ->sort()
            ->map(fn ($path) => asset('images/jendouba/' . $dir . '/' . basename($path)))
            ->values()->all();

        $data = (function () use ($scan) {
            $places = [
                ['slug' => 'bulla-regia',  'tag' => 'Site romain',        'titre' => 'Bulla Regia',                 'meta' => '≈ 8 km · nord-est',
                 'texte' => "Unique au monde : les riches Romains y bâtissaient leurs villas <b>sous terre</b> pour fuir la chaleur — cours à colonnades et mosaïques enterrées (maisons de la Chasse, de la Pêche, d'Amphitrite). L'une des cités antiques les mieux préservées d'Afrique du Nord."],
                ['slug' => 'chemtou',      'tag' => 'Carrières antiques',  'titre' => 'Chemtou (Simitthus)',        'meta' => '≈ 20 km · ouest',
                 'texte' => "Les carrières du célèbre <b>marbre jaune numidique</b> (<i>giallo antico</i>), le plus prestigieux de l'Empire romain, exporté jusqu'à Rome. Site archéologique et musée au bord de la Medjerda, avec les vestiges du pont romain et du camp."],
                ['slug' => 'ain-draham',   'tag' => 'Montagne & forêt',    'titre' => 'Aïn Draham & la Kroumirie',  'meta' => '≈ 30 km · nord',
                 'texte' => "Station de montagne à 800 m d'altitude, toits de tuiles rouges, air frais : la « petite Suisse tunisienne ». Forêts de chêne-liège, sentiers de randonnée, panoramas, fraîcheur en été et neige en hiver."],
                ['slug' => 'el-feija',     'tag' => 'Nature protégée',     'titre' => "Parc national d'El Feïja",   'meta' => '≈ 50 km · nord-ouest',
                 'texte' => "2 765 ha de forêt de montagne près de Ghardimaou : subéraie, sources, lacs et une riche biodiversité. C'est ici qu'a été réintroduit le <b>cerf de Berbérie</b>, emblème de la région."],
                ['slug' => 'tabarka',      'tag' => 'Mer',                 'titre' => 'Tabarka',                    'meta' => '≈ 60 km · nord',
                 'texte' => "Station balnéaire du Nord-Ouest : les <b>Aiguilles</b> rocheuses, le fort génois sur son île, les fonds coralliens et le festival de jazz en été. Un contraste saisissant avec la montagne toute proche."],
                ['slug' => 'dougga',       'tag' => 'Patrimoine mondial',  'titre' => 'Dougga',                    'meta' => '≈ 90 km · sud-est',
                 'texte' => "La cité romaine la mieux conservée d'Afrique du Nord, classée à l'UNESCO : capitole, théâtre de 3 500 places, temples et arc de triomphe dominant une plaine d'oliviers. À combiner avec la région du Kef."],
            ];

            foreach ($places as &$p) {
                $p['images'] = $scan('places/' . $p['slug']);
            }
            unset($p);

            return [
                'hero'   => $scan('hero'),
                'nature' => $scan('nature'),
                'vie'    => $scan('vie-etudiante'),
                'places' => $places,
            ];
        })();

        return view('jendouba', $data);
    }

    public function faculte()
    {
        // Photos pilotées par dossier, comme pour /jendouba : déposer un
        // fichier dans le bon dossier suffit à alimenter le diaporama
        // correspondant, sans rien changer au code.
        $scan = fn (string $dir): array => collect(glob(public_path("images/{$dir}/*"), GLOB_BRACE) ?: [])
            ->filter(fn ($path) => is_file($path) && in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'webp', 'avif'], true))
            ->unique(fn ($path) => strtolower(basename($path)))
            ->sort()
            ->map(fn ($path) => asset('images/' . $dir . '/' . basename($path)))
            ->values()->all();

        $universiteImages = $scan('universite/hero');
        $faculteImages    = $scan('faculte/hero');

        $etablissements = [
            ['slug' => 'fsjeg-jendouba',  'nom' => 'Faculté des Sciences Juridiques, Économiques et de Gestion — Jendouba', 'current' => true],
            ['slug' => 'ish-jendouba',    'nom' => 'Institut Supérieur des Sciences Humaines — Jendouba'],
            ['slug' => 'islai-beja',      'nom' => 'Institut Supérieur des Langues Appliquées et de l’Informatique — Béja'],
            ['slug' => 'isbb-beja',       'nom' => 'Institut Supérieur de Biotechnologie — Béja'],
            ['slug' => 'esier-medjez',    'nom' => 'École Supérieure d’Ingénieurs de Medjez el-Bab'],
            ['slug' => 'iseah-kef',       'nom' => 'Institut Supérieur des Études Appliquées en Humanités — Le Kef'],
            ['slug' => 'isi-kef',         'nom' => 'Institut Supérieur de l’Informatique — Le Kef'],
            ['slug' => 'ismt-kef',        'nom' => 'Institut Supérieur de Musique et de Théâtre — Le Kef'],
            ['slug' => 'issi-kef',        'nom' => 'Institut Supérieur des Sciences Infirmières — Le Kef'],
            ['slug' => 'issep-kef',       'nom' => 'Institut Supérieur du Sport et de l’Éducation Physique — Le Kef'],
            ['slug' => 'esa-kef',         'nom' => 'École Supérieure d’Agriculture — Le Kef'],
            ['slug' => 'isp-tabarka',     'nom' => 'Institut Sylvo-Pastoral — Tabarka'],
            ['slug' => 'isam-siliana',    'nom' => 'Institut Supérieur des Arts et Métiers — Siliana'],
        ];
        foreach ($etablissements as &$e) {
            $e['images'] = $scan('universite/etablissements/' . $e['slug']);
        }
        unset($e);

        return view('faculte', compact('universiteImages', 'faculteImages', 'etablissements'));
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
    $category = $request->filled('category') ? $request->string('category')->toString() : null;
    $search   = $request->filled('q') ? trim($request->string('q')->toString()) : null;

    $base = GaleriePhoto::published();
    if ($category) {
        $base->where('category', $category);
    }

    // Concordance activité -> galerie : on cherche le libellé de l'activité
    // dans le titre ou la description des photos. Si ça ne trouve rien
    // (libellé absent des métadonnées), on retombe sur le résultat sans
    // recherche texte (catégorie seule, ou tout) pour ne jamais atterrir
    // sur une page vide.
    $matched = null;
    if ($search) {
        $matched = (clone $base)->where(function ($sub) use ($search) {
            $sub->where('title', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%');
        });
    }

    $matchedCount = ($matched && $search) ? (clone $matched)->count() : 0;
    $query = $matchedCount > 0 ? $matched : $base;
    $searchApplied = $matchedCount > 0 ? $search : null;

    $query->orderByDesc('event_date')->orderByDesc('id');

    $categories = GaleriePhoto::published()
        ->select('category')
        ->distinct()
        ->orderBy('category')
        ->pluck('category');

    $photos = $query->paginate(24)->withQueryString();

    return view('galerie', compact('photos', 'categories', 'search', 'searchApplied'));
}
}