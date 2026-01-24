@extends('layouts.admin')

@section('title', 'Admin • Pays')
@section('header', 'Gestion des pays')

@section('content')
<div class="card">
    <div class="toolbar">
        <div>
            <div style="font-weight:800; font-size:18px;">Pays</div>
            <div class="help">Créer, modifier et supprimer les pays.</div>
        </div>
        <a class="btn btn--primary" href="{{ route('admin.pays.create') }}">+ Nouveau</a>
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
                @forelse($pays as $p)
                    <tr>
                        <td>{{ $p->idpays }}</td>
                        <td>{{ $p->nom }}</td>
                        <td>
                            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                                <a class="btn" href="{{ route('admin.pays.edit', $p) }}">Modifier</a>
                                <form method="POST" action="{{ route('admin.pays.destroy', $p) }}"
                                      onsubmit="return confirm('Supprimer ce pays ?');">
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
                            Aucun pays.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:14px;">
        {{ $pays->links() ?? '' }}
    </div>
</div>
@endsection
