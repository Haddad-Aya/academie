<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\Payments\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/** Suivi des paiements et encaissement manuel. Vue : admin/payments/index.blade.php */
class PaymentController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');

        $payments = Payment::with('registration.child.parent', 'registration.trainingGroup')
            ->when(in_array($status, [Payment::PENDING, Payment::PAID, Payment::FAILED], true),
                fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('admin.payments.index', compact('payments', 'status'));
    }

    /** Paiement reçu en espèces ou par virement. */
    public function markPaid(Request $request, Payment $payment, PaymentService $service): RedirectResponse
    {
        $service->markPaid($payment, [
            'method' => 'manual',
            'validated_by' => $request->user()->id,
        ]);

        return back()->with('status', 'Paiement enregistré.');
    }
}
