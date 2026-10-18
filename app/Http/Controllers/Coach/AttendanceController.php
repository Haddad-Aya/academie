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
use Illuminate\Support\Facades\Gate;
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
                ->orderBy('starts_at')])
            ->get();

        return view('coach.groups', compact('groups'));
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

    /** Feuille d'appel. L'entraîneur ne voit que prénom et nom : jamais les notes médicales ni le parent. */
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

        $presentIds = $data['present'] ?? [];
        $now = now();

        // Seuls les enfants réellement inscrits (validés) à ce groupe sont pris en compte.
        $rows = $this->players($session)->map(fn (Child $c) => [
            'training_session_id' => $session->id,
            'child_id' => $c->id,
            'present' => in_array($c->id, $presentIds, true),
            'recorded_by' => $request->user()->id,
            'created_at' => $now,
            'updated_at' => $now,
        ])->all();

        Attendance::upsert($rows, ['training_session_id', 'child_id'], ['present', 'recorded_by', 'updated_at']);

        return back()->with('status', 'Appel enregistré.');
    }

    private function players(TrainingSession $session)
    {
        return Child::query()
            ->select(['id', 'first_name', 'last_name'])
            ->whereHas('registrations', fn ($q) => $q
                ->where('training_group_id', $session->training_group_id)
                ->where('season', config('academy.season'))
                ->where('status', Registration::APPROVED))
            ->orderBy('first_name')
            ->get();
    }
}
