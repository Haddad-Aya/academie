<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\Payments\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Carbon;

/** Suivi des paiements et encaissement manuel. Vue : admin/payments/index.blade.php */
class PaymentController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');

        $payments = Payment::with('registration.child.parent', 'registration.trainingGroup')
            ->when(in_array($status, [Payment::PENDING, Payment::PAID, Payment::FAILED], true),
                fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('admin.payments.index', compact('payments', 'status'));
    }

    public function statistics(Request $request): View
    {
        $validated = $request->validate(['year' => ['nullable', 'integer', 'min:2020', 'max:2100']]);
        $year = (int) ($validated['year'] ?? now()->year);
        $from = sprintf('%04d-01', $year);
        $to = sprintf('%04d-12', $year);
        $payments = Payment::query()
            ->where(function ($query) use ($from, $to, $year) {
                $query->whereBetween('billing_month', [$from, $to])
                    ->orWhere(function ($legacy) use ($year) {
                        $legacy->whereNull('billing_month')->whereYear('created_at', $year);
                    });
            })
            ->get(['billing_month', 'created_at', 'amount', 'status', 'payment_method', 'currency']);

        $months = collect(range(1, 12))->map(function (int $month) use ($payments, $year) {
            $key = sprintf('%04d-%02d', $year, $month);
            $items = $payments->filter(fn (Payment $payment) =>
                ($payment->billing_month ?: $payment->created_at?->format('Y-m')) === $key);
            $paid = $items->where('status', Payment::PAID);

            return [
                'key' => $key,
                'label' => Carbon::createFromFormat('!Y-m', $key)->locale('fr')->translatedFormat('F Y'),
                'expected' => $items->sum(fn ($payment) => (float) $payment->amount),
                'collected' => $paid->sum(fn ($payment) => (float) $payment->amount),
                'card' => $paid->where('payment_method', Payment::METHOD_CARD)->sum(fn ($payment) => (float) $payment->amount),
                'onsite' => $paid->where('payment_method', Payment::METHOD_ONSITE)->sum(fn ($payment) => (float) $payment->amount),
                'paid_count' => $paid->count(),
                'pending_count' => $items->where('status', Payment::PENDING)->count(),
                'failed_count' => $items->where('status', Payment::FAILED)->count(),
            ];
        });

        return view('admin.payments.statistics', compact('months', 'year'));
    }

    /** Paiement reçu en espèces ou par virement. */
    public function markPaid(Request $request, Payment $payment, PaymentService $service): RedirectResponse
    {
        $payment->payment_method = Payment::METHOD_ONSITE;
        $payment->save();

        $service->markPaid($payment, [
            'method' => $payment->payment_method,
            'validated_by' => $request->user()->id,
        ]);

        return back()->with('status', 'Paiement enregistré.');
    }
}
