<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Utilisation : ->middleware('role:admin')  ou  ->middleware('role:coach,admin')
 * Refuse (403) tout utilisateur désactivé ou dont le rôle n'est pas dans la liste.
 */
class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! $user->active || ! in_array($user->role, $roles, true)) {
            abort(403);
        }

        return $next($request);
    }
}
