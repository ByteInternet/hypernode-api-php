<?php

declare(strict_types=1);

namespace Hypernode\Api\Service;

use GuzzleHttp\Psr7\Response;
use Hypernode\Api\Exception\HypernodeApiClientException;
use Hypernode\Api\Exception\HypernodeApiServerException;
use Hypernode\Api\HypernodeClientTestCase;

class WhitelistTest extends HypernodeClientTestCase
{
    public function testGetList()
    {
        $entries = [
            [
                'id' => 1234,
                'created' => '2026-07-14T14:49:06Z',
                'type' => 'waf',
                'description' => 'Office IP',
                'ip' => '1.2.3.4',
                'app' => 'johndoe',
            ],
        ];
        $this->responses->append(
            new Response(200, [], json_encode($entries)),
        );

        $result = $this->client->whitelist->getList('johndoe');

        $request = $this->responses->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/v2/whitelist/johndoe/', $request->getUri());
        $this->assertEquals($entries, $result);
    }

    public function testGetListAcceptsParams()
    {
        $this->responses->append(
            new Response(200, [], json_encode([])),
        );

        $result = $this->client->whitelist->getList('johndoe', ['type' => 'waf']);

        $request = $this->responses->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/v2/whitelist/johndoe/?type=waf', $request->getUri());
        $this->assertEquals([], $result);
    }

    public function testGetListRaisesClientExceptions()
    {
        $badRequestResponse = new Response(400, [], json_encode([
            'non_field_errors' => ['Your request was invalid.']
        ]));
        $this->responses->append($badRequestResponse);

        $this->expectExceptionObject(new HypernodeApiClientException($badRequestResponse));

        $this->client->whitelist->getList('johndoe');
    }

    public function testGetListRaisesServerExceptions()
    {
        $badRequestResponse = new Response(500, [], json_encode([
            'non_field_errors' => ['Something went wrong processing your request.']
        ]));
        $this->responses->append($badRequestResponse);

        $this->expectExceptionObject(new HypernodeApiServerException($badRequestResponse));

        $this->client->whitelist->getList('johndoe');
    }

    public function testCreate()
    {
        $entryData = [
            'ip' => '1.2.3.4',
            'type' => 'waf',
            'description' => 'Office IP',
        ];
        $createdEntry = array_merge($entryData, [
            'id' => 1234,
            'created' => '2026-07-15T12:00:00Z',
            'app' => 'johndoe',
        ]);
        $this->responses->append(
            new Response(200, [], json_encode($createdEntry)),
        );

        $result = $this->client->whitelist->create('johndoe', $entryData);

        $request = $this->responses->getLastRequest();
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('/v2/whitelist/johndoe/', $request->getUri());
        $this->assertJson((string)$request->getBody());
        $this->assertEquals($entryData, json_decode((string)$request->getBody(), true));
        $this->assertEquals($createdEntry, $result);
    }

    public function testCreateRaisesClientExceptions()
    {
        $badRequestResponse = new Response(400, [], json_encode([
            'non_field_errors' => ['Your request was invalid.']
        ]));
        $this->responses->append($badRequestResponse);

        $this->expectExceptionObject(new HypernodeApiClientException($badRequestResponse));

        $this->client->whitelist->create('johndoe', ['ip' => '1.2.3.4']);
    }

    public function testCreateRaisesServerExceptions()
    {
        $badRequestResponse = new Response(500, [], json_encode([
            'non_field_errors' => ['Something went wrong processing your request.']
        ]));
        $this->responses->append($badRequestResponse);

        $this->expectExceptionObject(new HypernodeApiServerException($badRequestResponse));

        $this->client->whitelist->create('johndoe', ['ip' => '1.2.3.4']);
    }

    public function testDelete()
    {
        $this->responses->append(
            new Response(204, [], null),
        );

        $entryData = ['ip' => '1.2.3.4', 'type' => 'waf'];
        $this->client->whitelist->delete('johndoe', $entryData);

        $request = $this->responses->getLastRequest();
        $this->assertEquals('DELETE', $request->getMethod());
        $this->assertEquals('/v2/whitelist/johndoe/', $request->getUri());
        $this->assertJson((string)$request->getBody());
        $this->assertEquals($entryData, json_decode((string)$request->getBody(), true));
    }

    public function testDeleteRaisesClientExceptions()
    {
        $badRequestResponse = new Response(400, [], json_encode([
            'non_field_errors' => ['Your request was invalid.']
        ]));
        $this->responses->append($badRequestResponse);

        $this->expectExceptionObject(new HypernodeApiClientException($badRequestResponse));

        $this->client->whitelist->delete('johndoe', ['ip' => '1.2.3.4']);
    }

    public function testDeleteRaisesServerExceptions()
    {
        $badRequestResponse = new Response(500, [], json_encode([
            'non_field_errors' => ['Something went wrong processing your request.']
        ]));
        $this->responses->append($badRequestResponse);

        $this->expectExceptionObject(new HypernodeApiServerException($badRequestResponse));

        $this->client->whitelist->delete('johndoe', ['ip' => '1.2.3.4']);
    }
}
