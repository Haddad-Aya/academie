<?php

namespace App\Providers;

use App\Services\Payments\KonnectGateway;
use App\Services\Payments\ManualGateway;
use App\Services\Payments\PaymentGateway;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(PaymentGateway::class, function () {
            return match (config('payments.driver')) {
                'konnect' => new KonnectGateway(),
                default => new ManualGateway(),
            };
        });
    }
}
