@extends('layouts.admin')

@section('title', 'Admin • Galerie')
@section('header', 'Galerie')

@section('content')

<div class="page__head">
    <div>
        <h1 class="page__title">Galerie</h1>
        <p class="page__subtitle">Ajoute, publie et organise les photos de l’association.</p>
    </div>
    <div class="page__actions">
        <a class="btn btn--primary" href="{{ route('admin.galerie.create') }}">+ Ajouter des photos</a>
    </div>
</div>

<div class="card" style="margin-bottom:14px;">
    <div class="card__body">
        <form method="GET" class="search" style="display:flex; gap:10px; flex-wrap:wrap;">
            <div style="min-width:220px; flex:1;">
                <label class="label">Catégorie</label>
                <select name="category" class="input">
                    <option value="">Toutes</option>
                    @foreach($categories as $c)
                        <option value="{{ $c }}" @selected(request('category')===$c)>{{ $c }}</option>
                    @endforeach
                </select>
            </div>

            <div style="min-width:220px;">
                <label class="label">Statut</label>
                <select name="status" class="input">
                    <option value="">Tous</option>
                    <option value="published" @selected(request('status')==='published')>Publié</option>
                    <option value="draft" @selected(request('status')==='draft')>Non publié</option>
                </select>
            </div>

            <div style="display:flex; align-items:flex-end; gap:10px;">
                <button class="btn btn--primary" type="submit">Filtrer</button>
                <a class="btn" href="{{ route('admin.galerie.index') }}">Réinitialiser</a>
            </div>
        </form>
    </div>
</div>

@if($photos->count() === 0)
    <div class="card">
        <div class="card__body">
            <div class="empty">Aucune photo pour le moment.</div>
        </div>
    </div>
@else
    <div class="card">
        <div class="card__body">
            <div class="gal-admin-grid">
                @foreach($photos as $p)
                    <div class="gal-admin-item">
                        <div class="gal-admin-thumb">
                            <img src="{{ $p->image_url }}" alt="Photo">
                            <div class="gal-admin-chip {{ $p->is_published ? 'is-on' : 'is-off' }}">
                                {{ $p->is_published ? 'Publié' : 'Non publié' }}
                            </div>
                        </div>

                        <div class="gal-admin-meta">
                            <div class="gal-admin-title">
                                {{ $p->title ?: '—' }}
                            </div>
                            <div class="gal-admin-sub">
                                <span>{{ $p->category }}</span>
                                <span class="sep">•</span>
                                <span>{{ optional($p->event_date)->format('d/m/Y') }}</span>
                            </div>

                            <div class="actions">
                                <form method="POST" action="{{ route('admin.galerie.toggle', $p) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn" type="submit">
                                        {{ $p->is_published ? 'Dépublier' : 'Publier' }}
                                    </button>
                                </form>

                                <a class="btn" href="{{ route('admin.galerie.edit', $p) }}">Modifier</a>

                                <form method="POST" action="{{ route('admin.galerie.destroy', $p) }}"
                                      onsubmit="return confirm('Supprimer définitivement cette photo ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn--danger" type="submit">Supprimer</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pager">
                <div class="pager__meta">
                    Page {{ $photos->currentPage() }} / {{ $photos->lastPage() }}
                    <span class="sep">•</span>
                    Total {{ $photos->total() }}
                </div>
                <div>
                    {{ $photos->links() }}
                </div>
            </div>
        </div>
    </div>
@endif

@endsection
