@extends('layouts.admin')

@section('title', 'Admin • Galerie')
@section('header', 'Médiathèque')

@section('content')
<div class="admDash">
    {{-- Header --}}
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">Galerie Photos</h1>
            <p class="admDash__sub">Gérez les souvenirs visuels de l'association.</p>
        </div>
        <a class="btn" style="background: #22c55e; color: #fff; border-radius: 12px; padding: 10px 20px; font-weight: 800; text-decoration: none;" href="{{ route('admin.galerie.create') }}">
            + Ajouter des photos
        </a>
    </div>

    {{-- Filtres --}}
    <div class="admPanel admPanel--full" style="background: rgba(255,255,255,0.02);">
        <div class="admPanel__body">
            <form method="GET" style="display:flex; gap:15px; flex-wrap:wrap; align-items: flex-end;">
                <div style="flex:1; min-width:200px;">
                    <label class="admKpi__label text-white" style="margin-bottom:8px; display:block;">Rechercher</label>
                    <input class="input" type="text" name="q" value="{{ $q }}" placeholder="Titre ou description..." style="background: rgba(0,0,0,0.3); color:#fff; width:100%;">
                </div>
                <div style="flex:1; min-width:200px;">
                    <label class="admKpi__label text-white" style="margin-bottom:8px; display:block;">Catégorie</label>
                    <select name="category" class="input" style="background: rgba(0,0,0,0.3); color:#fff;">
                        <option value="">Toutes les catégories</option>
                        @foreach($categories as $c)
                            <option value="{{ $c }}" @selected(request('category')===$c)>{{ ucfirst($c) }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="width:200px;">
                    <label class="admKpi__label text-white" style="margin-bottom:8px; display:block;">Statut</label>
                    <select name="status" class="input" style="background: rgba(0,0,0,0.3); color:#fff;">
                        <option value="">Tous</option>
                        <option value="published" @selected(request('status')==='published')>Publié</option>
                        <option value="draft" @selected(request('status')==='draft')>Brouillon</option>
                    </select>
                </div>
                <div style="display:flex; gap:10px;">
                    <button class="btn" style="background:#3b82f6; color:#fff; border-radius:10px; padding:10px 20px; font-weight:700; border:none; cursor:pointer;" type="submit">Filtrer</button>
                    <a href="{{ route('admin.galerie.index') }}" class="admQuick__btn" style="text-decoration:none;">Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Grille de photos --}}
    @if($photos->count() === 0)
        <div class="admPanel admPanel--full" style="text-align:center; padding:50px;">
            <p style="color:#64748b;">Aucune photo ne correspond à vos critères.</p>
        </div>
    @else
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
            @foreach($photos as $p)
                <div class="admPanel" style="grid-column: span 1; transition: transform 0.3s ease;">
                    {{-- Thumbnail --}}
                    <div style="position:relative; height:180px; overflow:hidden;">
                        <img src="{{ $p->image_url }}" alt="Photo" style="width:100%; height:100%; object-fit:cover;">
                        <div style="position:absolute; top:10px; left:10px;">
                            <span style="background: {{ $p->is_published ? 'rgba(34,197,94,0.8)' : 'rgba(239,68,68,0.8)' }}; color:#fff; padding:4px 10px; border-radius:20px; font-size:10px; font-weight:900; backdrop-filter:blur(5px); text-transform:uppercase;">
                                {{ $p->is_published ? 'Publié' : 'Privé' }}
                            </span>
                        </div>
                    </div>
                    
                    {{-- Meta --}}
                    <div class="admPanel__body" style="padding:15px;">
                        <div style="font-weight:800; color:#fff; font-size:14px; margin-bottom:5px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                            {{ $p->title ?: 'Sans titre' }}
                        </div>
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
                            <span style="color:#4ade80; font-size:11px; font-weight:700; text-transform:uppercase;">{{ $p->category }}</span>
                            <span style="color:#64748b; font-size:11px;">{{ optional($p->event_date)->format('d/m/Y') }}</span>
                        </div>

                        {{-- Actions --}}
                        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:8px;">
                            <form method="POST" action="{{ route('admin.galerie.toggle', $p) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="admQuick__btn" style="width:100%; font-size:11px;">
                                    {{ $p->is_published ? 'Dépublier' : 'Publier' }}
                                </button>
                            </form>
                            <a href="{{ route('admin.galerie.edit', $p) }}" class="admQuick__btn" style="text-decoration:none; text-align:center; font-size:11px; border-color:rgba(59,130,246,0.3); color:#60a5fa;">Modifier</a>
                            
                            <form method="POST" action="{{ route('admin.galerie.destroy', $p) }}" style="grid-column: span 2;" onsubmit="return confirm('Supprimer définitivement ?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="admQuick__btn" style="width:100%; font-size:11px; color:#f87171; border-color:rgba(239,68,68,0.2);">Supprimer</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
            
    <div class="custom-pagination">
        {{ $photos->links('vendor.pagination.admin') }} 
    </div>
    @endif
</div>
@endsection