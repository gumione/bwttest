<?php

namespace CommissionApp\Providers;

use CommissionApp\Interfaces\BinProviderInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Log\LoggerInterface;
use LogicException;

/**
 * Class BinListBinProvider
 * @package CommissionApp\Providers
 */
class BinListBinProvider implements BinProviderInterface
{
    public function __construct(
        private readonly Client $client,
        private readonly LoggerInterface $logger,
        private readonly string $binListUrl
    ) {}

    /**
     * Get BIN data.
     *
     * @param string $bin
     * @return object
     * @throws LogicException
     */
    public function getBinData(string $bin): object
    {
        try {
            $response = $this->client->get($this->binListUrl . $bin);
            $data = $response->getBody()->getContents();
            $this->logger->info('Received BIN data', ['data' => $data]);
            return json_decode($data);
        } catch (RequestException $e) {
            $this->logger->error('Error fetching BIN data', ['exception' => $e]);
            throw new LogicException('Error fetching BIN data');
        }
    }
}
