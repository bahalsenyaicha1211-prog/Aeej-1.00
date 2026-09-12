<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\HandlesImageUpload;
use App\Http\Controllers\Controller;
use App\Models\ContactPerson;
use Illuminate\Http\Request;

class ContactPersonController extends Controller
{
    use HandlesImageUpload;

    public function index()
    {
        $personnes = ContactPerson::orderBy('position')->orderBy('nom')->get();

        return view('admin.contacts.index', compact('personnes'));
    }

    public function create()
    {
        return view('admin.contacts.create');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $photo = $this->resolveImageUrl($request, 'photo', 'contacts', required: true);

        ContactPerson::create($data + [
            'photo' => $photo,
            'is_highlighted' => $request->boolean('is_highlighted'),
            'is_published' => $request->boolean('is_published'),
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.contacts.index')->with('success', 'Personne ajoutée aux contacts.');
    }

    public function edit(ContactPerson $contact)
    {
        return view('admin.contacts.edit', ['personne' => $contact]);
    }

    public function update(Request $request, ContactPerson $contact)
    {
        $data = $this->validated($request);

        $payload = $data + [
            'is_highlighted' => $request->boolean('is_highlighted'),
            'is_published' => $request->boolean('is_published'),
        ];

        if ($photo = $this->resolveImageUrl($request, 'photo', 'contacts', required: false)) {
            $payload['photo'] = $photo;
        }

        $contact->update($payload);

        return redirect()->route('admin.contacts.index')->with('success', 'Contact mis à jour.');
    }

    public function destroy(ContactPerson $contact)
    {
        // La photo reste sur Cloudinary (convention du projet : aucune suppression).
        $contact->delete();

        return back()->with('success', 'Contact supprimé.');
    }

    public function toggle(ContactPerson $contact)
    {
        $contact->update(['is_published' => ! $contact->is_published]);

        return back()->with('success', 'Visibilité mise à jour.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'nom' => ['required', 'string', 'max:150'],
            'poste' => ['required', 'string', 'max:150'],
            'telephone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'position' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['position'] = (int) ($data['position'] ?? 0);

        return $data;
    }
}
