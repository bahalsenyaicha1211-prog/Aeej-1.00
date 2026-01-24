@extends('layouts.admin')

@section('title', 'Admin • Bureau')
@section('header', 'Gestion du bureau')

@section('content')
<div class="card">
    <div class="toolbar">
        <div>
            <div style="font-weight:800; font-size:18px;">Bureau</div>
            <div class="help">Activer/désactiver, ordonner et mettre les photos.</div>
        </div>
        <a class="btn btn--primary" href="{{ route('admin.bureau.create') }}">+ Ajouter</a>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Actif</th>
                    <th>Ordre</th>
                    <th>Poste</th>
                    <th>Membre</th>
                    <th>Matricule</th>
                    <th style="width:220px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bureau as $b)
                    <tr>
                        <td>{{ $b->is_actif ? 'Oui' : 'Non' }}</td>
                        <td>{{ $b->ordre }}</td>
                        <td>{{ $b->poste }}</td>
                        <td>
                            @if($b->membre)
                                {{ $b->membre->prenom }} {{ $b->membre->nom }}
                            @else
                                —
                            @endif
                        </td>
                        <td>{{ $b->matricule }}</td>
                        <td>
                            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                                <a class="btn" href="{{ route('admin.bureau.edit', $b) }}">Modifier</a>
                                <form method="POST" action="{{ route('admin.bureau.destroy', $b) }}"
                                      onsubmit="return confirm('Supprimer ce membre du bureau ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn--danger" type="submit">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="color: rgba(229,231,235,0.75); padding:18px;">
                            Aucun membre du bureau enregistré.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
