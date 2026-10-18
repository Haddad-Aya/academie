<?php

namespace App\Http\Controllers\Guardian;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\TrainingSession;
use Illuminate\Http\Request;
use Illuminate\View\View;

/** Espace parent : accueil. Vue attendue : resources/views/parent/dashboard.blade.php (écran parent.html) */
class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $season = config('academy.season');

        $children = $request->user()->children()
            ->with([
                'registrations' => fn ($q) => $q->where('season', $season),
                'registrations.trainingGroup',
            ])
            ->get();

        $registrations = $children->flatMap(fn ($c) => $c->registrations);

        $due = Payment::with('registration.child')
            ->whereIn('registration_id', $registrations->pluck('id'))
            ->whereIn('status', [Payment::PENDING, Payment::FAILED])
            ->orderBy('billing_month')
            ->get();

        $groupIds = $registrations
            ->where('status', Registration::APPROVED)
            ->pluck('training_group_id')
            ->unique()
            ->values();

        $announcements = Announcement::whereNull('training_group_id')
            ->orWhereIn('training_group_id', $groupIds)
            ->latest()
            ->limit(3)
            ->get();

        $nextSession = TrainingSession::with('trainingGroup')
            ->whereIn('training_group_id', $groupIds)
            ->where('starts_at', '>=', now())
            ->orderBy('starts_at')
            ->first();

        return view('parent.dashboard', compact('children', 'due', 'announcements', 'nextSession'));
    }
}
