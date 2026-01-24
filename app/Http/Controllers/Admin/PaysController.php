<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pays;
use Illuminate\Http\Request;

class PaysController extends Controller
{
    public function index()
    {
        $pays = Pays::orderBy('nom')->paginate(20);
        return view('admin.pays.index', compact('pays'));
    }

    public function create()
    {
        return view('admin.pays.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => ['required','string','max:255','unique:pays,nom'],
        ]);

        Pays::create($data);

        return redirect()->route('admin.pays.index')
            ->with('success', 'Pays ajouté.');
    }

    public function edit(Pays $pays)
    {
        return view('admin.pays.edit', compact('pays'));
    }

    public function update(Request $request, Pays $pays)
    {
        $data = $request->validate([
            'nom' => ['required','string','max:255','unique:pays,nom,' . $pays->idpays . ',idpays'],
        ]);

        $pays->update($data);

        return redirect()->route('admin.pays.index')
            ->with('success', 'Pays mis à jour.');
    }

    public function destroy(Pays $pays)
    {
        $pays->delete();

        return redirect()->route('admin.pays.index')
            ->with('success', 'Pays supprimé.');
    }
}
