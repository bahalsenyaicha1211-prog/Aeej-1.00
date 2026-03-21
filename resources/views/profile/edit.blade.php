<x-member-layout :unreadAnnoncesCount="$unreadAnnoncesCount ?? 0">
    <x-slot name="header">
        Mon profil
    </x-slot>

    @php
        $user = auth()->user();
        // Extraction des initiales : Prénom + Nom
        $nameParts = explode(' ', trim($user->name));
        if (count($nameParts) >= 2) {
            $initials = substr($nameParts[0], 0, 1) . substr(end($nameParts), 0, 1);
        } else {
            $initials = substr($user->name, 0, 2);
        }
        $initials = strtoupper($initials);
    @endphp

    <style>
        :root {
            --profile-primary: #04643c;
            --profile-danger: #e53e3e;
            --profile-bg: #f8fafc;
            --profile-card-shadow: 0 2px 4px rgba(0,0,0,0.05), 0 1px 2px rgba(0,0,0,0.1);
        }

        .profile-container {
            max-width: 1100px;
            margin: 2rem auto;
            padding: 0 1rem;
            font-family: 'Inter', system-ui, sans-serif;
        }

        .profile-header {
            margin-bottom: 2.5rem;
            border-left: 4px solid var(--profile-primary);
            padding-left: 1.5rem;
        }

        .profile-header h1 {
            font-size: 1.875rem;
            font-weight: 800;
            color: #1a202c;
            margin: 0;
        }

        .profile-grid {
            display: grid;
            grid-template-columns: 320px 1fr;
            gap: 2rem;
            align-items: start;
        }

        .profile-card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--profile-card-shadow);
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid #edf2f7;
        }

        /* --- STYLE DES INITIALES --- */
        .avatar-display-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1rem 0;
        }

        .initials-circle {
            width: 130px;
            height: 130px;
            background-color: var(--profile-primary);
            color: white;
            font-size: 3rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            border: 4px solid white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            text-transform: uppercase;
        }

        /* --- CORRECTION DU DÉSORDRE (FORMULAIRES) --- */
        .profile-card .form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            width: 100%;
        }

        .profile-card .field {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            width: 100%;
        }

        .profile-card .input {
            width: 100% !important; /* Force la largeur */
            box-sizing: border-box; /* Évite que l'input dépasse */
            padding: 0.7rem 0.9rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
        }

        .btn {
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: 0.2s;
        }

        .btn--dark { background: #2d3748; color: white; }
        .btn--danger { background: var(--profile-danger); color: white; }

        @media (max-width: 992px) {
            .profile-grid { grid-template-columns: 1fr; }
        }
    </style>

    <div class="profile-container">
        <header class="profile-header">
            <h1>Paramètres du compte</h1>
            <p>Gérez vos informations personnelles et la sécurité de votre accès.</p>
        </header>

        <div class="profile-grid">
            <aside class="profile-sidebar">
                <div class="profile-card">
                    <div class="card-title">
                        <i class="fas fa-user-tag"></i> 
                    </div>
                    <div class="avatar-display-container">
                        <div class="initials-circle">
                            {{ $initials }}
                        </div>
                        <p style="margin-top: 10px; color: #718096; font-size: 0.85rem;">{{ $user->name }}</p>
                    </div>
                </div>
            </aside>

            <main class="profile-main">
                <div class="profile-card">
                    <div class="card-title"><i class="fas fa-user-circle"></i> Informations personnelles</div>
                    @include('profile.partials.update-profile-information-form')
                </div>

                <div class="profile-card">
                    <div class="card-title"><i class="fas fa-lock"></i> Sécurité & Mot de passe</div>
                    @include('profile.partials.update-password-form')
                </div>

                <div class="profile-card card-danger">
                    <div class="card-title"><i class="fas fa-exclamation-triangle"></i> Zone de danger</div>
                    @include('profile.partials.delete-user-form')
                </div>
            </main>
        </div>
    </div>
</x-member-layout>