<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BureauMembre;
use App\Models\Membre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BureauMembreController extends Controller
{
    public function index()
    {
        $bureau = BureauMembre::with('membre')
            ->orderByDesc('is_actif')
            ->orderBy('ordre')
            ->paginate(15);

        return view('admin.bureau.index', compact('bureau'));
    }

    public function create()
    {
        $membres = Membre::orderBy('prenom')->orderBy('nom')
            ->get(['matricule','nom','prenom','email']);

        return view('admin.bureau.create', compact('membres'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'matricule' => ['required', 'exists:membres,matricule'],
            'poste'     => ['required', 'string', 'max:120'],
            'ordre'     => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_actif'  => ['nullable', 'boolean'],
            'photo'     => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $data = [
            'matricule' => $validated['matricule'],
            'poste'     => $validated['poste'],
            'ordre'     => $validated['ordre'] ?? 0,
            'is_actif'  => (bool) $request->boolean('is_actif'),
        ];

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('bureau', 'public');
        }

        BureauMembre::create($data);

        return redirect()
            ->route('admin.bureau.index')
            ->with('success', 'Membre du bureau ajouté.');
    }

    public function edit(BureauMembre $bureau)
    {
        $membres = Membre::orderBy('prenom')->orderBy('nom')
            ->get(['matricule','nom','prenom','email']);

        return view('admin.bureau.edit', compact('bureau', 'membres'));
    }

    public function update(Request $request, BureauMembre $bureau)
    {
        $validated = $request->validate([
            'matricule' => ['required', 'exists:membres,matricule'],
            'poste'     => ['required', 'string', 'max:120'],
            'ordre'     => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_actif'  => ['nullable', 'boolean'],
            'photo'     => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_photo' => ['nullable', 'boolean'],
        ]);

        $data = [
            'matricule' => $validated['matricule'],
            'poste'     => $validated['poste'],
            'ordre'     => $validated['ordre'] ?? 0,
            'is_actif'  => (bool) $request->boolean('is_actif'),
        ];

        if ($request->boolean('remove_photo') && $bureau->photo) {
            Storage::disk('public')->delete($bureau->photo);
            $data['photo'] = null;
        }

        if ($request->hasFile('photo')) {
            if ($bureau->photo) {
                Storage::disk('public')->delete($bureau->photo);
            }
            $data['photo'] = $request->file('photo')->store('bureau', 'public');
        }

        $bureau->update($data);

        return redirect()
            ->route('admin.bureau.index')
            ->with('success', 'Membre du bureau mis à jour.');
    }

    public function destroy(BureauMembre $bureau)
    {
        if ($bureau->photo) {
            Storage::disk('public')->delete($bureau->photo);
        }

        $bureau->delete();

        return redirect()
            ->route('admin.bureau.index')
            ->with('success', 'Membre du bureau supprimé.');
    }
}
