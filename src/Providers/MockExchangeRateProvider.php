<?php

namespace CommissionApp\Providers;

use CommissionApp\Interfaces\ExchangeRateProviderInterface;

class MockExchangeRateProvider implements ExchangeRateProviderInterface
{
    private array $rates = [
        'USD' => 1.074458,
        'JPY' => 169.826654,
        'GBP' => 0.844838,
        'UAH' => 43.572347,
        'EUR' => 1
    ];

    public function getExchangeRate(string $currency): float
    {
        return $this->rates[$currency] ?? 1.0;
    }
}
