# Documentation du projet — Académie Future Football Djerba

## 1. Présentation

Application web pour une académie de football. Elle comprend un site vitrine public et des espaces authentifiés pour les parents, les entraîneurs et l’administration. Les données sont gérées par Laravel et stockées dans une base SQL.

Fonctionnalités principales :

- Présentation de l’académie, des groupes, des entraîneurs et de la galerie.
- Comptes parents et fiches des enfants.
- Inscriptions des enfants aux groupes et validation par l’administration.
- Création des séances et enregistrement des présences par les entraîneurs.
- Cotisations mensuelles par enfant, suivi des paiements et statistiques.
- Cotisations mensuelles réglées sur place et confirmées par l’administration.
- Interface en français et en arabe pour le site et les espaces parents/entraîneurs ; interface d’administration en français.

## 2. Technologies et versions

| Élément | Version ou contrainte du projet |
|---|---|
| PHP | `^8.2` ; environnement local observé : PHP 8.2.12 |
| Laravel | 12.70.0 dans `composer.lock` |
| Composer | 2.8.6 dans l’environnement local observé |
| Base de données | SQLite en local ; MySQL/MariaDB prévu sur l’hébergement |
| Node.js | 22.20.0 dans l’environnement local observé |
| npm | 10.9.3 dans l’environnement local observé |
| Vite | 7.3.7 dans `package-lock.json` |
| Tailwind CSS | 3.4.19 dans `package-lock.json` |
| Alpine.js | 3.17.4 dans `package-lock.json` |
| PHPUnit | 11.5.57 dans `composer.lock` |

Les versions verrouillées sont celles des fichiers `composer.lock` et `package-lock.json`. Pour les reproduire, utiliser `composer install` et `npm ci` plutôt que des installations non verrouillées.

## 3. Langages et interface

- **PHP** : logique métier, contrôleurs, modèles, middleware et commandes Laravel.
- **Blade, HTML** : pages et gabarits côté serveur (`resources/views/`).
- **CSS** : styles du site, notamment `public/css/style.css` et `resources/css/app.css`.
- **JavaScript** : interaction et bascule de langue (`public/js/i18n.js`) ; Alpine.js est initialisé dans `resources/js/app.js`.
- **Français/arabe** : le choix est mémorisé dans le navigateur et un cookie `locale` permet aussi à Laravel de choisir la langue des réponses serveur. L’arabe active le sens RTL. Le parcours de réinitialisation du mot de passe reste en français.

Les vues sont rendues par Laravel/Blade. Le projet n’est pas une application React.

## 4. Architecture

Le flux général est :

```text
Navigateur
   ↓ requête HTTP
routes/web.php → middleware (authentification, rôle, langue) → contrôleur
                                                    ↓
                                      modèles Eloquent / services
                                                    ↓
                                       base de données et stockage
```

Répertoires importants :

| Chemin | Contenu |
|---|---|
| `app/Http/Controllers/` | Contrôleurs des espaces public, parent, coach et administration. |
| `app/Http/Middleware/` | Contrôle des rôles et choix de langue. |
| `app/Models/` | Modèles Eloquent et relations entre les données. |
| `app/Services/Payments/` | Service de confirmation des règlements manuels. |
| `app/Console/Commands/` | Commandes Artisan, dont la génération des cotisations mensuelles. |
| `routes/web.php` et `routes/auth.php` | Routes du site et de l’authentification. |
| `resources/views/` | Vues Blade, classées par espace (`admin`, `parent`, `coach`, `auth`, `vitrine`). |
| `public/` | Fichiers accessibles au navigateur : CSS, JavaScript, logo, favicon et page de maintenance. |
| `database/migrations/` | Création et évolution du schéma de la base. |
| `database/seeders/` | Données initiales et création initiale de l’administrateur. |
| `config/` | Configuration Laravel, académie, devise et compte admin initial. |
| `storage/app/public/` | Images téléversées dans la galerie ; publiées via `public/storage`. |

### Rôles

- `parent` : rôle attribué par défaut aux comptes créés via `/register` ; fournit son téléphone et son e-mail, puis gère ses enfants et leurs inscriptions/paiements.
- `coach` : compte créé par l’administration ; gère ses groupes, séances et présences, et peut appeler ou écrire aux parents des enfants inscrits dans ses groupes. Il ne voit pas les notes médicales.
- `admin` : compte initial créé par le seeder ; gère inscriptions, groupes, entraîneurs, galerie et paiements.

Les espaces protégés vérifient la connexion et le rôle. Un formulaire public ne peut pas choisir lui-même un rôle admin ou coach.

### Données principales

