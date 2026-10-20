# Académie Future Football Djerba

Application Laravel pour le site vitrine et la gestion de l'académie : comptes parents, enfants, inscriptions par groupe, présences, galerie, paiements mensuels et espace d'administration.

## Prérequis

- PHP 8.2 ou plus, avec les extensions Laravel nécessaires
- Composer
- Node.js et npm
- SQLite en local ou MySQL/MariaDB sur l'hébergement

## Installation locale

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
```

Configure ensuite la base de données et le mail dans `.env`, puis lance :

```powershell
php artisan migrate --seed
npm ci
npm run build
php artisan storage:link
php artisan serve
```

L'application est disponible sur `http://127.0.0.1:8000`. Le seeder crée les groupes de départ et un compte administrateur. Configure `ADMIN_EMAIL` et `ADMIN_PASSWORD` avant de lancer le seeder ; si le mot de passe reste vide, un mot de passe aléatoire est affiché une seule fois dans le terminal.

## Déploiement

1. Héberge l'application sur un serveur PHP avec un domaine stable et HTTPS. La racine web du serveur doit pointer vers le dossier `public/`, jamais vers la racine du dépôt.
2. Configure `.env` sur le serveur : `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://ton-domaine`, une base de données de production, un transport SMTP fonctionnel, et un `APP_KEY` généré une seule fois.
3. Déploie les dépendances et les fichiers compilés :

   ```sh
   composer install --no-dev --optimize-autoloader
   npm ci
   npm run build
   php artisan migrate --force
   php artisan storage:link
   php artisan optimize
   ```

4. Lors de la première mise en production seulement, configure `ADMIN_EMAIL` et un mot de passe administrateur robuste, puis exécute `php artisan db:seed --force`.
5. Configure le cron de l'hébergeur pour exécuter `php artisan schedule:run` chaque minute. Le planificateur crée les échéances mensuelles des inscriptions approuvées.
6. Après le déploiement, vérifie la connexion, la création d'une inscription, l'envoi d'e-mails, les images de galerie et les webhooks entrants.

### Pages de panne et maintenance

- Laravel affiche `resources/views/errors/500.blade.php` pour une erreur interne et `resources/views/errors/503.blade.php` pendant le mode maintenance (`php artisan down`). Pour rétablir l'application après maintenance : `php artisan up`.
- Sur Nginx, adapte et inclus `deploy/nginx-errors.conf.example` dans le bloc `server` pour afficher `public/maintenance.html` lors des erreurs passerelle 502, 503 et 504, lorsque Nginx fonctionne encore mais que PHP/Laravel est indisponible.
- Si le VPS, le réseau ou le DNS est totalement indisponible, aucune page hébergée sur ce même serveur ne peut être envoyée au visiteur. Il faut alors une page de secours gérée par un proxy/CDN indépendant de l'hébergement.

Ne mets jamais `.env`, les clés API ou les mots de passe dans Git. Après une fuite d'identifiants, révoque-les et génère-en de nouveaux.

## Paiement Konnect

- Pour les essais, utilise les identifiants et l'URL API du sandbox ; les paiements de test n'utilisent pas de carte réelle.
- Pour accepter de vrais paiements, termine l'activation du compte Konnect de production, obtiens les identifiants de production et remplace les valeurs sandbox dans les variables `KONNECT_*` du serveur.
- Configure un domaine HTTPS stable et accessible par Konnect pour le webhook. Un tunnel temporaire Cloudflare ne convient pas comme adresse de production.
- Teste le cycle complet et le rapprochement des statuts avant d'ouvrir le paiement aux parents. Le service de carte sandbox peut être indisponible indépendamment de l'application.

## Images de la galerie

Les images envoyées par l'administration sont stockées sur le disque public Laravel. En production, conserve le lien `public/storage` créé par `php artisan storage:link` et configure les limites d'envoi PHP selon la taille autorisée par l'application.

## Commandes utiles

```sh
php artisan route:list
php artisan schedule:list
php artisan test
npm run build
```
