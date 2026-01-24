<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\BureauMembre;


use App\Http\Controllers\Controller;
use App\Models\Membre;
use App\Models\Departement;
use App\Models\Pays;
use Illuminate\Http\Request;

class MembreController extends Controller
{
    public function index()
    {
        $membres = Membre::with(['departement','pays'])
            ->orderBy('created_at','desc')
            ->paginate(15);

        return view('admin.membres.index', compact('membres'));
    }

    public function show(Membre $membre)
    {
        $membre->load(['departement','pays','user']);
        return view('admin.membres.show', compact('membre'));
    }

    public function edit(Membre $membre)
    {
        $departements = Departement::orderBy('nom')->get();
        $pays = Pays::orderBy('nom')->get();

        return view('admin.membres.edit', compact('membre','departements','pays'));
    }

    public function update(Request $request, Membre $membre)
    {
        $data = $request->validate([
            'nom' => ['required','string','max:255'],
            'prenom' => ['required','string','max:255'],
            'sexe' => ['required','in:M,F'],
            'iddep' => ['required','exists:departements,iddep'],
            'idpays' => ['required','exists:pays,idpays'],
            'annee_adhesion' => ['required','integer','min:2020','max:' . (date('Y') + 1)],
            'telephone' => ['nullable','string','max:20'],
            'email' => ['required','email','max:255','unique:membres,email,' . $membre->matricule . ',matricule'],
            'adresse' => ['nullable','string','max:500'],
        ]);

        $membre->update($data);

        return redirect()->route('admin.membres.show', $membre)
            ->with('success', 'Membre mis à jour.');
    }

   public function destroy(Membre $membre)
{
    DB::transaction(function () use ($membre) {

        // 1) Supprimer du bureau (si le membre y est)
        // + supprimer la photo bureau si tu stockes un chemin
        $bureauItems = \App\Models\BureauMembre::where('matricule', $membre->matricule)->get();
        foreach ($bureauItems as $b) {
            if ($b->photo) {
                Storage::disk('public')->delete($b->photo);
            }
            $b->delete();
        }

        // 2) Supprimer le user lié (si existant) + sa photo de profil
        $user = \App\Models\User::where('matricule', $membre->matricule)->first();
        if ($user) {
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            // Supprimer ses notifications (database channel)
            // notifications table: notifiable_type + notifiable_id
            $user->notifications()->delete();

            // Supprimer les sessions DB si tu utilises SESSION_DRIVER=database
            DB::table('sessions')->where('user_id', $user->id)->delete();

            $user->delete();
        }

        $membre->delete();
    });

    return redirect()->route('admin.membres.index')
        ->with('success', 'Membre supprimé définitivement du système.');
}

}
