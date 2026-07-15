<?php

declare(strict_types=1);

namespace Hypernode\Api\Service;

use GuzzleHttp\Psr7\Response;
use Hypernode\Api\Exception\HypernodeApiClientException;
use Hypernode\Api\Exception\HypernodeApiServerException;
use Hypernode\Api\HypernodeClientTestCase;

class AddonTest extends HypernodeClientTestCase
{
    public function testGetSlas()
    {
        $slas = [
            [
                'id' => 123,
                'code' => 'sla-standard',
                'name' => 'SLA Standard',
                'price' => 2500,
                'currency' => 'EUR',
                'billing_period' => 1,
                'billing_period_unit' => 'month',
            ],
        ];
        $this->responses->append(
            new Response(200, [], json_encode($slas)),
        );

        $result = $this->client->addon->getSlas();

        $request = $this->responses->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/v2/addon/slas/', $request->getUri());
        $this->assertEquals($slas, $result);
    }

    public function testGetSlasRaisesClientExceptions()
    {
        $badRequestResponse = new Response(400, [], json_encode([
            'non_field_errors' => ['Your request was invalid.']
        ]));
        $this->responses->append($badRequestResponse);

        $this->expectExceptionObject(new HypernodeApiClientException($badRequestResponse));

        $this->client->addon->getSlas();
    }

    public function testGetSlasRaisesServerExceptions()
    {
        $badRequestResponse = new Response(500, [], json_encode([
            'non_field_errors' => ['Something went wrong processing your request.']
        ]));
        $this->responses->append($badRequestResponse);

        $this->expectExceptionObject(new HypernodeApiServerException($badRequestResponse));

        $this->client->addon->getSlas();
    }
}
