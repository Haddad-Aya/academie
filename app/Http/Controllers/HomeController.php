<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/** Après connexion, chaque rôle est envoyé vers son espace. */
class HomeController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        return match ($request->user()->role) {
            User::ROLE_ADMIN => redirect()->route('admin.dashboard'),
            User::ROLE_COACH => redirect()->route('coach.groups'),
            default => redirect()->route('parent.dashboard'),
        };
    }
}
