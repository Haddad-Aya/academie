<?php

namespace App\Services\Payments;

use App\Models\Payment;
use Illuminate\Support\Facades\DB;

/** Seul point d'entrée pour changer le statut d'un paiement. Idempotent : rejouer un webhook est sans effet. */
class PaymentService
{
    public function markPaid(Payment $payment, array $payload = []): void
    {
        DB::transaction(function () use ($payment, $payload) {
            $locked = Payment::whereKey($payment->getKey())->lockForUpdate()->firstOrFail();

            if ($locked->status === Payment::PAID) {
                return; // déjà traité
            }

            $locked->status = Payment::PAID;
            $locked->paid_at = now();
            $locked->payload = $payload ?: $locked->payload;
            $locked->save();
        });
    }

    public function markFailed(Payment $payment, array $payload = []): void
    {
        DB::transaction(function () use ($payment, $payload) {
            $locked = Payment::whereKey($payment->getKey())->lockForUpdate()->firstOrFail();

            if ($locked->status === Payment::PAID) {
                return; // un paiement réussi ne revient jamais en échec
            }

            $locked->status = Payment::FAILED;
            $locked->payload = $payload ?: $locked->payload;
            $locked->save();
        });
    }

    /** Un statut temporaire du prestataire ne doit pas clôturer le paiement. */
    public function keepPending(Payment $payment, array $payload = []): void
    {
        DB::transaction(function () use ($payment, $payload) {
            $locked = Payment::whereKey($payment->getKey())->lockForUpdate()->firstOrFail();

            if ($locked->status !== Payment::PENDING) {
                return;
            }

            $locked->payload = $payload ?: $locked->payload;
            $locked->save();
        });
    }
}
