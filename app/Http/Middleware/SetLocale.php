<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->cookie('locale');

        // Le parcours « mot de passe oublié » reste entièrement en français.
        if (str_starts_with((string) $request->route()?->getName(), 'password.')) {
            app()->setLocale('fr');
            return $next($request);
        }

        app()->setLocale(in_array($locale, ['fr', 'ar'], true)
            ? $locale
            : config('app.locale', 'fr'));

        return $next($request);
    }
}
