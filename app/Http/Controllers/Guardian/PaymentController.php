<?php

namespace App\Http\Controllers\Guardian;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\Payments\PaymentGateway;
use App\Services\Payments\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/** Paiement côté parent. Vues : parent/payments/pending.blade.php et return.blade.php */
class PaymentController extends Controller
{
    /** Démarre le paiement et redirige le parent vers la page du prestataire. */
    public function checkout(Request $request, Payment $payment, PaymentGateway $gateway): RedirectResponse
    {
        $this->ensureOwner($request, $payment);

        if ($payment->status === Payment::PAID) {
            return redirect()->route('parent.dashboard')->with('status', 'Ce paiement est déjà réglé.');
        }

        $payment->payment_method = $gateway->name() === 'konnect'
            ? Payment::METHOD_CARD
            : Payment::METHOD_ONSITE;
        $payment->save();

        return redirect()->away($gateway->checkoutUrl($payment));
    }

    /** Mode manuel : instructions pour payer sur place ou par virement. */
    public function pending(Request $request, Payment $payment): View
    {
        $this->ensureOwner($request, $payment);

        return view('parent.payments.pending', compact('payment'));
    }

    /** Page de retour : vérifie le statut auprès de Konnect avant de l'afficher. */
    public function returned(
        Request $request,
        Payment $payment,
        PaymentGateway $gateway,
        PaymentService $payments,
    ): View
    {
        $this->ensureOwner($request, $payment);

        // Le webhook peut être retardé ou inaccessible pendant les tests locaux
        // (par exemple si le tunnel HTTPS a changé). Au retour de la caisse,
        // on redemande donc le statut à Konnect côté serveur.
        if ($payment->provider === 'konnect' && $payment->provider_ref && $gateway->name() === 'konnect') {
            $verificationRequest = Request::create('/webhooks/konnect', 'GET', [
                'payment_ref' => $payment->provider_ref,
            ]);
            $event = $gateway->parseWebhook($verificationRequest);

            if ($event !== null) {
                if (($event['status'] ?? null) === 'paid') {
                    $payments->markPaid($payment, $event['payload'] ?? []);
                } elseif (($event['status'] ?? null) === 'failed') {
                    $payments->markFailed($payment, $event['payload'] ?? []);
                } else {
                    $payments->keepPending($payment, $event['payload'] ?? []);
                }

                $payment->refresh();
            }
        }

        return view('parent.payments.return', compact('payment'));
    }

    private function ensureOwner(Request $request, Payment $payment): void
    {
        abort_unless($payment->registration->child->parent_id === $request->user()->id, 403);
    }
}
