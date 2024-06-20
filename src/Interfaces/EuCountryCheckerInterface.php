<?php

namespace CommissionApp\Interfaces;

/**
 * Interface EuCountryCheckerInterface
 * @package CommissionApp\Interfaces
 */
interface EuCountryCheckerInterface
{
    /**
     * Check if country is in EU.
     *
     * @param string $countryCode
     * @return bool
     */
    public function isEu(string $countryCode): bool;
}
