<?php

namespace App\Http\Controllers\Guardian;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\Payments\PaymentGateway;
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

        return redirect()->away($gateway->checkoutUrl($payment));
    }

    /** Mode manuel : instructions pour payer sur place ou par virement. */
    public function pending(Request $request, Payment $payment): View
    {
        $this->ensureOwner($request, $payment);

        return view('parent.payments.pending', compact('payment'));
    }

    /**
     * Page où le prestataire renvoie le parent. Elle AFFICHE le statut mais ne le modifie jamais :
     * seule la vérification serveur du webhook (ou l'administrateur) peut passer un paiement à "payé".
     */
    public function returned(Request $request, Payment $payment): View
    {
        $this->ensureOwner($request, $payment);

        return view('parent.payments.return', compact('payment'));
    }

    private function ensureOwner(Request $request, Payment $payment): void
    {
        abort_unless($payment->registration->child->parent_id === $request->user()->id, 403);
    }
}
