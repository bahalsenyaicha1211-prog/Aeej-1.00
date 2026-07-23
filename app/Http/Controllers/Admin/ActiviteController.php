<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activite;
use Illuminate\Http\Request;

class ActiviteController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $activites = Activite::when($q !== '', function ($query) use ($q) {
                $query->where('libelle', 'like', "%{$q}%")
                      ->orWhere('categorie', 'like', "%{$q}%");
            })
            ->orderBy('date','desc')
            ->paginate(12)
            ->withQueryString();

        return view('admin.activites.index', compact('activites', 'q'));
    }

    public function create()
    {
        return view('admin.activites.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'libelle' => ['required','string','max:255'],
            'categorie' => ['nullable','string','max:255'],
            'date' => ['required','date'],
        ]);

        Activite::create($data);

        return redirect()->route('admin.activites.index')
            ->with('success', 'Activité ajoutée.');
    }

    public function edit(Activite $activite)
    {
        return view('admin.activites.edit', compact('activite'));
    }

    public function update(Request $request, Activite $activite)
    {
        $data = $request->validate([
            'libelle' => ['required','string','max:255'],
            'categorie' => ['nullable','string','max:255'],
            'date' => ['required','date'],
        ]);

        $activite->update($data);

        return redirect()->route('admin.activites.index')
            ->with('success', 'Activité mise à jour.');
    }

    public function destroy(Activite $activite)
    {
        $activite->delete();

        return redirect()->route('admin.activites.index')
            ->with('success', 'Activité supprimée.');
    }
}
