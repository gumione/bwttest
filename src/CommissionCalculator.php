<?php

namespace CommissionApp;

use CommissionApp\Interfaces\BinProviderInterface;
use CommissionApp\Interfaces\ExchangeRateProviderInterface;
use CommissionApp\Interfaces\EuCountryCheckerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class CommissionCalculator
 * @package CommissionApp
 */
class CommissionCalculator
{
    
    private const EU_COMMISSION_RATE = 0.01;
    private const NON_EU_COMMISSION_RATE = 0.02;
    
    public function __construct(
        private readonly BinProviderInterface $binProvider,
        private readonly ExchangeRateProviderInterface $exchangeRateProvider,
        private readonly EuCountryCheckerInterface $euCountryChecker
    ) {}

    /**
     * Calculate commissions for transactions from a stream or file path.
     *
     * @param resource|string $input
     */
    public function calculateCommissions($input): void
    {
        if (is_string($input)) {
            $input = fopen($input, 'r');
        }

        while (($row = fgets($input)) !== false) {
            $transaction = json_decode($row, true);
            if (!$this->validateTransaction($transaction)) {
                echo "Invalid transaction data\n";
                continue;
            }
            try {
                $commission = $this->calculateCommission($transaction);
                echo number_format($commission, 2, '.', '') . "\n";
            } catch (\RuntimeException $e) {
                echo "Error processing transaction for BIN: " . $transaction['bin'] . " - " . $e->getMessage() . "\n";
            }
        }

        if (is_resource($input)) {
            fclose($input);
        }
    }
    
    /**
     * Calculate the commission for a single transaction.
     *
     * @param array $transaction
     * @return float
     * @throws \LogicException
     */
    public function calculateCommission(array $transaction): float
    {
        $bin = $transaction['bin'];
        $amount = $transaction['amount'];
        $currency = $transaction['currency'];

        try {
            $binData = $this->getBinData($bin);
        } catch (\RuntimeException $e) {
            throw new \RuntimeException($e->getMessage());
        }

        if (!isset($binData->country->alpha2)) {
            throw new \RuntimeException("Country data not found for BIN: $bin");
        }

        $countryCode = $binData->country->alpha2;
        $isEu = $this->euCountryChecker->isEu($countryCode);

        $rate = $this->getExchangeRate($currency);
        $amountInEur = $currency === 'EUR' ? $amount : $amount / $rate;

        $commissionRate = $isEu ? self::EU_COMMISSION_RATE : self::NON_EU_COMMISSION_RATE;
        $commission = $amountInEur * $commissionRate;

        return $this->roundUpToNextCent($commission);
    }

    /**
     * Rounds commission amount ceil to next cent.
     *
     * @param float $amount
     * @return float
     */
    private function roundUpToNextCent(float $amount): float
    {
        return ceil($amount * 100) / 100;
    }

    /**
     * Get BIN data from the provider.
     *
     * @param string $bin
     * @return object
     */
    private function getBinData(string $bin): object
    {
        return $this->binProvider->getBinData($bin);
    }

    /**
     * Get exchange rate from the provider.
     *
     * @param string $currency
     * @return float
     */
    private function getExchangeRate(string $currency): float
    {
        return $this->exchangeRateProvider->getExchangeRate($currency);
    }

    /**
     * Validate transaction data.
     *
     * @param array $transaction
     * @return bool
     */
    private function validateTransaction(array $transaction): bool
    {
        return isset($transaction['bin'], $transaction['amount'], $transaction['currency']) &&
               is_string($transaction['bin']) &&
               is_numeric($transaction['amount']) &&
               is_string($transaction['currency']);
    }
}
