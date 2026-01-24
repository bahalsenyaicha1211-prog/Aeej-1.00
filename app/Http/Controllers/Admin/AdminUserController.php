<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
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
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:users,email'],
            'password' => ['required','string','min:8'],
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_admin' => true,
            'is_super_admin' => false, // important : seul toi doit l’être
            'email_verified_at' => now(), // optionnel si tu veux éviter la vérif
        ]);

        return redirect()->route('admin.admins.index')->with('success', 'Admin ajouté.');
    }

    public function edit(User $admin)
    {
        abort_unless($admin->is_admin, 404);

        return view('admin.admins.edit', compact('admin'));
    }

    public function update(Request $request, User $admin)
    {
        abort_unless($admin->is_admin, 404);

        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:users,email,' . $admin->id],
            'password' => ['nullable','string','min:8'],
        ]);

        $admin->name = $data['name'];
        $admin->email = $data['email'];

        if (!empty($data['password'])) {
            $admin->password = Hash::make($data['password']);
        }

        // Sécurité: ne jamais permettre à un admin standard de se rendre super admin
        // Donc on ne propose pas ce champ du tout.

        $admin->save();

        return redirect()->route('admin.admins.index')->with('success', 'Admin mis à jour.');
    }

    public function destroy(User $admin)
    {
        abort_unless($admin->is_admin, 404);

        // Autorisation serveur (super admin only)
        Gate::authorize('delete-admin', $admin);

        $admin->delete();

        return redirect()->route('admin.admins.index')->with('success', 'Admin supprimé.');
    }
}
