<?php

namespace App\Console\Commands;

use App\Models\Payment;
use App\Models\Registration;
use Illuminate\Console\Command;

class GenerateMonthlyPayments extends Command
{
    protected $signature = 'academy:generate-monthly-payments';

    protected $description = 'Crée l’échéance mensuelle des inscriptions validées';

    public function handle(): int
    {
        $month = now()->format('Y-m');
        $created = 0;

        Registration::query()
            ->where('season', config('academy.season'))
            ->where('status', Registration::APPROVED)
            ->whereHas('trainingGroup', fn ($query) => $query->where('price', '>', 0))
            ->with('trainingGroup')
            ->chunkById(100, function ($registrations) use ($month, &$created) {
                foreach ($registrations as $registration) {
                    $payment = Payment::firstOrCreate(
                        [
                            'registration_id' => $registration->id,
                            'billing_month' => $month,
                        ],
                        [
                            'provider' => config('payments.driver'),
                            'amount' => $registration->trainingGroup->price,
                            'currency' => config('payments.currency'),
                        ],
                    );

                    if ($payment->wasRecentlyCreated) {
                        $created++;
                    }
                }
            });

        $this->info("$created échéance(s) créée(s) pour $month.");

        return self::SUCCESS;
    }
}
