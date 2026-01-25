<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index()
    {
        $admins = User::query()
            ->where('is_admin', true)
            ->orderByDesc('is_super_admin')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.admins.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.admins.create');
    }

    public function store(Request $request)
    {
        // Crée un compte admin
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:users,email'],
            'password' => ['required','string','min:8','confirmed'],
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_admin' => true,
            'is_super_admin' => false,
            'email_verified_at' => now(), // optionnel: tu peux enlever si tu veux qu'ils vérifient
        ]);

        return redirect()->route('admin.admins.index')->with('success', 'Admin ajouté.');
    }

    public function edit(User $admin)
    {
        abort_if(!$admin->is_admin, 404);

        return view('admin.admins.edit', compact('admin'));
    }

    public function update(Request $request, User $admin)
    {
        abort_if(!$admin->is_admin, 404);

        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:users,email,' . $admin->id],
            'password' => ['nullable','string','min:8','confirmed'],
        ]);

        $admin->name = $data['name'];
        $admin->email = $data['email'];

        if (!empty($data['password'])) {
            $admin->password = Hash::make($data['password']);
        }

        $admin->save();

        return redirect()->route('admin.admins.index')->with('success', 'Admin mis à jour.');
    }

    public function destroy(User $admin)
    {
        abort_if(!$admin->is_admin, 404);

        $me = auth()->user();

        // Règles serveur :
        // 1) Personne ne supprime un super admin sauf super admin
        // 2) Même un super admin ne peut pas se supprimer lui-même
        if ($admin->is_super_admin && !$me->is_super_admin) {
            abort(403, 'Action interdite.');
        }
        if ($admin->id === $me->id) {
            abort(403, 'Tu ne peux pas supprimer ton propre compte.');
        }

        $admin->delete();

        return redirect()->route('admin.admins.index')->with('success', 'Admin supprimé.');
    }

    public function toggleSuper(User $admin)
    {
        abort_if(!$admin->is_admin, 404);

        $me = auth()->user();

        // Seul super admin peut changer ce statut (déjà protégé par middleware)
        if ($admin->id === $me->id) {
            return back()->with('error', 'Tu ne peux pas modifier ton propre statut super admin.');
        }

        $admin->is_super_admin = !$admin->is_super_admin;
        $admin->save();

        return back()->with('success', 'Statut super admin mis à jour.');
    }
}
