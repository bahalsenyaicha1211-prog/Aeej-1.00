@extends('layouts.admin')

@section('title', 'Admin • Activités')
@section('header', 'Gestion des activités')

@section('content')
<div class="card">
    <div class="toolbar">
        <div>
            <div style="font-weight:800; font-size:18px;">Activités</div>
            <div class="help">Créer, modifier et supprimer les activités.</div>
        </div>

        <a class="btn btn--primary" href="{{ route('admin.activites.create') }}">+ Nouvelle activité</a>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Libellé</th>
                    <th>Catégorie</th>
                    <th style="width:220px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($activites as $a)
                    <tr>
                        <td>{{ \Illuminate\Support\Carbon::parse($a->date)->format('d/m/Y') }}</td>
                        <td>{{ $a->libelle }}</td>
                        <td>{{ $a->categorie ?: '—' }}</td>
                        <td>
                            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                                <a class="btn" href="{{ route('admin.activites.edit', $a) }}">Modifier</a>

                                <form method="POST" action="{{ route('admin.activites.destroy', $a) }}"
                                      onsubmit="return confirm('Supprimer cette activité ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn--danger" type="submit">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="color: rgba(229,231,235,0.75); padding:18px;">
                            Aucune activité pour le moment.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:14px;">
        {{ $activites->links() }}
    </div>
</div>
@endsection
