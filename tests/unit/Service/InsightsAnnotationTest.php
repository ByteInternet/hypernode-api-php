<?php

declare(strict_types=1);

namespace Hypernode\Api\Service;

use GuzzleHttp\Psr7\Response;
use Hypernode\Api\Exception\HypernodeApiClientException;
use Hypernode\Api\Exception\HypernodeApiServerException;
use Hypernode\Api\HypernodeClientTestCase;

class InsightsAnnotationTest extends HypernodeClientTestCase
{
    public function testGetList()
    {
        $annotations = [
            [
                'name' => 'Deployed release 1.2.3',
                'x_axis' => 1756384957,
                'app_name' => 'johndoe',
                'metrics_list' => ['memory_usage'],
                'metadata' => [],
            ],
        ];
        $this->responses->append(
            new Response(200, [], json_encode([
                'count' => 1,
                'next' => null,
                'previous' => null,
                'results' => $annotations,
            ])),
        );

        $result = $this->client->insightsAnnotation->getList();

        $request = $this->responses->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/v2/insights-annotation/', $request->getUri());
        $this->assertEquals($annotations, $result);
    }

    public function testGetListAcceptsParams()
    {
        $this->responses->append(
            new Response(200, [], json_encode([
                'count' => 0,
                'next' => null,
                'previous' => null,
                'results' => [],
            ])),
        );

        $result = $this->client->insightsAnnotation->getList(['limit' => 10, 'offset' => 20]);

        $request = $this->responses->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/v2/insights-annotation/?limit=10&offset=20', $request->getUri());
        $this->assertEquals([], $result);
    }

    public function testGetListPaginates()
    {
        $this->responses->append(
            new Response(200, [], json_encode([
                'count' => 2,
                'next' => 'https://api.hypernode.com/v2/insights-annotation/?limit=1&offset=1',
                'previous' => null,
                'results' => [
                    [
                        'name' => 'Deployed release 1.2.3',
                        'x_axis' => 1756384957,
                        'app_name' => 'johndoe',
                    ],
                ],
            ])),
            new Response(200, [], json_encode([
                'count' => 2,
                'next' => null,
                'previous' => null,
                'results' => [
                    [
                        'name' => 'Deployed release 1.2.4',
                        'x_axis' => 1756395957,
                        'app_name' => 'johndoe',
                    ],
                ],
            ])),
        );

        $result = $this->client->insightsAnnotation->getList();

        $this->assertCount(2, $result);
        $this->assertEquals('Deployed release 1.2.3', $result[0]['name']);
        $this->assertEquals('Deployed release 1.2.4', $result[1]['name']);

        $request = $this->responses->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals(
            'https://api.hypernode.com/v2/insights-annotation/?limit=1&offset=1',
            $request->getUri()
        );
    }

    public function testGetListRaisesClientExceptions()
    {
        $badRequestResponse = new Response(400, [], json_encode([
            'non_field_errors' => ['Your request was invalid.']
        ]));
        $this->responses->append($badRequestResponse);

        $this->expectExceptionObject(new HypernodeApiClientException($badRequestResponse));

        $this->client->insightsAnnotation->getList();
    }

    public function testGetListRaisesServerExceptions()
    {
        $badRequestResponse = new Response(500, [], json_encode([
            'non_field_errors' => ['Something went wrong processing your request.']
        ]));
        $this->responses->append($badRequestResponse);

        $this->expectExceptionObject(new HypernodeApiServerException($badRequestResponse));

        $this->client->insightsAnnotation->getList();
    }

    public function testCreate()
    {
        $annotationData = [
            'name' => 'Deployed release 1.2.3',
            'x_axis' => 1756384957,
            'app' => 'johndoe',
            'metrics' => 'memory_usage',
        ];
        $createdAnnotation = array_merge($annotationData, [
            'id' => 123,
            'created' => '2026-07-15T12:00:00Z',
            'modified' => '2026-07-15T12:00:00Z',
        ]);
        $this->responses->append(
            new Response(201, [], json_encode($createdAnnotation)),
        );

        $result = $this->client->insightsAnnotation->create($annotationData);

        $request = $this->responses->getLastRequest();
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('/v2/insights-annotation/create/', $request->getUri());
        $this->assertJson((string)$request->getBody());
        $this->assertEquals($annotationData, json_decode((string)$request->getBody(), true));
        $this->assertEquals($createdAnnotation, $result);
    }

    public function testCreateRaisesClientExceptions()
    {
        $badRequestResponse = new Response(400, [], json_encode([
            'non_field_errors' => ['Your request was invalid.']
        ]));
        $this->responses->append($badRequestResponse);

        $this->expectExceptionObject(new HypernodeApiClientException($badRequestResponse));

        $this->client->insightsAnnotation->create(['name' => 'Deployed release 1.2.3']);
    }

    public function testCreateRaisesServerExceptions()
    {
        $badRequestResponse = new Response(500, [], json_encode([
            'non_field_errors' => ['Something went wrong processing your request.']
        ]));
        $this->responses->append($badRequestResponse);

        $this->expectExceptionObject(new HypernodeApiServerException($badRequestResponse));

        $this->client->insightsAnnotation->create(['name' => 'Deployed release 1.2.3']);
    }
}
