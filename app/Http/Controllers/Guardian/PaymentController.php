<?php

namespace App\Http\Controllers\Guardian;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\View\View;

/** Informations sur le règlement manuel des cotisations. */
class PaymentController extends Controller
{
    /** Règlement manuel à l’académie. */
    public function pending(Request $request, Payment $payment): View
    {
        $this->ensureOwner($request, $payment);

        return view('parent.payments.pending', compact('payment'));
    }

    private function ensureOwner(Request $request, Payment $payment): void
    {
        abort_unless($payment->registration->child->parent_id === $request->user()->id, 403);
    }
}
