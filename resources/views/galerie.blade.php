@extends('layouts.public')

@section('title', 'Galerie - AEEJ')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/galerie.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('js/galerie.js') }}" defer></script>
@endsection

@section('content')
<main class="gal-page">
    <section class="gal-hero">
        <div class="gal-hero__inner">
            <h1>Galerie AEEJ</h1>
            <p>Revivez nos événements : formations, fêtes, visites, activités…</p>

            <div class="gal-filters" role="navigation" aria-label="Filtres galerie">
                <a class="chip {{ request('category') ? '' : 'is-active' }}"
                   href="{{ route('galerie') }}">
                    Tout
                </a>

                @foreach($categories as $c)
                    <a class="chip {{ request('category')===$c ? 'is-active' : '' }}"
                       href="{{ route('galerie', ['category' => $c]) }}">
                        {{ $c }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="gal-wrap">
        @if($photos->count() === 0)
            <div class="gal-empty">
                Aucune photo disponible pour le moment.
            </div>
        @else
            <div class="gal-grid" data-gallery-grid>
                @foreach($photos as $p)
                    <article class="gal-card">
                        <button class="gal-card__btn"
                                type="button"
                                data-lightbox-open
                                data-src="{{ $p->image_url }}"
                                data-title="{{ $p->title ?? '' }}"
                                data-meta="{{ $p->category }} • {{ optional($p->event_date)->format('d/m/Y') }}">
                            <img loading="lazy" src="{{ $p->image_url }}" alt="{{ $p->title ?? 'Photo AEEJ' }}">
                            <div class="gal-card__overlay">
                                <div class="gal-card__meta">
                                    <span class="tag">{{ $p->category }}</span>
                                    <span class="date">{{ optional($p->event_date)->format('d/m/Y') }}</span>
                                </div>
                                @if($p->title)
                                    <div class="gal-card__title">{{ $p->title }}</div>
                                @endif
                            </div>
                        </button>
                    </article>
                @endforeach
            </div>

            <div class="gal-pager">
                {{ $photos->links() }}
            </div>
        @endif
    </section>

    {{-- Lightbox --}}
    <div class="lightbox" data-lightbox>
        <div class="lightbox__backdrop" data-lightbox-close></div>
        <div class="lightbox__panel" role="dialog" aria-modal="true" aria-label="Aperçu photo">
            <button class="lightbox__close" type="button" data-lightbox-close aria-label="Fermer">✕</button>
            <img class="lightbox__img" data-lightbox-img alt="">
            <div class="lightbox__info">
                <div class="lightbox__title" data-lightbox-title></div>
                <div class="lightbox__meta" data-lightbox-meta></div>
            </div>
        </div>
    </div>
</main>
@endsection
