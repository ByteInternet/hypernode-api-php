<?php

declare(strict_types=1);

namespace Hypernode\Api\Service;

use GuzzleHttp\Psr7\Response;
use Hypernode\Api\Exception\HypernodeApiClientException;
use Hypernode\Api\Exception\HypernodeApiServerException;
use Hypernode\Api\HypernodeClientTestCase;

class EphemeralAppTest extends HypernodeClientTestCase
{
    public function testCreateEphemeralApp()
    {
        $this->responses->append(
            new Response(200, [], json_encode([
                'name' => 'johndoe-eph123456',
                'parent' => 'johndoe',
                'type' => 'ephemeral',
            ])),
        );

        $ephemeralAppName = $this->client->ephemeralApp->create('johndoe');

        $request = $this->responses->getLastRequest();
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('/v2/app/johndoe/ephemeral/', $request->getUri());
        $this->assertEquals('johndoe-eph123456', $ephemeralAppName);
    }

    public function testCreateEphemeralAppRaisesClientExceptions()
    {
        $badRequestResponse = new Response(400, [], json_encode([
            'non_field_errors' => ['Your request was invalid.']
        ]));
        $this->responses->append($badRequestResponse);

        $this->expectExceptionObject(new HypernodeApiClientException($badRequestResponse));

        $this->client->ephemeralApp->create('johndoe');
    }

    public function testCreateEphemeralAppRaisesServerExceptions()
    {
        $badRequestResponse = new Response(500, [], json_encode([
            'non_field_errors' => ['Something went wrong processing your request.']
        ]));
        $this->responses->append($badRequestResponse);

        $this->expectExceptionObject(new HypernodeApiServerException($badRequestResponse));

        $this->client->ephemeralApp->create('johndoe');
    }

    public function testCancelEphemeralApp()
    {
        $this->responses->append(
            new Response(204, [], null),
        );

        $this->client->ephemeralApp->cancel('johndoe-eph123456');

        $request = $this->responses->getLastRequest();
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('/v2/app/johndoe-eph123456/cancel/', $request->getUri());
    }

    public function testCancelEphemeralAppRaisesClientExceptions()
    {
        $badRequestResponse = new Response(400, [], json_encode([
            'non_field_errors' => ['Your request was invalid.']
        ]));
        $this->responses->append($badRequestResponse);

        $this->expectExceptionObject(new HypernodeApiClientException($badRequestResponse));

        $this->client->ephemeralApp->cancel('johndoe');
    }

    public function testCancelEphemeralAppRaisesServerExceptions()
    {
        $badRequestResponse = new Response(500, [], json_encode([
            'non_field_errors' => ['Something went wrong processing your request.']
        ]));
        $this->responses->append($badRequestResponse);

        $this->expectExceptionObject(new HypernodeApiServerException($badRequestResponse));

        $this->client->ephemeralApp->cancel('johndoe');
    }
}
