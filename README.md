# AEEJ — Plateforme de l'Association des Étudiants Étrangers à Jendouba

Plateforme web de gestion et d'accueil pour l'**AEEJ**, association d'étudiants étrangers de Jendouba (Tunisie), active depuis 2005.

**En production :** https://aeej-sd58.onrender.com

---

## Le problème

Chaque année, des étudiants étrangers arrivent à Jendouba sans repères : démarches de séjour, logement, gestion du budget, intégration. En parallèle, l'association gère ses membres, ses cotisations et sa trésorerie sans outil dédié.

Cette plateforme répond aux deux besoins : elle accueille et oriente les nouveaux arrivants, et elle donne au bureau les moyens de gérer l'association.

## Fonctionnalités

### Site public

- Présentation de l'association, du bureau exécutif et des activités
- **Guide Étudiant** structuré en quatre volets : intégration, séjour, logement, finances
- Pages d'information sur Jendouba et la faculté
- Galerie photo
- Formulaire de contact et inscription en ligne
- Tableau de bord public : membres, départements, activités, pays représentés

### Espace membre

- Compte individuel et profil
- Notifications et annonces de l'association
- Suivi personnel des cotisations
- Statistiques de l'association

### Administration et trésorerie

Gestion des droits par rôle, chaque fonction du bureau disposant de ses propres accès :

| Rôle | Périmètre |
|---|---|
| Administrateur | Gestion globale : membres, bureau, départements, pays, activités, annonces, galerie |
| Chef trésorier | Configuration des cotisations, supervision de la caisse |
| Trésorier | Enregistrement des cotisations et des dépenses |
| Commissaire aux comptes | Consultation et contrôle des opérations financières |

Les dépenses sont détaillées par lignes, et les documents financiers sont exportables en PDF.

## Stack technique

| Composant | Choix |
|---|---|
| Framework | Laravel 12 (PHP 8.2+) |
| Vues | Blade |
| Styles | Tailwind CSS |
| Build | Vite |
| Base de données | SQLite en local, PostgreSQL en production |
| Médias | Cloudinary |
| Export PDF | barryvdh/laravel-dompdf |
| Déploiement | Docker, hébergement sur Render |

## Installation locale

```bash
git clone https://github.com/bahalsenyaicha1211-prog/Aeej-1.00.git
cd Aeej-1.00
composer install
npm install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
npm run dev
php artisan serve
```

L'application est alors disponible sur `http://localhost:8000`.

## Déploiement

Le déploiement est conteneurisé. Le `Dockerfile` construit l'image, installe les dépendances, compile les assets et récupère le certificat racine nécessaire à la connexion TLS vers la base de données. Le script `build.sh` orchestre la construction sur Render.

## Auteur

**Alseny Bah** — conception, développement et déploiement.

Étudiant en licence Business Information Systems à la Faculté des Sciences Juridiques, Économiques et de Gestion de Jendouba, et membre du bureau de l'AEEJ.
