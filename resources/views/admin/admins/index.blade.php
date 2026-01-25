@extends('layouts.admin')

@section('title', 'Admin • Admins')
@section('header', 'Gestion des admins')

@section('content')
<div class="page__head">
    <div>
        <h1 class="page__title">Admins</h1>
        <p class="page__subtitle">Créer et gérer les comptes administrateurs.</p>
    </div>

    <div class="page__actions">
        <a class="btn btn--primary" href="{{ route('admin.admins.create') }}">+ Ajouter un admin</a>
    </div>
</div>

<div class="card">
    <div class="card__body">
        <div class="tablewrap">
            <table class="table">
                <thead>
                <tr>
                    <th>Admin</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Créé</th>
                    <th style="width:260px;"></th>
                </tr>
                </thead>
                <tbody>
                @forelse($admins as $a)
                    <tr>
                        <td>
                            <div class="cell__title">{{ $a->name }}</div>
                            <div class="cell__sub">ID: {{ $a->id }}</div>
                        </td>
                        <td class="mono">{{ $a->email }}</td>
                        <td>
                            @if($a->is_super_admin)
                                <span class="mono">SUPER ADMIN</span>
                            @else
                                <span class="mono">ADMIN</span>
                            @endif
                        </td>
                        <td>{{ optional($a->created_at)->format('d/m/Y') }}</td>
                        <td>
                            <div class="actions">
                                <a class="btn" href="{{ route('admin.admins.edit', $a) }}">Modifier</a>

                                {{-- Bouton "secret" : suppression visible UNIQUEMENT au super admin --}}
                                @if(auth()->user()->is_super_admin && !$a->is_super_admin && $a->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.admins.destroy', $a) }}"
                                          onsubmit="return confirm('Supprimer cet admin ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn--danger" type="submit">Supprimer</button>
                                    </form>
                                @endif

                                {{-- Optionnel: toggler super admin (uniquement super admin) --}}
                                @if(auth()->user()->is_super_admin && $a->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.admins.toggleSuper', $a) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn" type="submit">
                                            {{ $a->is_super_admin ? 'Retirer super' : 'Mettre super' }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5"><div class="empty">Aucun admin.</div></td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="pager">
            <div class="pager__meta">
                Affichage {{ $admins->firstItem() ?? 0 }}–{{ $admins->lastItem() ?? 0 }} / {{ $admins->total() }}
            </div>
            <div>
                {{ $admins->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
