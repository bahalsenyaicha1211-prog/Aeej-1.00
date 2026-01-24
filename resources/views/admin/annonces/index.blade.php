@extends('layouts.admin')

@section('title', 'Admin • Annonces')
@section('header', 'Gestion des annonces')

@section('content')
<div class="card">
    <div class="toolbar">
        <div>
            <div style="font-weight:800; font-size:18px;">Annonces</div>
            <div class="help">Publier, épingler et gérer les annonces.</div>
        </div>
        <a class="btn btn--primary" href="{{ route('admin.annonces.create') }}">+ Nouvelle annonce</a>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Statut</th>
                    <th>Épinglée</th>
                    <th>Publié le</th>
                    <th>Extrait</th>
                    <th style="width:220px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($annonces as $a)
                    <tr>
                        <td>{{ $a->is_published ? 'Publiée' : 'Brouillon' }}</td>
                        <td>{{ $a->is_pinned ? 'Oui' : 'Non' }}</td>
                        <td>{{ $a->published_at ? $a->published_at->format('d/m/Y H:i') : '—' }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($a->contenu, 70) }}</td>
                        <td>
                            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                                <a class="btn" href="{{ route('admin.annonces.edit', $a) }}">Modifier</a>

                                <form method="POST" action="{{ route('admin.annonces.destroy', $a) }}"
                                      onsubmit="return confirm('Supprimer cette annonce ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn--danger" type="submit">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="color: rgba(229,231,235,0.75); padding:18px;">
                            Aucune annonce.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:14px;">
        {{ $annonces->links() }}
    </div>
</div>
@endsection
