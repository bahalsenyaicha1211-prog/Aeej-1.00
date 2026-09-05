<x-member-layout :unreadAnnoncesCount="$unreadAnnoncesCount ?? 0">
    <x-slot name="header">
        Mon profil
    </x-slot>

    @php
        $user = auth()->user();
        $membre = $membre ?? $user->membre;

        $nameParts = preg_split('/\s+/', trim($user->name)) ?: [];
        if (count($nameParts) >= 2) {
            $initials = mb_substr($nameParts[0], 0, 1) . mb_substr(end($nameParts), 0, 1);
        } else {
            $initials = mb_substr($user->name, 0, 2);
        }
        $initials = mb_strtoupper($initials);

        $roles = [];
        if ($user->is_admin)               $roles[] = 'Administrateur';
        if ($user->isChefTresorier())      $roles[] = 'Chef trésorier';
        elseif ($user->isTresorier())      $roles[] = 'Trésorier';
        if ($user->isCommissaireComptes()) $roles[] = 'Commissaire aux comptes';
        if (empty($roles))                 $roles[] = 'Membre';
    @endphp

    <style>
        .pf {
            --pf-border: #e5e7eb;
            --pf-muted: #6b7280;
            --pf-text: #111827;
            --pf-brand: #04643c;
            --pf-danger: #dc2626;
            --pf-radius: 16px;
            --pf-shadow: 0 10px 25px rgba(17, 24, 39, .08);
            max-width: 1080px;
            margin: 0 auto;
        }

        .pf__intro {
            color: var(--pf-muted);
            font-size: 14px;
            margin: 0 0 22px;
        }

        .pf__flash {
            border-radius: 12px;
            padding: 12px 14px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 18px;
        }
        .pf__flash--ok { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
        .pf__flash--err { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }

        .pf__layout {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 24px;
            align-items: start;
        }

        .pf__col {
            display: flex;
            flex-direction: column;
            gap: 20px;
            min-width: 0;
        }

        .pf-card {
            background: #fff;
            border: 1px solid var(--pf-border);
            border-radius: var(--pf-radius);
            box-shadow: var(--pf-shadow);
            padding: 20px;
        }
        .pf-card--danger { border-color: #fecaca; }

        .pf-card__title {
            font-size: 15px;
            font-weight: 900;
            color: var(--pf-text);
            margin: 0 0 4px;
        }
        .pf-card__desc {
            font-size: 13px;
            color: var(--pf-muted);
            margin: 0 0 18px;
        }

        /* --- Carte résumé (colonne gauche) --- */
        .pf-summary { text-align: center; position: sticky; top: 90px; }
        .pf-summary__avatar {
            width: 96px;
            height: 96px;
            margin: 4px auto 14px;
            border-radius: 50%;
            background: var(--pf-brand);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: .5px;
        }
        .pf-summary__name {
            font-size: 16px;
            font-weight: 900;
            color: var(--pf-text);
        }
        .pf-summary__email {
            font-size: 12.5px;
            color: var(--pf-muted);
            word-break: break-all;
            margin-top: 2px;
        }
        .pf-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            justify-content: center;
            margin: 12px 0 4px;
        }
        .pf-badge {
            font-size: 11px;
            font-weight: 800;
            padding: 4px 10px;
            border-radius: 999px;
            background: rgba(4, 100, 60, .1);
            color: var(--pf-brand);
        }

        /* --- Liste d'infos --- */
        .pf-list {
            display: grid;
            gap: 0;
        }
        .pf-list__row {
            display: grid;
            grid-template-columns: 140px 1fr;
            gap: 12px;
            padding: 11px 0;
            border-bottom: 1px solid var(--pf-border);
            font-size: 14px;
        }
        .pf-list__row:last-child { border-bottom: 0; }
        .pf-list__row:first-child { padding-top: 0; }
        .pf-list__k { color: var(--pf-muted); font-weight: 700; }
        .pf-list__v { color: var(--pf-text); font-weight: 600; word-break: break-word; }

        /* --- Formulaires (partiels réutilisés) --- */
        .pf .form { display: flex; flex-direction: column; gap: 16px; }
        .pf .section + .section { margin-top: 4px; }
        .pf .section__head { display: block; margin-bottom: 14px; }
        .pf .section__title { font-size: 15px; font-weight: 900; margin: 0 0 4px; }
        .pf .section__desc { font-size: 13px; color: var(--pf-muted); margin: 0; }
        .pf .field { display: flex; flex-direction: column; gap: 6px; }
        .pf .label,
        .pf .field > label {
            font-size: 12px;
            font-weight: 800;
            color: var(--pf-muted);
            text-transform: uppercase;
            letter-spacing: .03em;
        }
        .pf .input {
            width: 100%;
            box-sizing: border-box;
            padding: 11px 12px;
            border: 1px solid var(--pf-border);
            border-radius: 10px;
            font-size: 14px;
            font-family: inherit;
            background: #fff;
            color: var(--pf-text);
            transition: border-color .12s ease, box-shadow .12s ease;
        }
        .pf .input:focus {
            outline: none;
            border-color: var(--pf-brand);
            box-shadow: 0 0 0 3px rgba(4, 100, 60, .12);
        }
        .pf .field__hint { font-size: 12px; color: var(--pf-muted); }
        .pf .actions { margin-top: 4px; display: flex; gap: 10px; flex-wrap: wrap; }

        .pf .btn {
            padding: 10px 18px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 13.5px;
            cursor: pointer;
            border: 1px solid transparent;
            transition: opacity .12s ease;
        }
        .pf .btn:hover { opacity: .9; }
        .pf .btn--dark { background: var(--pf-brand); color: #fff; }
        .pf .btn--danger { background: var(--pf-danger); color: #fff; }

        .pf .alert {
            border-radius: 10px;
            padding: 10px 12px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 12px;
        }
        .pf .alert--danger { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
        .pf .list { margin: 0; padding-left: 18px; }

        /* --- Séparateur entre 2 formulaires d'une même carte --- */
        .pf-divider {
            border: 0;
            border-top: 1px solid var(--pf-border);
            margin: 22px 0;
        }

        @media (max-width: 900px) {
            .pf__layout { grid-template-columns: 1fr; }
            .pf-summary { position: static; }
            .pf-list__row { grid-template-columns: 1fr; gap: 2px; }
        }
    </style>

    <div class="pf">
        <p class="pf__intro">Consultez vos informations personnelles et gérez la sécurité de votre accès.</p>

        @if (session('success'))
            <div class="pf__flash pf__flash--ok">{{ session('success') }}</div>
        @endif
        @if (session('status') === 'password-updated')
            <div class="pf__flash pf__flash--ok">Votre mot de passe a été mis à jour.</div>
        @endif
        @if (session('error'))
            <div class="pf__flash pf__flash--err">{{ session('error') }}</div>
        @endif

        <div class="pf__layout">

            {{-- Colonne gauche : résumé --}}
            <aside>
                <div class="pf-card pf-summary">
                    <div class="pf-summary__avatar">{{ $initials }}</div>
                    <div class="pf-summary__name">{{ $user->name }}</div>
                    <div class="pf-summary__email">{{ $user->email }}</div>

                    <div class="pf-badges">
                        @foreach($roles as $role)
                            <span class="pf-badge">{{ $role }}</span>
                        @endforeach
                    </div>
                </div>
            </aside>

            {{-- Colonne droite --}}
            <div class="pf__col">

                {{-- 1. Informations personnelles (lecture seule) --}}
                <div class="pf-card">
                    <h2 class="pf-card__title">Informations personnelles</h2>
                    <p class="pf-card__desc">
                        Ces informations proviennent de votre fiche d'adhésion.
                        Pour toute correction, contactez le bureau.
                    </p>

                    <div class="pf-list">
                        <div class="pf-list__row">
                            <span class="pf-list__k">Matricule</span>
                            <span class="pf-list__v">{{ $membre?->matricule ?? '—' }}</span>
                        </div>
                        <div class="pf-list__row">
                            <span class="pf-list__k">Nom</span>
                            <span class="pf-list__v">{{ $membre?->nom ?? '—' }}</span>
                        </div>
                        <div class="pf-list__row">
                            <span class="pf-list__k">Prénom</span>
                            <span class="pf-list__v">{{ $membre?->prenom ?? '—' }}</span>
                        </div>
                        <div class="pf-list__row">
                            <span class="pf-list__k">Sexe</span>
                            <span class="pf-list__v">
                                @if($membre?->sexe === 'M') Homme
                                @elseif($membre?->sexe === 'F') Femme
                                @else — @endif
                            </span>
                        </div>
                        <div class="pf-list__row">
                            <span class="pf-list__k">Département</span>
                            <span class="pf-list__v">{{ $membre?->departement?->nom ?? '—' }}</span>
                        </div>
                        <div class="pf-list__row">
                            <span class="pf-list__k">Pays</span>
                            <span class="pf-list__v">{{ $membre?->pays?->nom ?? '—' }}</span>
                        </div>
                        <div class="pf-list__row">
                            <span class="pf-list__k">Année d'adhésion</span>
                            <span class="pf-list__v">{{ $membre?->annee_adhesion ?? '—' }}</span>
                        </div>
                        <div class="pf-list__row">
                            <span class="pf-list__k">Adresse e-mail</span>
                            <span class="pf-list__v">{{ $user->email }}</span>
                        </div>
                    </div>
                </div>

                {{-- 2. Coordonnées + compte (modifiable) --}}
                <div class="pf-card">
                    <h2 class="pf-card__title">Modifier mes informations</h2>
                    <p class="pf-card__desc">Vous pouvez tenir à jour vos coordonnées et votre nom affiché.</p>

                    @include('profile.partials.update-coordonnees-form')

                    <hr class="pf-divider">

                    @include('profile.partials.update-profile-information-form')
                </div>

                {{-- 3. Sécurité --}}
                <div class="pf-card">
                    @include('profile.partials.update-password-form')
                </div>

                {{-- 4. Zone de danger --}}
                <div class="pf-card pf-card--danger">
                    @include('profile.partials.delete-user-form')
                </div>

            </div>
        </div>
    </div>
</x-member-layout>
