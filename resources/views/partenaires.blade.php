@extends('layouts.public')

@section('title', 'Nos partenaires - AEEJ')

@section('styles')
    <link rel="stylesheet" href="{{ asset_v('css/partenaires.css') }}?v={{ filemtime(public_path('css/partenaires.css')) }}">
@endsection

@section('scripts')
    <script src="{{ asset_v('js/partenaires.js') }}?v={{ filemtime(public_path('js/partenaires.js')) }}" defer></script>
@endsection

@section('content')
<main class="pt">
    <div class="pt__wrap">

        <section class="pt-hero">
            <span class="pt-kicker"><span class="pt-kicker__dot"></span> Réseau AEEJ</span>
            <h1>Nos partenaires</h1>
            <p>
                Institutions, entreprises, clubs et associations qui accompagnent l'AEEJ
                et soutiennent la réussite des étudiants étrangers à Jendouba.
            </p>
        </section>

        @if($total > 0)
            <nav class="pt-filters" aria-label="Filtrer par catégorie">
                <a class="pt-chip {{ $filtre === '' ? 'is-active' : '' }}" href="{{ route('partenaires') }}">Tout</a>
                @foreach($categories as $cat)
                    <a class="pt-chip {{ $filtre === $cat->slug ? 'is-active' : '' }}"
                       href="{{ route('partenaires', ['categorie' => $cat->slug]) }}">{{ $cat->nom }}</a>
                @endforeach
            </nav>
        @endif

        @forelse($groupes as $nomCategorie => $items)
            <section class="pt-group">
                @if($filtre === '')
                    <h2 class="pt-group__title">{{ $nomCategorie }}</h2>
                @endif

                <div class="pt-grid">
                    @foreach($items as $p)
                        <article class="pt-card" style="--i: {{ $loop->index }}">
                            <div class="pt-card__logo">
                                <img loading="lazy" src="{{ $p->logo_url }}" alt="Logo {{ $p->nom }}">
                            </div>
                            <div class="pt-card__body">
                                <h3 class="pt-card__name">{{ $p->nom }}</h3>
                                <span class="pt-tag">{{ $p->categorie?->nom ?? 'Non classé' }}</span>
                                <p class="pt-card__desc">{{ $p->description }}</p>
                                <button class="pt-card__more" type="button" hidden>Lire la suite</button>
                                @if($p->url)
                                    <a class="pt-card__link" href="{{ $p->url }}" target="_blank" rel="noopener noreferrer">
                                        Visiter le site
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M7 17L17 7M17 7H8M17 7v9"/>
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @empty
            <div class="pt-empty">
                @if($total > 0)
                    Aucun partenaire dans cette catégorie.
                @else
                    Nos partenaires seront bientôt présentés ici.
                @endif
            </div>
        @endforelse

    </div>
</main>
@endsection
