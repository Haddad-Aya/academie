<?php

namespace App\Http\Controllers\Guardian;

use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\TrainingGroup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

/** Inscription d'un enfant à un groupe (écran inscription.html). Vue : parent/registrations/create.blade.php */
class RegistrationController extends Controller
{
    public function create(Child $child): View
    {
        Gate::authorize('view', $child);

        $groups = TrainingGroup::where('active', true)->orderBy('age_min')->get();
        $suggested = $groups->first(fn ($g) => $g->acceptsAge($child->age));

        return view('parent.registrations.create', compact('child', 'groups', 'suggested'));
    }

    public function store(Request $request, Child $child): RedirectResponse
    {
        Gate::authorize('update', $child);

        $data = $request->validate([
            'training_group_id' => ['required', 'integer', 'exists:training_groups,id'],
            'pay' => ['nullable', 'in:online,later'],
        ]);

        $season = config('academy.season');
        $group = TrainingGroup::where('active', true)->findOrFail($data['training_group_id']);

        if (! $group->acceptsAge($child->age)) {
            return back()->withErrors(['training_group_id' => "Ce groupe accueille les {$group->age_min}-{$group->age_max} ans."])->withInput();
        }

        if ($child->registrations()->where('season', $season)
            ->whereIn('status', [Registration::PENDING, Registration::APPROVED])->exists()) {
            return back()->withErrors(['training_group_id' => 'Cet enfant est déjà inscrit pour la saison en cours.']);
        }

        if ($group->reservedSeats($season) >= $group->capacity) {
            return back()->withErrors(['training_group_id' => 'Ce groupe est complet.']);
        }

        $paymentMethod = ($data['pay'] ?? 'later') === 'online'
            ? Payment::METHOD_CARD
            : Payment::METHOD_ONSITE;

        $payment = DB::transaction(function () use ($child, $group, $season, $paymentMethod) {
            $registration = new Registration([
                'child_id' => $child->id,
                'training_group_id' => $group->id,
                'season' => $season,
            ]);
            $registration->status = Registration::PENDING;
            $registration->amount = $group->price;
            $registration->save();

            if ((float) $group->price <= 0) {
                return null; // groupe gratuit ou prix non renseigné : rien à payer
            }

            return Payment::create([
                'registration_id' => $registration->id,
                'provider' => config('payments.driver'),
                'amount' => $group->price,
                'currency' => config('payments.currency'),
                'billing_month' => now()->format('Y-m'),
                'payment_method' => $paymentMethod,
            ]);
        });

        if ($payment && ($data['pay'] ?? 'later') === 'online') {
            return redirect()->route('parent.payments.checkout', $payment);
        }

        return redirect()->route('parent.dashboard')
            ->with('status', 'Inscription envoyée. Elle sera confirmée par l\'académie.');
    }
}
