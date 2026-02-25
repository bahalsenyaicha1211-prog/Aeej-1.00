<x-member-layout :unreadAnnoncesCount="$unreadAnnoncesCount ?? 0">
    <x-slot name="header">
        Mon profil
    </x-slot>

    <style>
        :root {
            --profile-primary: #3182ce;
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

        /* En-tête */
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

        .profile-header p {
            color: #718096;
            margin-top: 0.5rem;
        }

        /* Layout Grid */
        .profile-grid {
            display: grid;
            grid-template-columns: 320px 1fr;
            gap: 2rem;
            align-items: start;
        }

        /* Cartes Style */
        .profile-card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--profile-card-shadow);
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid #edf2f7;
        }

        .card-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.1rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .card-title i {
            color: var(--profile-primary);
        }

        /* Colonne de gauche (Sticky) */
        .profile-sidebar {
            position: sticky;
            top: 2rem;
        }

        /* Zone de danger spécifique */
        .card-danger {
            border: 1px solid #fed7d7;
            background-color: #fffafb;
        }
        
        .card-danger .card-title {
            color: var(--profile-danger);
            border-bottom-color: #feb2b2;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .profile-grid {
                grid-template-columns: 1fr;
            }
            .profile-sidebar {
                position: static;
            }
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
                        <i class="fas fa-camera"></i> Photo de profil
                    </div>
                    @include('profile.partials.update-profile-photo-form')
                </div>
            </aside>

            <main class="profile-main">
                
                <div class="profile-card">
                    <div class="card-title">
                        <i class="fas fa-user-circle"></i> Informations personnelles
                    </div>
                    @include('profile.partials.update-profile-information-form')
                </div>

                <div class="profile-card">
                    <div class="card-title">
                        <i class="fas fa-lock"></i> Sécurité & Mot de passe
                    </div>
                    @include('profile.partials.update-password-form')
                </div>

                <div class="profile-card card-danger">
                    <div class="card-title">
                        <i class="fas fa-exclamation-triangle"></i> Zone de danger
                    </div>
                    @include('profile.partials.delete-user-form')
                </div>

            </main>
        </div>
    </div>
</x-member-layout>