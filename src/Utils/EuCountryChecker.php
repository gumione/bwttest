<?php

namespace CommissionApp\Utils;

use CommissionApp\Interfaces\EuCountryCheckerInterface;

/**
 * Class EuCountryChecker
 * @package CommissionApp\Utils
 */
class EuCountryChecker implements EuCountryCheckerInterface
{
    public function __construct(
        private readonly array $euCountries
    ) {}

    /**
     * Check if country is in EU.
     *
     * @param string $countryCode
     * @return bool
     */
    public function isEu(string $countryCode): bool
    {
        return in_array($countryCode, $this->euCountries);
    }
}