- `users` : parents, entraîneurs et administrateurs.
- `children` : enfants liés à leur parent ; les notes médicales sont chiffrées avec `APP_KEY`.
- `training_groups` : catégories, âges, entraîneur, horaires, capacité et tarif.
- `registrations` : inscription d’un enfant à un groupe pour une saison.
- `training_sessions` et `attendances` : séances et appel enregistré par enfant.
- `payments` : échéance mensuelle d’un enfant, statut et méthode de règlement. Les anciennes lignes restent conservées comme historique.
- `academy_gallery_items` : images et légendes de la galerie.
- `announcements` : annonces destinées à un groupe ou à toute l’académie.

### Parcours d’un règlement

1. Une échéance mensuelle est créée pour chaque inscription approuvée et tarifée.
2. Le parent règle la cotisation à l’académie.
3. L’administration confirme la réception dans la liste des paiements ; le statut passe alors à `paid`.
4. Les paiements précédemment créés par l’ancien parcours restent visibles comme historique, mais ne peuvent plus être initiés depuis l’application.

## 5. Installation et lancement en local (Windows/PowerShell)

Prérequis : PHP 8.2+, Composer, Node.js/npm et les extensions PHP requises par Laravel et le pilote de base choisi.

Depuis le dossier du projet :

```powershell
composer install
Copy-Item .env.example .env
```

Si `.env` existe déjà, ne le remplace pas : conserve-le et modifie uniquement les variables nécessaires. Configure au minimum la base, `APP_URL`, `ADMIN_EMAIL` et `ADMIN_PASSWORD`, puis lance :

```powershell
php artisan key:generate
php artisan migrate --seed
npm ci
npm run build
php artisan storage:link
```

Pour lancer les services de développement configurés dans Composer :

```powershell
composer run dev
```

Cette commande lance le serveur Laravel, Vite et les outils de développement configurés. Le site est normalement accessible sur `http://127.0.0.1:8000`. Autre possibilité : lancer `php artisan serve` et `npm run dev` dans deux terminaux.

Le seeder crée les groupes de départ et, si l’adresse n’existe pas encore, le premier compte admin. Si `ADMIN_PASSWORD` est vide, un mot de passe aléatoire est affiché une seule fois dans le terminal. Ne partage pas cette sortie.

Commandes utiles :

```powershell
php artisan route:list
php artisan schedule:list
php artisan test
npm run build
```

## 6. Routes HTTP

Les routes ci-dessous sont définies dans `routes/web.php` et `routes/auth.php`. Les routes de gestion sont protégées par authentification et contrôle de rôle ; les routes de paiement parent exigent le rôle `parent`.

### Site public

| Méthode | URL | Fonction |
|---|---|---|
| `GET` | `/` | Site vitrine. |
| `GET` | `/up` | Vérification d’état Laravel. |

### Authentification et profil

| Méthode | URL | Fonction |
|---|---|---|
| `GET`, `POST` | `/register` | Création d’un compte parent. |
| `GET`, `POST` | `/login` | Connexion. |
| `POST` | `/logout` | Déconnexion. |
| `GET`, `POST` | `/forgot-password` | Demande de lien de réinitialisation. |
| `GET` | `/reset-password/{token}` | Formulaire de nouveau mot de passe. |
| `POST` | `/reset-password` | Enregistrement du nouveau mot de passe. |
| `GET`, `POST` | `/confirm-password` | Confirmation du mot de passe. |
| `PUT` | `/password` | Changement de mot de passe connecté. |
| `GET` | `/verify-email` | Écran de vérification e-mail. |
| `GET` | `/verify-email/{id}/{hash}` | Confirmation de l’adresse e-mail. |
| `POST` | `/email/verification-notification` | Renvoi du lien de vérification. |
| `GET`, `PATCH`, `DELETE` | `/profile` | Afficher, modifier ou supprimer le profil connecté. |
| `GET` | `/dashboard` | Redirection vers l’espace correspondant au rôle. |

### Espace parent (`/parent`, rôle `parent`)

| Méthode | URL | Fonction |
|---|---|---|
| `GET` | `/parent` | Tableau de bord du parent. |
| `GET` | `/parent/children/create` | Formulaire d’ajout d’un enfant. |
| `POST` | `/parent/children` | Enregistrer l’enfant. |
| `GET` | `/parent/children/{child}/edit` | Modifier une fiche enfant. |
| `PUT` | `/parent/children/{child}` | Enregistrer les modifications. |
| `GET` | `/parent/children/{child}/register` | Choisir un groupe pour l’enfant. |
| `POST` | `/parent/children/{child}/register` | Créer l’inscription et son échéance initiale. |
| `GET` | `/parent/payments/{payment}/pending` | Instructions de règlement manuel. |

