<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PartnerCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PartnerCategoryController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => ['required', 'string', 'max:80', 'unique:partner_categories,nom'],
        ]);

        PartnerCategory::create(['nom' => $data['nom']]);

        return back()->with('success', 'Catégorie ajoutée.');
    }

    public function update(Request $request, PartnerCategory $categorie)
    {
        $data = $request->validate([
            'nom' => ['required', 'string', 'max:80', Rule::unique('partner_categories', 'nom')->ignore($categorie->id)],
        ]);

        $categorie->update(['nom' => $data['nom']]);

        return back()->with('success', 'Catégorie renommée.');
    }

    public function destroy(PartnerCategory $categorie)
    {
        $count = $categorie->partners()->count();
        $categorie->delete(); // les partenaires liés passent à partner_category_id = null

        $msg = $count > 0
            ? "Catégorie supprimée. {$count} partenaire(s) passé(s) en « Non classé »."
            : 'Catégorie supprimée.';

        return back()->with('success', $msg);
    }
}
