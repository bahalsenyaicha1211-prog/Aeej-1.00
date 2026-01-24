@extends('layouts.public')

@section('title', 'Bureau Exécutif - AEEJ')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/bureau.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('js/bureau.js') }}" defer></script>
@endsection

@section('content')
<main class="contenu-principal">
    <section class="hero-bureau">
        <div class="hero-texte">
            <h1>Bureau Exécutif</h1>
            <p>
                Découvrez l’équipe qui porte la vision de l’AEEJ et coordonne les activités.
            </p>
        </div>
    </section>

    <div class="galerie" id="galerie">
        <ul class="cartes">
            @forelse($bureau as $item)
                @php $m = $item->membre; @endphp
                <li data-role="{{ \Illuminate\Support\Str::slug($item->poste) }}">
                    @if($item->photo)
                        <img loading="lazy" src="{{ asset('storage/' . $item->photo) }}" alt="{{ $item->poste }}">
                    @else
                        <img loading="lazy" src="{{ asset('images/image1.JPG') }}" alt="{{ $item->poste }}">
                    @endif

                    <h3>{{ $item->poste }}</h3>

                    @if($m)
                        <p><strong>{{ $m->prenom }} {{ $m->nom }}</strong></p>
                    @else
                        <p><strong>Matricule : {{ $item->matricule }}</strong></p>
                    @endif

                    <div class="carte-info">
                        <p>Membre du bureau exécutif</p>
                    </div>
                </li>
            @empty
                <li>
                    <div class="carte-info">
                        <p>Aucun membre du bureau n’est encore défini.</p>
                    </div>
                </li>
            @endforelse
        </ul>

        <div class="boutons">
            <button id="precedent" class="bouton-nav" aria-label="Carte précédente">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M15 18l-6-6 6-6"/>
                </svg>
            </button>
            <button id="suivant" class="bouton-nav" aria-label="Carte suivante">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 18l6-6-6-6"/>
                </svg>
            </button>
        </div>
    </div>
</main>
@endsection
