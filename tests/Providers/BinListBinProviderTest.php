<?php

namespace CommissionApp\Tests\Providers;

use CommissionApp\Providers\BinListBinProvider;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

class BinListBinProviderTest extends TestCase
{
    private $client;
    private $logger;
    private $apiUrl = 'https://lookup.binlist.net/';

    protected function setUp(): void
    {
        $this->client = new Client();
        $this->logger = new NullLogger();
    }

    public function testGetBinData()
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode(['country' => ['alpha2' => 'DE']]))
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $binProvider = new BinListBinProvider($client, $this->logger, $this->apiUrl);
        $binData = $binProvider->getBinData('45717360');

        $this->assertEquals('DE', $binData->country->alpha2);
    }

    public function testGetBinDataThrowsExceptionOnError()
    {
        $mock = new MockHandler([
            new Response(500, [])
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $this->expectException(\LogicException::class);

        $binProvider = new BinListBinProvider($client, $this->logger, $this->apiUrl);
        $binProvider->getBinData('45717360');
    }
}
