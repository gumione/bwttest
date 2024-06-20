<?php

require 'vendor/autoload.php';

use GuzzleHttp\Client;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
//use CommissionApp\Providers\BinListBinProvider;
use CommissionApp\Providers\MockBinProvider;
use CommissionApp\CommissionCalculator;
use CommissionApp\Utils\EuCountryChecker;
//use CommissionApp\Providers\ExchangeRateApiProvider;
use CommissionApp\Providers\MockExchangeRateProvider;

$config = require 'config.php';

$client = new Client();
$logger = new Logger('app');
$logger->pushHandler(new StreamHandler($config['log_file'], Logger::DEBUG));

$binProvider = new MockBinProvider();
/* Here we are using MockBinProvider 'cause of current API limits on binlist, should be something like
    $binProvider = new BinListBinProvider($client, $logger, $config['binlist_api_url']);
   in real case scenario
*/
$exchangeRateProvider = new MockExchangeRateProvider();
/* same here:
 *  $exchangeRateProvider = new ExchangeRateApiProvider($client, $logger, $config['exchange_rate_api_url'], $config['exchange_rate_api_key']);
 */
$euCountryChecker = new EuCountryChecker($config['eu_countries']);

$calculator = new CommissionCalculator($binProvider, $exchangeRateProvider, $euCountryChecker);
$calculator->calculateCommissions($argv[1]);

