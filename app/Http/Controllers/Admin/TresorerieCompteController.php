<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BureauMembre;
use App\Models\Membre;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TresorerieCompteController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $comptes = User::query()
            ->with('membre')
            ->where(function ($sub) {
                $sub->where('is_tresorier', true)
                  ->orWhere('is_chef_tresorier', true)
                  ->orWhere('is_commissaire_comptes', true);
            })
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('is_chef_tresorier')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.tresorerie-comptes.index', compact('comptes', 'q'));
    }

    public function create()
    {
        $dejaAssignes = User::query()
            ->where('is_tresorier', true)
            ->orWhere('is_chef_tresorier', true)
            ->orWhere('is_commissaire_comptes', true)
            ->pluck('matricule')
            ->filter()
            ->all();

        $tresoriersDisponibles = BureauMembre::tresoriers()
            ->with('membre')
            ->whereNotIn('matricule', $dejaAssignes)
            ->get()
            ->filter(fn ($b) => $b->membre !== null);

        $membresDisponibles = Membre::whereNotIn('matricule', $dejaAssignes)
            ->orderBy('prenom')->orderBy('nom')
            ->get();

        return view('admin.tresorerie-comptes.create', compact('tresoriersDisponibles', 'membresDisponibles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'role_tresorier' => ['required', 'in:tresorier,chef_tresorier,commissaire'],
            'matricule' => ['required', 'exists:membres,matricule'],
        ]);

        if (in_array($data['role_tresorier'], ['tresorier', 'chef_tresorier'])) {
            $estTresorierBureau = BureauMembre::tresoriers()->where('matricule', $data['matricule'])->exists();
            if (!$estTresorierBureau) {
                return back()->withInput()->withErrors([
                    'matricule' => "Ce membre n'a pas le poste Trésorier/Trésorière dans le bureau.",
                ]);
            }
        }

        $membre = Membre::findOrFail($data['matricule']);
        $user = $membre->user;

        if (!$user) {
            return back()->withInput()->withErrors([
                'matricule' => "Ce membre n'a pas encore de compte utilisateur associé.",
            ]);
        }

        DB::transaction(function () use ($data, $user) {
            if ($data['role_tresorier'] === 'chef_tresorier') {
                User::where('is_chef_tresorier', true)->update(['is_chef_tresorier' => false, 'is_tresorier' => true]);
            }

            $user->is_tresorier = in_array($data['role_tresorier'], ['tresorier', 'chef_tresorier']);
            $user->is_chef_tresorier = $data['role_tresorier'] === 'chef_tresorier';
            $user->is_commissaire_comptes = $data['role_tresorier'] === 'commissaire';
            $user->save();
        });

        return redirect()->route('admin.tresorerie-comptes.index')->with('success', 'Rôle attribué à ce membre.');
    }

    public function edit(User $tresorerie_compte)
    {
        abort_if(!$tresorerie_compte->is_tresorier && !$tresorerie_compte->is_chef_tresorier && !$tresorerie_compte->is_commissaire_comptes, 404);

        return view('admin.tresorerie-comptes.edit', ['compte' => $tresorerie_compte->load('membre')]);
    }

    public function update(Request $request, User $tresorerie_compte)
    {
        abort_if(!$tresorerie_compte->is_tresorier && !$tresorerie_compte->is_chef_tresorier && !$tresorerie_compte->is_commissaire_comptes, 404);

        $data = $request->validate([
            'role_tresorier' => ['required', 'in:tresorier,chef_tresorier,commissaire'],
        ]);

        if (in_array($data['role_tresorier'], ['tresorier', 'chef_tresorier']) && $tresorerie_compte->matricule) {
            $estTresorierBureau = BureauMembre::tresoriers()->where('matricule', $tresorerie_compte->matricule)->exists();
            if (!$estTresorierBureau) {
                return back()->withErrors([
                    'role_tresorier' => "Ce membre n'a pas le poste Trésorier/Trésorière dans le bureau, il ne peut pas être trésorier ou chef trésorier.",
                ]);
            }
        }

        DB::transaction(function () use ($data, $tresorerie_compte) {
            if ($data['role_tresorier'] === 'chef_tresorier') {
                User::where('is_chef_tresorier', true)
                    ->where('id', '!=', $tresorerie_compte->id)
                    ->update(['is_chef_tresorier' => false, 'is_tresorier' => true]);
            }

            $tresorerie_compte->is_tresorier = in_array($data['role_tresorier'], ['tresorier', 'chef_tresorier']);
            $tresorerie_compte->is_chef_tresorier = $data['role_tresorier'] === 'chef_tresorier';
            $tresorerie_compte->is_commissaire_comptes = $data['role_tresorier'] === 'commissaire';
            $tresorerie_compte->save();
        });

        return redirect()->route('admin.tresorerie-comptes.index')->with('success', 'Rôle mis à jour.');
    }

    public function destroy(User $tresorerie_compte)
    {
        abort_if(!$tresorerie_compte->is_tresorier && !$tresorerie_compte->is_chef_tresorier && !$tresorerie_compte->is_commissaire_comptes, 404);

        // On ne supprime jamais le compte : c'est celui d'un membre de l'association.
        // On retire uniquement les droits trésorerie.
        $tresorerie_compte->is_tresorier = false;
        $tresorerie_compte->is_chef_tresorier = false;
        $tresorerie_compte->is_commissaire_comptes = false;
        $tresorerie_compte->save();

        return redirect()->route('admin.tresorerie-comptes.index')->with('success', 'Rôle retiré. Le compte membre reste actif.');
    }
}
