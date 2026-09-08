@extends('layouts.admin')

@section('title', 'Admin • Partenaires')
@section('header', 'Nos partenaires')

@section('content')
<div class="admDash">
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">Partenaires</h1>
            <p class="admDash__sub">Gérez les partenaires affichés sur la page publique « Nos partenaires ».</p>
        </div>
        <a class="btn" style="background: #22c55e; color: #fff; border-radius: 12px; padding: 10px 20px; font-weight: 800; text-decoration: none;" href="{{ route('admin.partenaires.create') }}">
            + Ajouter un partenaire
        </a>
    </div>

    {{-- Catégories --}}
    <div class="admPanel admPanel--full" style="background: rgba(255,255,255,0.02);">
        <div class="admPanel__head"><h2 class="admPanel__h text-white">Catégories</h2></div>
        <div class="admPanel__body">
            @if($errors->any())
                <div style="margin-bottom:14px; padding:10px 14px; border-radius:10px; background:rgba(239,68,68,0.08); border:1px solid rgba(239,68,68,0.25); color:#fb7185; font-size:13px; font-weight:600;">
                    ⚠️ {{ $errors->first() }}
                </div>
            @endif

            <div style="display:flex; flex-wrap:wrap; gap:10px;">
                @foreach($categories as $cat)
                    <div style="display:flex; align-items:center; gap:6px; background:rgba(255,255,255,0.04); border:1px solid rgba(255,255,255,0.12); border-radius:10px; padding:6px 8px;">
                        <form method="POST" action="{{ route('admin.partenaires-categories.update', $cat) }}" style="display:flex; align-items:center; gap:6px;">
                            @csrf @method('PATCH')
                            <input class="input" name="nom" value="{{ $cat->nom }}" required
                                   style="width:150px; padding:6px 8px; font-size:13px; background:rgba(0,0,0,0.25); color:#fff;">
                            <span style="font-size:11px; color:#64748b;">{{ $cat->partners_count }}</span>
                            <button type="submit" class="admQuick__btn" style="padding:6px 10px; font-size:11px;">Renommer</button>
                        </form>
                        <form method="POST" action="{{ route('admin.partenaires-categories.destroy', $cat) }}"
                              onsubmit="return confirm('Supprimer la catégorie « {{ $cat->nom }} » ? {{ $cat->partners_count }} partenaire(s) passeront en « Non classé ».');">
                            @csrf @method('DELETE')
                            <button type="submit" class="admQuick__btn" style="padding:6px 10px; font-size:11px; color:#f87171; border-color:rgba(239,68,68,0.25); display:inline-flex; align-items:center;" aria-label="Supprimer la catégorie"><x-icon name="x" style="width:14px;height:14px"/></button>
                        </form>
                    </div>
                @endforeach
            </div>

            <form method="POST" action="{{ route('admin.partenaires-categories.store') }}" style="margin-top:14px; display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
                @csrf
                <input class="input" name="nom" placeholder="Nouvelle catégorie…" required
                       style="max-width:240px; background:rgba(0,0,0,0.25); color:#fff;">
                <button type="submit" class="btn" style="background:#3b82f6; color:#fff; border:none; border-radius:10px; padding:9px 18px; font-weight:700; cursor:pointer;">Ajouter</button>
            </form>
        </div>
    </div>

    {{-- Filtre --}}
    <form method="GET" style="margin-bottom: 16px; display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
        <input class="input" type="text" name="q" value="{{ $q }}" placeholder="Rechercher un partenaire…" style="max-width:280px; width:100%;">
        <select name="categorie" class="input" style="max-width:220px;">
            <option value="">Toutes les catégories</option>
            <option value="non-classe" @selected($slug === 'non-classe')>Non classé</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->slug }}" @selected($slug === $cat->slug)>{{ $cat->nom }}</option>
            @endforeach
        </select>
        <button class="admQuick__btn" type="submit">Filtrer</button>
        @if($q !== '' || $slug !== '')
            <a class="admQuick__btn" href="{{ route('admin.partenaires.index') }}" style="text-decoration:none;">Réinitialiser</a>
        @endif
    </form>

    @if($partenaires->count() === 0)
        <div class="admPanel admPanel--full" style="text-align:center; padding:50px;">
            <p style="color:#64748b;">{{ $q !== '' || $slug !== '' ? 'Aucun partenaire ne correspond au filtre.' : 'Aucun partenaire pour le moment.' }}</p>
        </div>
    @else
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
            @foreach($partenaires as $p)
                <div class="admPanel" style="grid-column: span 1;">
                    <div style="position:relative; height:150px; background:#fff; display:flex; align-items:center; justify-content:center; padding:18px;">
                        <img src="{{ $p->logo_url }}" alt="{{ $p->nom }}" style="max-width:100%; max-height:100%; object-fit:contain;">
                        <div style="position:absolute; top:10px; left:10px;">
                            <span style="background: {{ $p->is_published ? 'rgba(34,197,94,0.9)' : 'rgba(239,68,68,0.9)' }}; color:#fff; padding:4px 10px; border-radius:20px; font-size:10px; font-weight:900; text-transform:uppercase;">
                                {{ $p->is_published ? 'Publié' : 'Masqué' }}
                            </span>
                        </div>
                    </div>

                    <div class="admPanel__body" style="padding:15px;">
                        <div style="font-weight:800; color:#fff; font-size:14px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $p->nom }}</div>
                        <div style="font-size:11px; color:#4ade80; font-weight:700; text-transform:uppercase; margin:4px 0 14px;">{{ $p->categorie?->nom ?? 'Non classé' }}</div>

                        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:8px;">
                            <form method="POST" action="{{ route('admin.partenaires.toggle', $p) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="admQuick__btn" style="width:100%; font-size:11px;">{{ $p->is_published ? 'Masquer' : 'Publier' }}</button>
                            </form>
                            <a href="{{ route('admin.partenaires.edit', $p) }}" class="admQuick__btn" style="text-decoration:none; text-align:center; font-size:11px; border-color:rgba(59,130,246,0.3); color:#60a5fa;">Modifier</a>
                            <form method="POST" action="{{ route('admin.partenaires.destroy', $p) }}" style="grid-column: span 2;" onsubmit="return confirm('Supprimer « {{ $p->nom }} » ?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="admQuick__btn" style="width:100%; font-size:11px; color:#f87171; border-color:rgba(239,68,68,0.2);">Supprimer</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="custom-pagination">
            {{ $partenaires->links('vendor.pagination.admin') }}
        </div>
    @endif
</div>
@endsection
