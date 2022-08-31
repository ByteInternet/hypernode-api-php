<?php

namespace Hypernode\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use Http\Client\Common\HttpMethodsClient;
use Http\Client\Common\HttpMethodsClientInterface;
use Http\Discovery\Psr17FactoryDiscovery;
use PHPUnit\Framework\TestCase;

class HypernodeClientTestCase extends TestCase
{
    protected HttpMethodsClientInterface $api;
    protected MockHandler $responses;
    protected HypernodeClient $client;

    protected function setUp(): void
    {
        $this->responses = new MockHandler();
        $this->api = new HttpMethodsClient(
            new Client(['handler' => HandlerStack::create($this->responses)]),
            Psr17FactoryDiscovery::findRequestFactory(),
            Psr17FactoryDiscovery::findStreamFactory()
        );
        $this->client = new HypernodeClient($this->api);
    }
}