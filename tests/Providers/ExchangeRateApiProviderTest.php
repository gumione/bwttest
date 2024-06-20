<?php

namespace CommissionApp\Tests\Providers;

use CommissionApp\Providers\ExchangeRateApiProvider;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

class ExchangeRateApiProviderTest extends TestCase
{
    private $client;
    private $logger;
    private $apiUrl = 'http://api.exchangeratesapi.io/v1';
    private $apiKey = '295f5fd26801c3cbc78bdb6f4eec3605';

    protected function setUp(): void
    {
        $this->client = new Client();
        $this->logger = new NullLogger();
    }

    public function testGetExchangeRate()
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode(['rates' => ['USD' => 1.074458]]))
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $exchangeRateProvider = new ExchangeRateApiProvider($client, $this->logger, $this->apiUrl, $this->apiKey);
        $rate = $exchangeRateProvider->getExchangeRate('USD');

        $this->assertEquals(1.074458, $rate);
    }

    public function testGetExchangeRateReturnsOneForEur()
    {
        $exchangeRateProvider = new ExchangeRateApiProvider($this->client, $this->logger, $this->apiUrl, $this->apiKey);
        $rate = $exchangeRateProvider->getExchangeRate('EUR');

        $this->assertEquals(1.0, $rate);
    }

    public function testGetExchangeRateThrowsExceptionOnError()
    {
        $mock = new MockHandler([
            new Response(500, [])
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $this->expectException(\LogicException::class);

        $exchangeRateProvider = new ExchangeRateApiProvider($client, $this->logger, $this->apiUrl, $this->apiKey);
        $exchangeRateProvider->getExchangeRate('USD');
    }
}
