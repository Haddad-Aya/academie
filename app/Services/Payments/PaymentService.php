<?php

namespace App\Services\Payments;

use App\Models\Payment;
use Illuminate\Support\Facades\DB;

/** Seul point d'entrée pour confirmer un paiement manuel. */
class PaymentService
{
    public function markPaid(Payment $payment, array $payload = []): void
    {
        DB::transaction(function () use ($payment, $payload) {
            $locked = Payment::whereKey($payment->getKey())->lockForUpdate()->firstOrFail();

            if ($locked->status === Payment::PAID) {
                return;
            }

            $locked->status = Payment::PAID;
            $locked->paid_at = now();
            $locked->payload = $payload ?: $locked->payload;
            $locked->save();
        });
    }
}
