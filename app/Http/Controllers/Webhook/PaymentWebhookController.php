<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\Payments\PaymentGateway;
use App\Services\Payments\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/** Appelé par le prestataire (route publique, sans CSRF, protégée par signature). */
class PaymentWebhookController extends Controller
{
    public function __invoke(Request $request, PaymentGateway $gateway, PaymentService $payments): Response
    {
        // Les références inconnues ne nécessitent pas de nouvelle tentative Konnect.
        if ($gateway->name() === 'konnect' && ! Payment::where('provider', $gateway->name())
            ->where('provider_ref', $request->query('payment_ref'))->exists()) {
            return response()->noContent();
        }

        $event = $gateway->parseWebhook($request);

        // Signature fausse ou format inconnu.
        abort_if($event === null, 400);

        $payment = Payment::where('provider', $gateway->name())
            ->where('provider_ref', $event['provider_ref'])
            ->first();

        // Référence inconnue : on répond 200 pour que le prestataire n'insiste pas, sans rien modifier.
        if (! $payment) {
            return response()->noContent();
        }

        if (($event['status'] ?? null) === 'paid') {
            // Montant reçu différent du montant attendu : on refuse de valider.
            if (isset($event['amount']) && abs((float) $event['amount'] - (float) $payment->amount) > 0.001) {
                $payments->markFailed($payment, $event['payload'] ?? []);

                return response()->noContent();
            }

            $payments->markPaid($payment, $event['payload'] ?? []);
        } elseif (($event['status'] ?? null) === 'failed') {
            $payments->markFailed($payment, $event['payload'] ?? []);
        } else {
            // Konnect peut notifier un statut encore pending ; il ne doit pas être déclaré échoué.
            $payments->keepPending($payment, $event['payload'] ?? []);
        }

        return response()->noContent();
    }
}
