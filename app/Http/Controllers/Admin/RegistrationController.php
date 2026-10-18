<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/** Validation des inscriptions. Vue : admin/registrations/index.blade.php */
class RegistrationController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');

        $registrations = Registration::with(['child.parent', 'trainingGroup', 'payments'])
            ->where('season', config('academy.season'))
            ->when(in_array($status, [Registration::PENDING, Registration::APPROVED, Registration::REJECTED], true),
                fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.registrations.index', compact('registrations', 'status'));
    }

    public function approve(Request $request, Registration $registration): RedirectResponse
    {
        return $this->review($request, $registration, Registration::APPROVED, 'Inscription validée.');
    }

    public function reject(Request $request, Registration $registration): RedirectResponse
    {
        return $this->review($request, $registration, Registration::REJECTED, 'Inscription refusée.');
    }

    private function review(Request $request, Registration $registration, string $status, string $message): RedirectResponse
    {
        $registration->status = $status;
        $registration->reviewed_by = $request->user()->id;
        $registration->reviewed_at = now();
        $registration->save();

        return back()->with('status', $message);
    }
}
