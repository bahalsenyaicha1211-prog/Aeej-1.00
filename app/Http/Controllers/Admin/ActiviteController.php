<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activite;
use Illuminate\Http\Request;

class ActiviteController extends Controller
{
    public function index()
    {
        $activites = Activite::orderBy('date','desc')->paginate(12);
        return view('admin.activites.index', compact('activites'));
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
