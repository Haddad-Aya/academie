<?php

namespace App\Services\Payments;

use App\Models\Payment;
use Illuminate\Http\Request;

/**
 * Paiement hors ligne (espèces, virement, paiement au stade).
 * Le parent voit les instructions ; l'administrateur marque le paiement comme reçu.
 * Utilisable dès le premier jour, sans compte chez un prestataire.
 */
class ManualGateway implements PaymentGateway
{
    public function name(): string
    {
        return 'manual';
    }

    public function checkoutUrl(Payment $payment): string
    {
        return route('parent.payments.pending', $payment);
    }

    public function parseWebhook(Request $request): ?array
    {
        return null; // aucun webhook en mode manuel
    }
}
