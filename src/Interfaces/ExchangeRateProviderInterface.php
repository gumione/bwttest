<?php

namespace CommissionApp\Interfaces;

/**
 * Interface ExchangeRateProviderInterface
 * @package CommissionApp\Interfaces
 */
interface ExchangeRateProviderInterface
{
    /**
     * Get exchange rate.
     *
     * @param string $currency
     * @return float
     */
    public function getExchangeRate(string $currency): float;
}
