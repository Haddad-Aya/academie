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
        $months = collect(range(1, 12))->map(function (int $month) use ($year) {
            $key = sprintf('%04d-%02d', $year, $month);
            $monthStart = Carbon::create($year, $month, 1)->startOfMonth();
            $nextMonth = $monthStart->copy()->addMonth();
            $totals = Payment::query()
                ->where(function ($query) use ($key, $monthStart, $nextMonth) {
                    $query->where('billing_month', $key)
                        ->orWhere(function ($legacy) use ($monthStart, $nextMonth) {
                            $legacy->whereNull('billing_month')
                                ->where('created_at', '>=', $monthStart)
                                ->where('created_at', '<', $nextMonth);
                        });
                })
                ->selectRaw('COALESCE(SUM(amount), 0) AS expected')
                ->selectRaw('COALESCE(SUM(CASE WHEN status = ? THEN amount ELSE 0 END), 0) AS collected', [Payment::PAID])
                ->selectRaw('COALESCE(SUM(CASE WHEN status = ? AND payment_method = ? THEN amount ELSE 0 END), 0) AS onsite', [Payment::PAID, Payment::METHOD_ONSITE])
                ->selectRaw('COALESCE(SUM(CASE WHEN status = ? THEN 1 ELSE 0 END), 0) AS paid_count', [Payment::PAID])
                ->selectRaw('COALESCE(SUM(CASE WHEN status = ? THEN 1 ELSE 0 END), 0) AS pending_count', [Payment::PENDING])
                ->selectRaw('COALESCE(SUM(CASE WHEN status = ? THEN 1 ELSE 0 END), 0) AS failed_count', [Payment::FAILED])
                ->first();

            return [
                'key' => $key,
                'label' => Carbon::createFromFormat('!Y-m', $key)->locale('fr')->translatedFormat('F Y'),
                'expected' => (float) $totals->expected,
                'collected' => (float) $totals->collected,
                'onsite' => (float) $totals->onsite,
                'paid_count' => (int) $totals->paid_count,
                'pending_count' => (int) $totals->pending_count,
                'failed_count' => (int) $totals->failed_count,
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
