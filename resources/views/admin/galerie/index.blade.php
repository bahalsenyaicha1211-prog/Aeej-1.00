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
            <form method="GET" data-live-search="#galerie-results" action="{{ route('admin.galerie.index') }}" style="display:flex; gap:15px; flex-wrap:wrap; align-items: flex-end;">
                <div style="flex:1 1 200px;">
                    <label class="admKpi__label text-white" style="margin-bottom:8px; display:block;">Rechercher</label>
                    <input class="input" type="text" name="q" value="{{ $q }}" placeholder="Titre ou description..." style="background: rgba(0,0,0,0.3); color:#fff; width:100%;" autocomplete="off">
                </div>
                <div style="flex:1 1 200px;">
                    <label class="admKpi__label text-white" style="margin-bottom:8px; display:block;">Catégorie</label>
                    <select name="category" class="input" style="background: rgba(0,0,0,0.3); color:#fff; width:100%;">
                        <option value="">Toutes les catégories</option>
                        @foreach($categories as $c)
                            <option value="{{ $c }}" @selected(request('category')===$c)>{{ ucfirst($c) }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="flex:1 1 160px;">
                    <label class="admKpi__label text-white" style="margin-bottom:8px; display:block;">Statut</label>
                    <select name="status" class="input" style="background: rgba(0,0,0,0.3); color:#fff; width:100%;">
                        <option value="">Tous</option>
                        <option value="published" @selected(request('status')==='published')>Publié</option>
                        <option value="draft" @selected(request('status')==='draft')>Brouillon</option>
                    </select>
                </div>
                <div style="display:flex; gap:10px; flex:1 1 auto; flex-wrap:wrap;">
                    <button class="btn btn--primary" style="flex:1 1 auto; min-width:120px;" type="submit">Filtrer</button>
                    <a href="{{ route('admin.galerie.index') }}" data-live-search-link="#galerie-results" class="btn btn--ghost" style="flex:1 1 auto; min-width:120px;">Réinitialiser</a>
                </div>
            </form>
        </div>
    </div>

    <div id="galerie-results">
        @include('admin.galerie._results')
    </div>
</div>
@endsection