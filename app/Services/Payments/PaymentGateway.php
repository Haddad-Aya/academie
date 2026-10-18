<?php

namespace App\Services\Payments;

use App\Models\Payment;
use Illuminate\Http\Request;

/**
 * Contrat commun à tous les prestataires de paiement.
 * Pour changer de prestataire, on écrit une nouvelle classe qui implémente cette interface
 * et on la déclare dans PaymentServiceProvider : le reste de l'application ne change pas.
 */
interface PaymentGateway
{
    /** Nom enregistré dans payments.provider. */
    public function name(): string;

    /**
     * Prépare le paiement chez le prestataire et renvoie l'URL vers laquelle rediriger le parent.
     * Si le prestataire renvoie une référence de transaction, l'enregistrer dans $payment->provider_ref.
     */
    public function checkoutUrl(Payment $payment): string;

    /**
     * Vérifie l'authenticité d'un webhook (signature) puis renvoie
     * ['provider_ref' => string, 'status' => 'paid'|'failed'|'pending', 'amount' => ?float, 'payload' => array].
     * Renvoie null si la requête n'est pas authentique ou n'est pas comprise.
     */
    public function parseWebhook(Request $request): ?array;
}
