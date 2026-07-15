<?php

declare(strict_types=1);

namespace Hypernode\Api\Service;

use GuzzleHttp\Psr7\Response;
use Hypernode\Api\Exception\HypernodeApiClientException;
use Hypernode\Api\Exception\HypernodeApiServerException;
use Hypernode\Api\HypernodeClientTestCase;

class ProductTest extends HypernodeClientTestCase
{
    public function testGetList()
    {
        $products = [
            [
                'code' => 'FALCON_XS_202203',
                'name' => 'Falcon XS',
                'backups_enabled' => true,
                'is_development' => false,
                'varnish_supported' => false,
                'supports_sla' => true,
                'price' => 1234,
                'currency' => 'EUR',
            ],
            [
                'code' => 'FALCON_M_202203',
                'name' => 'Falcon M',
                'backups_enabled' => true,
                'is_development' => false,
                'varnish_supported' => true,
                'supports_sla' => true,
                'price' => 2345,
                'currency' => 'EUR',
            ],
        ];
        $this->responses->append(
            new Response(200, [], json_encode($products)),
        );

        $result = $this->client->product->getList();

        $request = $this->responses->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/v2/product/', $request->getUri());
        $this->assertEquals($products, $result);
    }

    public function testGetListRaisesClientExceptions()
    {
        $badRequestResponse = new Response(400, [], json_encode([
            'non_field_errors' => ['Your request was invalid.']
        ]));
        $this->responses->append($badRequestResponse);

        $this->expectExceptionObject(new HypernodeApiClientException($badRequestResponse));

        $this->client->product->getList();
    }

    public function testGetListRaisesServerExceptions()
    {
        $badRequestResponse = new Response(500, [], json_encode([
            'non_field_errors' => ['Something went wrong processing your request.']
        ]));
        $this->responses->append($badRequestResponse);

        $this->expectExceptionObject(new HypernodeApiServerException($badRequestResponse));

        $this->client->product->getList();
    }
}
