<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Departement;
use Illuminate\Http\Request;

class DepartementController extends Controller
{
    public function index()
    {
        $departements = Departement::orderBy('nom')->paginate(15);
        return view('admin.departements.index', compact('departements'));
    }

    public function create()
    {
        return view('admin.departements.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => ['required','string','max:255','unique:departements,nom'],
        ]);

        Departement::create($data);

        return redirect()->route('admin.departements.index')
            ->with('success', 'Département ajouté.');
    }

    public function edit(Departement $departement)
    {
        return view('admin.departements.edit', compact('departement'));
    }

    public function update(Request $request, Departement $departement)
    {
        $data = $request->validate([
            'nom' => ['required','string','max:255','unique:departements,nom,' . $departement->iddep . ',iddep'],
        ]);

        $departement->update($data);

        return redirect()->route('admin.departements.index')
            ->with('success', 'Département mis à jour.');
    }

    public function destroy(Departement $departement)
    {
        $departement->delete();

        return redirect()->route('admin.departements.index')
            ->with('success', 'Département supprimé.');
    }
}

