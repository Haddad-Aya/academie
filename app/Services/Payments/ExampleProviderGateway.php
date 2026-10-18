<?php

namespace App\Services\Payments;

use App\Models\Payment;
use Illuminate\Http\Request;
use LogicException;

/**
 * MODÈLE À COMPLÉTER pour le prestataire choisi (Paymee, Konnect, Flouci, banque...).
 *
 * Aucun appel réel n'est fait ici : les adresses d'API, les champs et la façon de signer
 * les webhooks sont propres à chaque prestataire. Recopier la documentation officielle.
 *
 * Règles à respecter quelle que soit l'API :
 *  - les clés API restent dans .env et ne sont jamais envoyées au navigateur ;
 *  - le paiement n'est considéré "payé" que sur réception d'un webhook authentique,
 *    jamais sur la simple page de retour du parent ;
 *  - comparer le montant reçu au montant attendu.
 */
class ExampleProviderGateway implements PaymentGateway
{
    public function name(): string
    {
        return 'example';
    }

    public function checkoutUrl(Payment $payment): string
    {
        // 1. Appeler l'API du prestataire (Http::withToken(config(...))->post(...)) avec :
        //    montant, devise, référence interne ($payment->id), URL de retour, URL du webhook
        //    (route('webhooks.payments')).
        // 2. Enregistrer la référence renvoyée : $payment->provider_ref = '...'; $payment->save();
        // 3. Renvoyer l'URL de la page de paiement hébergée par le prestataire.
        throw new LogicException('ExampleProviderGateway : à implémenter avec la documentation du prestataire.');
    }

    public function parseWebhook(Request $request): ?array
    {
        $secret = (string) config('payments.webhook_secret');
        $given = (string) $request->header('X-Signature', ''); // nom d'en-tête propre au prestataire

        // Schéma de signature courant (HMAC-SHA256 du corps brut) : à adapter à la documentation.
        $expected = hash_hmac('sha256', $request->getContent(), $secret);

        if ($secret === '' || ! hash_equals($expected, $given)) {
            return null; // signature absente ou fausse : on ignore
        }

        // Après signature valide, extraire du corps : référence, statut, montant.
        // return ['provider_ref' => ..., 'status' => 'paid', 'amount' => ..., 'payload' => $request->all()];
        throw new LogicException('ExampleProviderGateway : mapping du webhook à implémenter.');
    }
}
