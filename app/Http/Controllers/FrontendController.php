<?php

namespace App\Http\Controllers;

use App\Models\Activite;
use App\Models\Departement;
use App\Models\Membre;
use App\Models\Pays;
use App\Models\BureauMembre;
use App\Models\GaleriePhoto;
use Illuminate\Http\Request;
use App\Models\User;
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
    $startMonth = now()->startOfMonth();
    $endMonth   = now()->endOfMonth();

    return view('accueil', [
        'membresCount'      => Membre::count(),
        'departementsCount' => Departement::count(),
        'activitesCount'    => Activite::count(),
        'paysCount'         => Pays::count(),
        'bureauCount'       => BureauMembre::count(),

        // ✅ inscriptions du mois (suppose que "created_at" existe sur membres)
        'inscriptionsRecent' => Membre::whereBetween('created_at', [$startMonth, $endMonth])->count(),
    ]);
}

    public function apropos()
    {
        return view('apropos');
    }

    public function guideEtudiant()
    {
        return view('guideEtudiant');
    }

    public function bureau()
    {
        // Version simple v1 : page statique
        // Plus tard tu mettras la logique bureau_affectations / mandat.
        return view('bureau');
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

        // V1 simple : on ne stocke pas, on confirme juste.
        // Plus tard : Mail::to(...) ou table contact_messages.
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
            'matricule'      => ['required', 'string', 'max:50', 'unique:membres,matricule', 'unique:users,matricule'],
            'nom'            => ['required', 'string', 'max:255'],
            'prenom'         => ['required', 'string', 'max:255'],
            'sexe'           => ['required', 'in:M,F'],

            'iddep'          => ['required', 'exists:departements,iddep'],
            'idpays'         => ['required', 'exists:pays,idpays'],

            'annee_adhesion' => ['required', 'integer', 'min:2020', 'max:' . (date('Y') + 1)],
            'telephone'      => ['nullable', 'string', 'max:20'],
            'email'          => ['required', 'email', 'max:255', 'unique:membres,email', 'unique:users,email'],
            'adresse'        => ['nullable', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($validated) {

            // 1) créer le membre
            $membre = Membre::create([
                'matricule'      => $validated['matricule'],
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
                'matricule' => $membre->matricule,
                'role'      => null,
                'is_admin'  => false,
                'password'  => Hash::make($randomPassword),
            ]);
        });

        // 3) envoyer le lien "définir mot de passe"
        // (Breeze fournit forgot/reset-password routes + vues)
        $status = Password::sendResetLink(['email' => $validated['email']]);

        if ($status === Password::RESET_LINK_SENT) {
            return redirect()->route('accueil')
                ->with('success', 'Inscription réussie. Un email vous a été envoyé pour définir votre mot de passe.');
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