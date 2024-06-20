<?php

namespace CommissionApp\Providers;

use CommissionApp\Interfaces\ExchangeRateProviderInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Log\LoggerInterface;
use LogicException;

/**
 * Class ExchangeRateApiProvider
 * @package CommissionApp\Providers
 */
class ExchangeRateApiProvider implements ExchangeRateProviderInterface
{
    public function __construct(
        private readonly Client $client,
        private readonly LoggerInterface $logger,
        private readonly string $exchangeRateUrl,
        private readonly string $apiKey
    ) {}

    /**
     * Get exchange rate.
     *
     * @param string $currency
     * @return float
     * @throws LogicException
     */
    public function getExchangeRate(string $currency): float
    {
        try {
            $response = $this->client->get($this->exchangeRateUrl . '/latest?access_key=' . $this->apiKey);
            $data = $response->getBody()->getContents();
            $parsedData = json_decode($data);

            if ($currency === 'EUR') {
                return 1.0;
            }

            if (isset($parsedData->error)) {
                throw new LogicException("API Error: " . $parsedData->error->info);
            }

            return $parsedData->rates->{$currency} ?? 1.0;
        } catch (RequestException $e) {
            $this->logger->error('Error fetching exchange rate data', ['exception' => $e]);
            throw new LogicException('Error fetching exchange rate data');
        }
    }
}
