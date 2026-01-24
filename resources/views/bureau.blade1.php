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
                <h1>Bureau Exécutif {{ $mandatActif ? $mandatActif->libelle : '' }}</h1>
                <p>
                    Découvrez l'équipe qui porte la vision de l'AEEJ, coordonne les activités,
                    accompagne les étudiants et représente fièrement l'association.
                </p>
            </div>
        </section>

        <div class="galerie" id="galerie">
            <ul class="cartes">
                @if($mandatActif && $affectationsParPoste->count() > 0)
                    @foreach($affectationsParPoste as $codePoste => $groupe)
                        @php
                            $poste = $groupe['poste'];
                            $affectations = $groupe['affectations'];
                            $isMulti = $groupe['is_multi'];
                        @endphp
                        
                        {{-- Postes uniques : afficher une carte avec le membre (ou "Non attribué") --}}
                        @if(!$isMulti)
                            @php
                                $affectation = $affectations->first();
                                $membre = $affectation ? $affectation->membre : null;
                            @endphp
                            <li data-role="{{ $poste->code }}">
                                @if($membre && $membre->photo)
                                    <img loading="lazy" src="{{ asset('storage/' . $membre->photo) }}" alt="{{ $poste->libelle }}">
                                @else
                                    <img loading="lazy" src="{{ asset('images/image1.JPG') }}" alt="{{ $poste->libelle }}">
                                @endif
                                <h3>{{ $poste->libelle }}</h3>
                                @if($membre)
                                    <p><strong>{{ $membre->prenom }} {{ $membre->nom }}</strong></p>
                                @else
                                    <p><strong>Non attribué</strong></p>
                                @endif
                                <div class="carte-info">
                                    <p>Membre du bureau exécutif</p>
                                </div>
                            </li>
                        @else
                            {{-- Postes multiples : afficher une carte par membre --}}
                            @foreach($affectations as $affectation)
                                @php $membre = $affectation->membre; @endphp
                                <li data-role="{{ $poste->code }}">
                                    @if($membre && $membre->photo)
                                        <img loading="lazy" src="{{ asset('storage/' . $membre->photo) }}" alt="{{ $poste->libelle }}">
                                    @else
                                        <img loading="lazy" src="{{ asset('images/image1.JPG') }}" alt="{{ $poste->libelle }}">
                                    @endif
                                    <h3>{{ $poste->libelle }}</h3>
                                    @if($membre)
                                        <p><strong>{{ $membre->prenom }} {{ $membre->nom }}</strong></p>
                                    @endif
                                    <div class="carte-info">
                                        <p>Membre du bureau exécutif</p>
                                    </div>
                                </li>
                            @endforeach
                        @endif
                    @endforeach
                @else
                    {{-- Aucun mandat actif ou aucune affectation --}}
                    <li>
                        <div class="carte-info">
                            <p>Aucun mandat actif ou aucun membre du bureau pour le moment.</p>
                        </div>
                    </li>
                @endif
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

