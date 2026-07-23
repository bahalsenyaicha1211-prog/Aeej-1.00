<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TresorerieCompteController extends Controller
{
    public function index()
    {
        $comptes = User::query()
            ->where(function ($q) {
                $q->where('is_tresorier', true)
                  ->orWhere('is_chef_tresorier', true)
                  ->orWhere('is_commissaire_comptes', true);
            })
            ->orderByDesc('is_chef_tresorier')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.tresorerie-comptes.index', compact('comptes'));
    }

    public function create()
    {
        return view('admin.tresorerie-comptes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role_tresorier' => ['required', 'in:tresorier,chef_tresorier,commissaire'],
        ]);

        DB::transaction(function () use ($data) {
            if ($data['role_tresorier'] === 'chef_tresorier') {
                User::where('is_chef_tresorier', true)->update(['is_chef_tresorier' => false, 'is_tresorier' => true]);
            }

            User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'is_tresorier' => in_array($data['role_tresorier'], ['tresorier', 'chef_tresorier']),
                'is_chef_tresorier' => $data['role_tresorier'] === 'chef_tresorier',
                'is_commissaire_comptes' => $data['role_tresorier'] === 'commissaire',
                'email_verified_at' => now(),
            ]);
        });

        return redirect()->route('admin.tresorerie-comptes.index')->with('success', 'Compte trésorerie créé.');
    }

    public function edit(User $tresorerie_compte)
    {
        abort_if(!$tresorerie_compte->is_tresorier && !$tresorerie_compte->is_chef_tresorier && !$tresorerie_compte->is_commissaire_comptes, 404);

        return view('admin.tresorerie-comptes.edit', ['compte' => $tresorerie_compte]);
    }

    public function update(Request $request, User $tresorerie_compte)
    {
        abort_if(!$tresorerie_compte->is_tresorier && !$tresorerie_compte->is_chef_tresorier && !$tresorerie_compte->is_commissaire_comptes, 404);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $tresorerie_compte->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role_tresorier' => ['required', 'in:tresorier,chef_tresorier,commissaire'],
        ]);

        DB::transaction(function () use ($data, $tresorerie_compte) {
            if ($data['role_tresorier'] === 'chef_tresorier') {
                User::where('is_chef_tresorier', true)
                    ->where('id', '!=', $tresorerie_compte->id)
                    ->update(['is_chef_tresorier' => false, 'is_tresorier' => true]);
            }

            $tresorerie_compte->name = $data['name'];
            $tresorerie_compte->email = $data['email'];
            $tresorerie_compte->is_tresorier = in_array($data['role_tresorier'], ['tresorier', 'chef_tresorier']);
            $tresorerie_compte->is_chef_tresorier = $data['role_tresorier'] === 'chef_tresorier';
            $tresorerie_compte->is_commissaire_comptes = $data['role_tresorier'] === 'commissaire';

            if (!empty($data['password'])) {
                $tresorerie_compte->password = Hash::make($data['password']);
            }

            $tresorerie_compte->save();
        });

        return redirect()->route('admin.tresorerie-comptes.index')->with('success', 'Compte mis à jour.');
    }

    public function destroy(User $tresorerie_compte)
    {
        abort_if(!$tresorerie_compte->is_tresorier && !$tresorerie_compte->is_chef_tresorier && !$tresorerie_compte->is_commissaire_comptes, 404);

        $tresorerie_compte->delete();

        return redirect()->route('admin.tresorerie-comptes.index')->with('success', 'Compte supprimé.');
    }
}
