<?php

namespace CommissionApp\Tests;

use CommissionApp\CommissionCalculator;
use CommissionApp\Providers\MockBinProvider;
use CommissionApp\Providers\MockExchangeRateProvider;
use CommissionApp\Utils\EuCountryChecker;
use PHPUnit\Framework\TestCase;

class CommissionCalculatorTest extends TestCase
{
    private CommissionCalculator $calculator;
    private string $testFilePath;

    protected function setUp(): void
    {
        $euCountries = [
            'AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES', 'FI',
            'FR', 'GR', 'HR', 'HU', 'IE', 'IT', 'LT', 'LU', 'LV', 'MT',
            'NL', 'PO', 'PT', 'RO', 'SE', 'SI', 'SK'
        ];

        $binProvider = new MockBinProvider();
        $exchangeRateProvider = new MockExchangeRateProvider();
        $euCountryChecker = new EuCountryChecker($euCountries);

        $this->calculator = new CommissionCalculator($binProvider, $exchangeRateProvider, $euCountryChecker);

        $this->testFilePath = __DIR__ . '/testInput.txt';
        file_put_contents($this->testFilePath, json_encode(['bin' => '45717360', 'amount' => '100.00', 'currency' => 'EUR']) . "\n");
        file_put_contents($this->testFilePath, json_encode(['bin' => '516793', 'amount' => '50.00', 'currency' => 'USD']) . "\n", FILE_APPEND);
        file_put_contents($this->testFilePath, json_encode(['bin' => '45417360', 'amount' => '10000.00', 'currency' => 'JPY']) . "\n", FILE_APPEND);
        file_put_contents($this->testFilePath, json_encode(['bin' => '41417360', 'amount' => '130.00', 'currency' => 'USD']) . "\n", FILE_APPEND);
        file_put_contents($this->testFilePath, json_encode(['bin' => '4745030', 'amount' => '2000.00', 'currency' => 'GBP']) . "\n", FILE_APPEND);
        file_put_contents($this->testFilePath, json_encode(['bin' => '44411114', 'amount' => '1000.00', 'currency' => 'UAH']) . "\n", FILE_APPEND);
    }

    protected function tearDown(): void
    {
        unlink($this->testFilePath);
    }

    public function testCalculateCommissions()
    {
        ob_start();
        $this->calculator->calculateCommissions($this->testFilePath);
        $output = ob_get_clean();
        $expectedOutput = "1.00\n0.47\nError processing transaction for BIN: 45417360 - BIN data not found for: 45417360\nError processing transaction for BIN: 41417360 - Country data not found for BIN: 41417360\n23.68\n0.46\n";
        $this->assertEquals($expectedOutput, $output);
    }
}
