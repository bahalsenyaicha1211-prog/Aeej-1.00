@extends('layouts.public')

@section('title', 'Guide Étudiant - AEEJ')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/guide.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('js/guideEtudiant.js') }}" defer></script>
@endsection

@section('content')
<main class="guide-page">

    {{-- HERO --}}
    <section class="guide-hero">
        <div class="container">
            <div class="hero-box">
                <span class="badge">Guide Étudiant AEEJ</span>
                <h1>Tout pour réussir votre arrivée et votre installation en Tunisie</h1>
                <p>
                    L’AEEJ vous accompagne étape par étape : intégration, séjour, logement et gestion du budget.
                    Choisissez un thème et accédez directement aux informations essentielles.
                </p>

                <div class="hero-actions">
                    <button class="chip is-active" type="button" data-target="integration">
                        <i class="fa-solid fa-seedling"></i> Intégration
                    </button>
                    <button class="chip" type="button" data-target="sejour">
                        <i class="fa-solid fa-passport"></i> Séjour
                    </button>
                    <button class="chip" type="button" data-target="logement">
                        <i class="fa-solid fa-house"></i> Logement
                    </button>
                    <button class="chip" type="button" data-target="finance">
                        <i class="fa-solid fa-coins"></i> Finances
                    </button>
                </div>
            </div>
        </div>
    </section>

    

    {{-- CONTENU --}}
    <section id="guide-content" class="guide-content">
        <div class="container">
            <div class="content-layout">
                {{-- MENU --}}
                <aside class="side">
                    <div class="side-title">Sections</div>

                    <nav class="side-nav">
                        <button class="side-link is-active" type="button" data-target="integration">
                            <i class="fa-solid fa-seedling"></i> Intégration
                        </button>
                        <button class="side-link" type="button" data-target="sejour">
                            <i class="fa-solid fa-passport"></i> Séjour
                        </button>
                        <button class="side-link" type="button" data-target="logement">
                            <i class="fa-solid fa-house"></i> Logement
                        </button>
                        <button class="side-link" type="button" data-target="finance">
                            <i class="fa-solid fa-coins"></i> Finances
                        </button>
                    </nav>

                    <div class="side-note">
                        Cliquez sur une section pour afficher le contenu.
                    </div>
                </aside>

                {{-- PANELS --}}
                <div class="panels">
                    <article id="integration" class="panel is-active" data-panel>
                        <h2>Intégration</h2>
                        <p>Premiers repères et bons réflexes dès l’arrivée.</p>

                        <div class="panel-grid">
                            <div class="box">
                                <h3><i class="fa-solid fa-sim-card"></i> Dès l’arrivée</h3>
                                <ul>
                                    <li>Prendre une <strong>carte SIM</strong> locale (TT, Ooredoo, Orange).</li>
                                    <li>Rejoindre les <strong>groupes WhatsApp/Facebook</strong> utiles.</li>
                                    <li>Identifier <strong>services proches</strong> (hôpital, police, transport).</li>
                                </ul>
                            </div>

                            <div class="box">
                                <h3><i class="fa-solid fa-people-group"></i> Réseau & entraide</h3>
                                <ul>
                                    <li>Participer aux activités AEEJ.</li>
                                    <li>Demander un “ancien” pour orientation rapide.</li>
                                    <li>Noter les contacts importants.</li>
                                </ul>
                            </div>
                        </div>
                    </article>

                    <article id="sejour" class="panel" data-panel>
                        <h2>Carte de séjour</h2>
                        <p>Documents, étapes et erreurs à éviter.</p>

                        <div class="panel-grid">
                            <div class="box">
                                <h3><i class="fa-solid fa-circle-check"></i> Pourquoi c’est important</h3>
                                <p>
                                    Elle facilite la régularisation et plusieurs démarches administratives.
                                </p>
                            </div>

                            <div class="box">
                                <h3><i class="fa-solid fa-folder-open"></i> Dossier type</h3>
                                <ul>
                                    <li>Pièce d’identité + copies</li>
                                    <li>Attestation d’inscription</li>
                                    <li>Photos d’identité</li>
                                    <li>Justificatif de logement (selon cas)</li>
                                </ul>
                            </div>
                        </div>
                    </article>

                    <article id="logement" class="panel" data-panel>
                        <h2>Logement</h2>
                        <p>Où chercher et quoi vérifier avant de payer.</p>

                        <div class="panel-grid">
                            <div class="box">
                                <h3><i class="fa-solid fa-magnifying-glass"></i> Où chercher</h3>
                                <ul>
                                    <li>Groupes Facebook / WhatsApp</li>
                                    <li>Bouche-à-oreille</li>
                                    <li>Agences (si nécessaire)</li>
                                </ul>
                            </div>

                            <div class="box">
                                <h3><i class="fa-solid fa-key"></i> Avant de signer</h3>
                                <ul>
                                    <li>Visiter et vérifier eau/électricité</li>
                                    <li>Clarifier charges incluses</li>
                                    <li>Garder preuve de paiement</li>
                                </ul>
                            </div>
                        </div>
                    </article>

                    <article id="finance" class="panel" data-panel>
                        <h2>Gestion financière</h2>
                        <p>Budget mensuel et habitudes simples pour économiser.</p>

                        <div class="panel-grid">
                            <div class="box">
                                <h3><i class="fa-solid fa-wallet"></i> Budget</h3>
                                <ul>
                                    <li>Loyer + charges</li>
                                    <li>Transport</li>
                                    <li>Alimentation</li>
                                    <li>Internet/téléphone</li>
                                    <li>Imprévus</li>
                                </ul>
                            </div>

                            <div class="box">
                                <h3><i class="fa-solid fa-piggy-bank"></i> Astuces</h3>
                                <ul>
                                    <li>Noter ses dépenses</li>
                                    <li>Faire les courses par semaine</li>
                                    <li>Limiter les achats impulsifs</li>
                                </ul>
                            </div>
                        </div>
                    </article>

                    <div class="footer-note">
                        Ne vous inquiétez pas : l’AEEJ vous accompagne à chaque étape.
                        L’objectif est simple : vous permettre d’étudier dans de bonnes conditions.
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>
@endsection
