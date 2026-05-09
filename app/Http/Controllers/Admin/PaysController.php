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
            'nom'       => ['required', 'string', 'max:255', 'unique:pays,nom'],
            // Validation de la signature : 2 lettres exactes et unique
            'signature' => ['required', 'string', 'size:2', 'unique:pays,signature'],
        ]);

        // On force la signature en majuscules avant de créer
        $data['signature'] = strtoupper($data['signature']);

        Pays::create($data);

        return redirect()->route('admin.pays.index')
            ->with('success', 'Pays ajouté avec sa signature de matricule.');
    }

    public function edit(Pays $pays)
    {
        return view('admin.pays.edit', compact('pays'));
    }

    public function update(Request $request, Pays $pays)
    {
        $data = $request->validate([
            // On ignore l'ID actuel pour la règle unique lors de la modification
            'nom'       => ['required', 'string', 'max:255', 'unique:pays,nom,' . $pays->idpays . ',idpays'],
            'signature' => ['required', 'string', 'size:2', 'unique:pays,signature,' . $pays->idpays . ',idpays'],
        ]);

        // On force la signature en majuscules avant la mise à jour
        $data['signature'] = strtoupper($data['signature']);

        $pays->update($data);

        return redirect()->route('admin.pays.index')
            ->with('success', 'Pays et signature mis à jour.');
    }

    public function destroy(Pays $pays)
    {
        $pays->delete();

        return redirect()->route('admin.pays.index')
            ->with('success', 'Pays supprimé.');
    }
}