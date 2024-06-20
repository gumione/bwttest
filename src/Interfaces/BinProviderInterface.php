<?php

namespace CommissionApp\Interfaces;

/**
 * Interface BinProviderInterface
 * @package CommissionApp\Interfaces
 */
interface BinProviderInterface
{
    /**
     * Get BIN data.
     *
     * @param string $bin
     * @return object
     */
    public function getBinData(string $bin): object;
}
