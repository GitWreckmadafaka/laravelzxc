<?php

namespace App\Services;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;

class PayPalClient
{
    public static function client()
    {
        $environment = config('services.paypal.mode') === 'sandbox'
            ? new SandboxEnvironment(config('services.paypal.client_id'), config('services.paypal.secret'))
            : new ProductionEnvironment(config('services.paypal.client_id'), config('services.paypal.secret'));

        return new PayPalHttpClient($environment);
    }
}
