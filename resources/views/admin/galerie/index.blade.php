@extends('layouts.admin')

@section('title', 'Admin • Galerie')
@section('header', 'Galerie')

@section('styles')
<style>
/* ================================
   Galerie Admin (UNIQUEMENT page)
   Objectif: vignettes petites + responsive
================================== */

.gal-admin-grid{
  display:grid;
  grid-template-columns: repeat(12, minmax(0, 1fr));
  gap: 12px;
}

/* Card item */
.gal-admin-item{
  grid-column: span 3; /* 4 cartes/ligne */
  border: 1px solid rgba(255,255,255,.12);
  border-radius: 18px;
  background: rgba(255,255,255,.04);
  overflow: hidden;
  box-shadow: 0 12px 28px rgba(0,0,0,.22);
  display:flex;
  flex-direction: column;
}

/* Thumb */
.gal-admin-thumb{
  position: relative;
  background: rgba(0,0,0,.12);
}

.gal-admin-thumb img{
  width:100%;
  height: 160px;          /* -> ici on réduit */
  object-fit: cover;      /* coupe propre */
  display:block;
}

/* Status chip */
.gal-admin-chip{
  position:absolute;
  left: 10px;
  top: 10px;
  padding: 6px 10px;
  border-radius: 999px;
  font-size: 12px;
  font-weight: 900;
  letter-spacing: .02em;
  border: 1px solid rgba(255,255,255,.14);
  backdrop-filter: blur(10px);
}
.gal-admin-chip.is-on{
  background: rgba(34,197,94,.14);
  border-color: rgba(34,197,94,.30);
  color: rgba(187,247,208,.95);
}
.gal-admin-chip.is-off{
  background: rgba(255,255,255,.06);
  border-color: rgba(255,255,255,.16);
  color: rgba(229,231,235,.85);
}

/* Meta zone */
.gal-admin-meta{
  padding: 12px;
  display:flex;
  flex-direction: column;
  gap: 10px;
}

/* Title */
.gal-admin-title{
  font-weight: 950;
  letter-spacing: .2px;
  font-size: 14px;
  line-height: 1.25;
  color: rgba(229,231,235,.95);

  /* coupe propre si titre long */
  overflow:hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

/* Sub */
.gal-admin-sub{
  color: rgba(229,231,235,.70);
  font-size: 12px;
  display:flex;
  gap: 8px;
  flex-wrap: wrap;
}
.gal-admin-sub .sep{opacity:.65}

/* Actions: on rend plus compact que la class .actions globale */
.gal-admin-actions{
  display:flex;
  gap: 8px;
  flex-wrap: wrap;
}

/* Boutons compacts uniquement ici */
.gal-admin-actions .btn{
  padding: 8px 10px;
  border-radius: 12px;
  font-weight: 800;
  font-size: 12px;
}

/* Responsive */
@media (max-width: 1200px){
  .gal-admin-item{grid-column: span 4;} /* 3/ligne */
}
@media (max-width: 900px){
  .gal-admin-item{grid-column: span 6;} /* 2/ligne */
  .gal-admin-thumb img{height: 170px;}
}
@media (max-width: 520px){
  .gal-admin-item{grid-column: span 12;} /* 1/ligne */
  .gal-admin-thumb img{height: 190px;}
}
</style>
@endsection

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

                            <div class="gal-admin-actions">
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
