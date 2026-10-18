<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Child;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\TrainingGroup;
use Illuminate\View\View;

/** Tableau de bord administrateur (écran admin.html). Vue : admin/dashboard.blade.php */
class DashboardController extends Controller
{
    public function index(): View
    {
        $season = config('academy.season');

        $stats = [
            'to_review' => Registration::where('season', $season)->where('status', Registration::PENDING)->count(),
            'active_players' => Registration::where('season', $season)->where('status', Registration::APPROVED)->count(),
            'pending_payments' => Payment::where('status', Payment::PENDING)->count(),
            'attendance_rate' => $this->attendanceRate(),
        ];

        $pending = Registration::with(['child.parent', 'trainingGroup', 'payments'])
            ->where('season', $season)
            ->where('status', Registration::PENDING)
            ->latest()
            ->limit(10)
            ->get();

        $groups = TrainingGroup::with('coach')->where('active', true)->orderBy('age_min')->get();

        return view('admin.dashboard', compact('stats', 'pending', 'groups'));
    }

    private function attendanceRate(): ?int
    {
        $total = Attendance::count();

        return $total === 0 ? null : (int) round(Attendance::where('present', true)->count() / $total * 100);
    }
}
