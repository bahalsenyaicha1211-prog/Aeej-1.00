@extends('layouts.admin')

@section('title', 'Admin • Membres')
@section('header', 'Gestion des membres')

@section('content')
<div class="card">
    <div class="toolbar">
        <div>
            <div style="font-weight:800; font-size:18px;">Membres</div>
            <div class="help">Consulter, modifier et supprimer les membres.</div>
        </div>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Matricule</th>
                    <th>Nom complet</th>
                    <th>Sexe</th>
                    <th>Département</th>
                    <th>Pays</th>
                    <th>Adhésion</th>
                    <th style="width:260px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($membres as $m)
                    <tr>
                        <td>{{ $m->matricule }}</td>
                        <td style="font-weight:700;">{{ $m->prenom }} {{ $m->nom }}</td>
                        <td>{{ $m->sexe }}</td>
                        <td>{{ $m->departement?->nom ?? '—' }}</td>
                        <td>{{ $m->pays?->nom ?? '—' }}</td>
                        <td>{{ $m->annee_adhesion }}</td>
                        <td>
                            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                                <a class="btn" href="{{ route('admin.membres.show', $m) }}">Voir</a>
                                <a class="btn" href="{{ route('admin.membres.edit', $m) }}">Modifier</a>
                                <form method="POST" action="{{ route('admin.membres.destroy', $m) }}"
                                      onsubmit="return confirm('Supprimer définitivement ce membre ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn--danger" type="submit">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="color: rgba(229,231,235,0.75); padding:18px;">
                            Aucun membre enregistré.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:14px;">
        {{ $membres->links() }}
    </div>
</div>
@endsection