### Espace entraîneur (`/coach`, rôle `coach` ou `admin`)

| Méthode | URL | Fonction |
|---|---|---|
| `GET` | `/coach` | Groupes attribués et séances proches. |
| `POST` | `/coach/groups/{group}/sessions` | Créer une séance. |
| `GET` | `/coach/sessions/history` | Historique filtrable des séances/appels. |
| `GET` | `/coach/sessions/{session}` | Afficher la feuille d’appel. |
| `POST` | `/coach/sessions/{session}` | Enregistrer ou mettre à jour les présences. |

### Administration (`/admin`, rôle `admin`)

| Méthode | URL | Fonction |
|---|---|---|
| `GET` | `/admin` | Tableau de bord. |
| `GET` | `/admin/registrations` | Liste et filtres des inscriptions. |
| `POST` | `/admin/registrations/{registration}/approve` | Valider une inscription. |
| `POST` | `/admin/registrations/{registration}/reject` | Refuser une inscription. |
| `GET` | `/admin/registrations/export` | Export CSV des inscriptions. |
| `GET` | `/admin/groups` | Liste des groupes. |
| `GET` | `/admin/groups/create` | Formulaire de création d’un groupe. |
| `POST` | `/admin/groups` | Créer un groupe. |
| `GET` | `/admin/groups/{group}/edit` | Formulaire de modification. |
| `PUT` | `/admin/groups/{group}` | Enregistrer le groupe. |
| `GET` | `/admin/coaches` | Liste des entraîneurs. |
| `POST` | `/admin/coaches` | Créer un compte entraîneur. |
| `POST` | `/admin/coaches/{user}/toggle` | Activer/désactiver un entraîneur. |
| `GET` | `/admin/gallery` | Gérer la galerie. |
| `POST` | `/admin/gallery` | Ajouter une image. |
| `DELETE` | `/admin/gallery/{item}` | Supprimer une image. |
| `GET` | `/admin/payments` | Liste et filtres de paiements. |
| `GET` | `/admin/payments/statistics` | Statistiques mensuelles. |
| `POST` | `/admin/payments/{payment}/paid` | Enregistrer un règlement reçu sur place. |

## 7. Configuration (`.env`)

Le fichier `.env.example` est un modèle local. Le vrai `.env` contient des secrets et ne doit pas être ajouté à Git.

Variables importantes :

- Application : `APP_ENV`, `APP_DEBUG`, `APP_URL`, `APP_KEY`, `APP_LOCALE`.
- Base de données : `DB_CONNECTION` et paramètres `DB_*` ; SQLite en local ou MySQL/MariaDB en hébergement.
- E-mails : `MAIL_MAILER`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_FROM_ADDRESS`.
- Premier admin : `ADMIN_EMAIL`, `ADMIN_PASSWORD`.
- Devise utilisée pour les cotisations : TND, définie dans `config/payments.php`.
- Académie : `ACADEMY_SEASON`, `ACADEMY_NAME`.


## 8. Tâches automatiques et stockage

- `academy:generate-monthly-payments` crée les échéances du mois pour les inscriptions approuvées et les groupes tarifés.
- La tâche est planifiée chaque jour à 00:05 dans `routes/console.php`. L’hébergement doit appeler `php artisan schedule:run` chaque minute via cron.
- Les images de galerie sont enregistrées sur le disque `public` sous `storage/app/public/academy-gallery`. `php artisan storage:link` expose ce stockage via `public/storage`.
- L’administration accepte des images JPEG/JFIF, PNG, GIF, WebP, AVIF, BMP et TIFF, jusqu’à 5 Mo par fichier.
- Les listes longues sont paginées : inscriptions (20), paiements (25), groupes et entraîneurs (20), galerie (12), historique des séances (20) et échéances en attente côté parent (10). Les pages mensuelles de statistiques sont calculées en base, avec des index sur le mois et la date du paiement.
- La feuille d’appel affiche toute la liste du groupe pour enregistrer présents et absents dans une seule opération ; la capacité maximale d’un groupe est limitée à 200 enfants.

## 9. Déploiement

Le guide de mise en ligne détaillé se trouve dans [README.md](README.md). Points impératifs : racine web sur `public/`, PHP 8.2+, HTTPS, base de production, `APP_ENV=production`, `APP_DEBUG=false`, SMTP réel, stockage accessible en écriture et cron actif.

Ne régénère pas `APP_KEY` après le déploiement : les notes médicales chiffrées existantes en dépendent. Sauvegarde la base, les images et la clé de chiffrement de manière sécurisée.
