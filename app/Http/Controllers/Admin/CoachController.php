<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

/** Comptes entraîneurs : créés uniquement par l'administrateur. Vue : admin/coaches/index.blade.php */
class CoachController extends Controller
{
    public function index(): View
    {
        $coaches = User::where('role', User::ROLE_COACH)
            ->withCount('coachedGroups')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.coaches.index', compact('coaches'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30', 'unique:users,phone'],
            'password' => ['required', Password::min(10)],
        ]);

        $coach = new User($data);
        $coach->role = User::ROLE_COACH; // le rôle est attribué ici, jamais par un formulaire public
        $coach->save();

        return back()->with('status', 'Entraîneur créé. Communiquez-lui son mot de passe de façon sécurisée.');
    }

    public function toggle(User $user): RedirectResponse
    {
        abort_unless($user->role === User::ROLE_COACH, 404);

        $user->active = ! $user->active;
        $user->save();

        return back()->with('status', $user->active ? 'Compte réactivé.' : 'Compte désactivé.');
    }
}
