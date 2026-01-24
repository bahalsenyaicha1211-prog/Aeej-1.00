@extends('layouts.admin')

@section('title', 'Admin • Départements')
@section('header', 'Gestion des départements')

@section('content')
<div class="card">
    <div class="toolbar">
        <div>
            <div style="font-weight:800; font-size:18px;">Départements</div>
            <div class="help">Créer, modifier et supprimer les départements.</div>
        </div>
        <a class="btn btn--primary" href="{{ route('admin.departements.create') }}">+ Nouveau</a>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th style="width:220px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($departements as $d)
                    <tr>
                        <td>{{ $d->iddep }}</td>
                        <td>{{ $d->nom }}</td>
                        <td>
                            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                                <a class="btn" href="{{ route('admin.departements.edit', $d) }}">Modifier</a>
                                <form method="POST" action="{{ route('admin.departements.destroy', $d) }}"
                                      onsubmit="return confirm('Supprimer ce département ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn--danger" type="submit">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="color: rgba(229,231,235,0.75); padding:18px;">
                            Aucun département.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:14px;">
        {{ $departements->links() ?? '' }}
    </div>
</div>
@endsection
