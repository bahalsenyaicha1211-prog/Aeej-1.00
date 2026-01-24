@extends('layouts.admin')

@section('title', 'Gestion du Bureau')
@section('header', 'Gestion du Bureau')

@section('content')
<div class="toolbar">
    <div></div>
    <a class="btn btn--primary" href="{{ route('admin.bureau.create') }}">Ajouter un membre</a>
</div>

<div class="card">
    <div class="table-wrap">
        <table class="table">
            <thead>
            <tr>
                <th>Photo</th>
                <th>Membre</th>
                <th>Poste</th>
                <th>Ordre</th>
                <th>Actif</th>
                <th style="width:190px;">Actions</th>
            </tr>
            </thead>

            <tbody>
            @forelse($bureau as $b)
                <tr>
                    <td>
                        <img
                            src="{{ $b->photo ? asset('storage/'.$b->photo) : asset('images/image1.JPG') }}"
                            alt="Photo"
                            style="width:46px;height:46px;border-radius:999px;object-fit:cover;border:1px solid rgba(148,163,184,.22);"
                        >
                    </td>
                    <td>
                        <div style="font-weight:700;">
                            {{ $b->membre?->prenom }} {{ $b->membre?->nom }}
                        </div>
                        <div style="font-size:12px;opacity:.75;">
                            Matricule : {{ $b->matricule }}
                        </div>
                    </td>
                    <td>{{ $b->poste }}</td>
                    <td>{{ $b->ordre }}</td>
                    <td>{{ $b->is_actif ? 'Oui' : 'Non' }}</td>
                    <td>
                        <a class="btn btn--ghost" href="{{ route('admin.bureau.edit', $b) }}">Modifier</a>

                        <form method="POST"
                              action="{{ route('admin.bureau.destroy', $b) }}"
                              style="display:inline-block"
                              onsubmit="return confirm('Supprimer ce membre du bureau ?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn--danger" type="submit">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6">Aucun membre du bureau.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:12px;">
        {{ $bureau->links() }}
    </div>
</div>
@endsection
