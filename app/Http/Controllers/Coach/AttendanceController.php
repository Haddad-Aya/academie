<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Child;
use App\Models\Registration;
use App\Models\TrainingGroup;
use App\Models\TrainingSession;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/** Espace entraîneur (écran presence.html). Vues : coach/groups.blade.php, coach/attendance.blade.php */
class AttendanceController extends Controller
{
    /** Mes groupes et leurs prochaines séances. */
    public function groups(Request $request): View
    {
        $user = $request->user();

        $groups = TrainingGroup::query()
            ->where('active', true)
            ->when($user->role === User::ROLE_COACH, fn ($q) => $q->where('coach_id', $user->id))
            ->with(['sessions' => fn ($q) => $q
                ->where('starts_at', '>=', now()->subDay())
                ->where('starts_at', '<=', now()->addDays(30))
                ->orderBy('starts_at')
                ->limit(5)])
            ->get();

        return view('coach.groups', compact('groups'));
    }

    /** Historique consultable sans charger toutes les séances dans l'espace principal. */
    public function history(Request $request): View
    {
        $validated = $request->validate([
            'year' => ['nullable', 'integer', 'min:2020', 'max:2100'],
            'month' => ['nullable', 'integer', 'between:1,12'],
            'group_id' => ['nullable', 'integer'],
        ]);

        $user = $request->user();
        $year = (int) ($validated['year'] ?? now()->year);
        $month = isset($validated['month']) ? (int) $validated['month'] : null;
        $periodStart = Carbon::create($year, $month ?? 1, 1)->startOfMonth();
        $periodEnd = $month
            ? $periodStart->copy()->endOfMonth()
            : Carbon::create($year, 12, 31)->endOfDay();
        $groups = TrainingGroup::query()
            ->when($user->role === User::ROLE_COACH, fn ($query) => $query->where('coach_id', $user->id))
            ->orderBy('name')
            ->get(['id', 'name']);
        $groupId = isset($validated['group_id']) && $groups->contains('id', (int) $validated['group_id'])
            ? (int) $validated['group_id']
            : null;

        $sessions = TrainingSession::query()
            ->with('trainingGroup:id,name')
            ->withCount('attendances')
            ->withCount(['attendances as present_count' => fn ($query) => $query->where('present', true)])
            ->whereBetween('starts_at', [$periodStart, $periodEnd])
            ->when($groupId, fn ($query) => $query->where('training_group_id', $groupId))
            ->when($user->role === User::ROLE_COACH, fn ($query) => $query->whereHas(
                'trainingGroup', fn ($groupQuery) => $groupQuery->where('coach_id', $user->id),
            ))
            ->orderByDesc('starts_at')
            ->paginate(20)
            ->withQueryString();

        return view('coach.sessions.history', compact('sessions', 'groups', 'year', 'month', 'groupId'));
    }

    public function storeSession(Request $request, TrainingGroup $group): RedirectResponse
    {
        Gate::authorize('manage', $group);

        $data = $request->validate([
            'starts_at' => ['required', 'date', 'after:'.now()->subDay()->toDateTimeString()],
            'location' => ['nullable', 'string', 'max:120'],
        ]);

        $group->sessions()->create([
            'starts_at' => $data['starts_at'],
            'location' => $data['location'] ?? $group->location,
        ]);

        return back()->with('status', 'Séance ajoutée.');
    }

    /** Feuille d'appel : coordonnées du parent incluses pour les seuls enfants du groupe attribué. */
    public function show(TrainingSession $session): View
    {
        Gate::authorize('manage', $session->trainingGroup);

        $players = $this->players($session);
        $present = $session->attendances()->where('present', true)->pluck('child_id')->all();

        return view('coach.attendance', compact('session', 'players', 'present'));
    }

    public function store(Request $request, TrainingSession $session): RedirectResponse
    {
        Gate::authorize('manage', $session->trainingGroup);

        $data = $request->validate([
            'present' => ['nullable', 'array'],
            'present.*' => ['integer'],
        ]);

        // Recalculer la liste côté serveur évite d'enregistrer des enfants qui
        // ne sont pas inscrits dans le groupe ou dont l'inscription n'est pas validée.
        $players = $this->players($session);
        if ($players->isEmpty()) {
            return back()->withErrors(['attendance' => 'Aucun enfant avec une inscription validée dans ce groupe. L’appel n’a pas été enregistré.']);
        }

        $presentIds = collect($data['present'] ?? [])->map(fn ($id) => (int) $id)->all();

        DB::transaction(function () use ($players, $presentIds, $session, $request) {
            foreach ($players as $child) {
                Attendance::updateOrCreate(
                    ['training_session_id' => $session->id, 'child_id' => $child->id],
                    [
                        'present' => in_array($child->id, $presentIds, true),
                        'recorded_by' => $request->user()->id,
                    ],
                );
            }
        });

        $savedCount = $session->attendances()->whereIn('child_id', $players->modelKeys())->count();
        if ($savedCount !== $players->count()) {
            return back()->withErrors(['attendance' => 'L’appel n’a pas pu être entièrement enregistré. Vérifiez la base de données puis réessayez.']);
        }

        return back()->with('status', "Appel enregistré et vérifié pour {$savedCount} enfant(s).");
    }

    private function players(TrainingSession $session)
    {
        return Child::query()
            ->select(['id', 'parent_id', 'first_name', 'last_name'])
            ->with('parent:id,name,email,phone')
            ->whereHas('registrations', fn ($q) => $q
                ->where('training_group_id', $session->training_group_id)
                ->where('season', config('academy.season'))
                ->where('status', Registration::APPROVED))
            ->orderBy('first_name')
            ->get();
    }
}
