<?php

namespace App\Services\Payments;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

/** Intégration Konnect : caisse hébergée et validation serveur-à-serveur du webhook. */
class KonnectGateway implements PaymentGateway
{
    public function name(): string
    {
        return 'konnect';
    }

    public function checkoutUrl(Payment $payment): string
    {
        $apiKey = (string) config('payments.konnect.api_key');
        $walletId = (string) config('payments.konnect.wallet_id');

        if ($apiKey === '' || $walletId === '') {
            throw new RuntimeException('La clé API et le portefeuille Konnect doivent être configurés.');
        }

        $parent = $payment->registration->child->parent;
        $name = preg_split('/\s+/', trim((string) $parent->name), 2) ?: [];
        $amount = $this->amountInMillimes($payment->amount);

        if ($amount < 1) {
            throw new RuntimeException('Le montant du paiement Konnect doit être positif.');
        }

        $response = $this->client()->withHeaders(['x-api-key' => $apiKey])->post('payments/init-payment', [
            'receiverWalletId' => $walletId,
            'token' => (string) config('payments.konnect.currency', 'TND'),
            'amount' => $amount,
            'type' => 'immediate',
            'description' => 'Cotisation '.($payment->billing_month ?? '').' - inscription #'.$payment->registration_id,
            'acceptedPaymentMethods' => config('payments.konnect.accepted_payment_methods', ['wallet', 'bank_card', 'e-DINAR']),
            'lifespan' => (int) config('payments.konnect.lifespan', 30),
            'checkoutForm' => true,
            'addPaymentFeesToAmount' => false,
            'firstName' => $name[0] ?? $parent->name,
            'lastName' => $name[1] ?? '',
            'phoneNumber' => $parent->phone,
            'email' => $parent->email,
            'orderId' => (string) $payment->id,
            'webhook' => route('webhooks.konnect'),
            // Ces URL sont encore acceptées par l'API, bien qu'indiquées deprecated dans la doc.
            'successUrl' => route('parent.payments.return', $payment),
            'failUrl' => route('parent.payments.return', $payment),
        ]);

        if (! $response->successful()) {
            Log::warning('Konnect payment initialization failed.', [
                'payment_id' => $payment->id,
                'http_status' => $response->status(),
            ]);
            throw new RuntimeException('Konnect ne peut pas initialiser ce paiement pour le moment.');
        }

        $data = $response->json();
        $payUrl = $data['payUrl'] ?? null;
        $paymentRef = $data['paymentRef'] ?? null;

        if (! is_string($payUrl) || ! is_string($paymentRef) || ! $this->isTrustedCheckoutUrl($payUrl)) {
            Log::warning('Konnect returned an invalid checkout response.', ['payment_id' => $payment->id]);
            throw new RuntimeException('Konnect a retourné une réponse de paiement invalide.');
        }

        $payment->provider = $this->name();
        $payment->provider_ref = $paymentRef;
        $payment->payload = ['konnect' => ['paymentRef' => $paymentRef]];
        $payment->save();

        return $payUrl;
    }

    /** Le webhook Konnect ne transporte qu'une référence : son statut est revérifié via l'API. */
    public function parseWebhook(Request $request): ?array
    {
        $paymentRef = $request->query('payment_ref');
        $apiKey = (string) config('payments.konnect.api_key');

        if (! is_string($paymentRef) || $paymentRef === '' || $apiKey === '') {
            return null;
        }

        $payment = Payment::where('provider', $this->name())
            ->where('provider_ref', $paymentRef)
            ->first();

        // Ne dépense pas un appel API pour une référence qui n'appartient pas à notre application.
        if (! $payment) {
            return null;
        }

        $response = $this->client()->withHeaders(['x-api-key' => $apiKey])
            ->get('payments/'.rawurlencode($paymentRef));

        if (! $response->successful()) {
            Log::warning('Konnect payment status lookup failed.', [
                'payment_id' => $payment->id,
                'http_status' => $response->status(),
            ]);

            return null;
        }

        $details = $response->json('payment');
        if (! is_array($details) || ($details['id'] ?? null) !== $paymentRef) {
            return null;
        }

        $status = ($details['status'] ?? null) === 'completed' ? 'paid' : 'pending';
        $expectedAmount = $this->amountInMillimes($payment->amount);
        $receivedAmount = filter_var($details['amount'] ?? null, FILTER_VALIDATE_INT);
        $reachedAmount = filter_var($details['reachedAmount'] ?? null, FILTER_VALIDATE_INT);
        $currency = $details['token'] ?? null;

        if ($status === 'paid' && (
            $currency !== config('payments.konnect.currency', 'TND')
            || $receivedAmount !== $expectedAmount
            || $reachedAmount === false
            || $reachedAmount < $expectedAmount
        )) {
            Log::warning('Konnect completed payment does not match the expected amount.', [
                'payment_id' => $payment->id,
                'currency' => $currency,
                'expected_millimes' => $expectedAmount,
                'received_millimes' => $receivedAmount,
                'reached_millimes' => $reachedAmount,
            ]);

            return [
                'provider_ref' => $paymentRef,
                'status' => 'failed',
                'payload' => ['status' => $details['status'] ?? null, 'amount_mismatch' => true],
            ];
        }

        return [
            'provider_ref' => $paymentRef,
            'status' => $status,
            'amount' => $status === 'paid' ? $expectedAmount / 1000 : null,
            'payload' => [
                'status' => $details['status'] ?? null,
                'amount' => $details['amount'] ?? null,
                'reachedAmount' => $details['reachedAmount'] ?? null,
                'token' => $currency,
            ],
        ];
    }

    private function client()
    {
        return Http::baseUrl(rtrim((string) config('payments.konnect.api_url'), '/').'/')
            ->acceptJson()
            ->asJson()
            ->timeout(15)
            ->connectTimeout(5);
    }

    private function amountInMillimes(string|float|int $amount): int
    {
        return (int) round((float) $amount * 1000);
    }

    private function isTrustedCheckoutUrl(string $url): bool
    {
        $host = parse_url($url, PHP_URL_HOST);

        return parse_url($url, PHP_URL_SCHEME) === 'https'
            && is_string($host)
            && (str_ends_with($host, '.konnect.network') || $host === 'konnect.network');
    }
}
