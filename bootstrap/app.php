<?php

use App\Http\Middleware\EnsureRole;
use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Le site peut être servi derrière Cloudflare Tunnel / un reverse proxy.
        // Lire le protocole transmis (X-Forwarded-Proto) pour que asset() génère
        // des URL HTTPS quand la requête publique est chiffrée.
        $middleware->trustProxies(at: '*');

        // Synchronise la langue choisie dans l'interface avec Laravel
        // (validation, authentification et autres messages côté serveur).
        $middleware->web(append: [SetLocale::class]);

        // Alias utilisé dans routes/web.php : ->middleware('role:admin')
        $middleware->alias([
            'role' => EnsureRole::class,
        ]);

        // Le prestataire de paiement appelle cette URL sans jeton CSRF :
        // l'authenticité est vérifiée par la signature du webhook.
        $middleware->validateCsrfTokens(except: ['webhooks/*']);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
