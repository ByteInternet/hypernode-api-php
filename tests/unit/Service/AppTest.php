<?php

declare(strict_types=1);

namespace Hypernode\Api\Service;

use GuzzleHttp\Psr7\Response;
use Hypernode\Api\Exception\HypernodeApiClientException;
use Hypernode\Api\Exception\HypernodeApiServerException;
use Hypernode\Api\HypernodeClientTestCase;

class AppTest extends HypernodeClientTestCase
{
    public function testGetList()
    {
        $this->responses->append(
            new Response(200, [], json_encode([
                'next' => null,
                'results' => [
                    [
                        'name' => 'johndoe',
                        'type' => 'persistent',
                        'product' => 'FALCON_M_202203',
                    ],
                    [
                        'name' => 'tdgroot',
                        'type' => 'persistent',
                        'product' => 'FALCON_M_202203',
                    ],
                ]
            ])),
        );

        $result = $this->client->app->getList();

        $this->assertCount(2, $result);
        $this->assertContainsOnlyInstancesOf(\Hypernode\Api\Resource\App::class, $result);
        $this->assertEquals('johndoe', $result[0]->name);
        $this->assertEquals('tdgroot', $result[1]->name);

        $request = $this->responses->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/v2/app/', $request->getUri());
    }

    public function testGetListAcceptsParams()
    {
        $this->responses->append(
            new Response(200, [], json_encode([
                'next' => null,
                'results' => [
                    [
                        'name' => 'johndoe',
                        'type' => 'persistent',
                        'parent' => null,
                        'product' => 'FALCON_M_202203',
                    ],
                    [
                        'name' => 'tdgroot',
                        'type' => 'persistent',
                        'parent' => 'johndoe',
                        'product' => 'FALCON_M_202203',
                    ],
                ]
            ])),
        );

        $result = $this->client->app->getList(['parent' => 'johndoe']);

        $this->assertCount(2, $result);
        $this->assertContainsOnlyInstancesOf(\Hypernode\Api\Resource\App::class, $result);
        $this->assertEquals('johndoe', $result[0]->name);
        $this->assertEquals('tdgroot', $result[1]->name);
        $this->assertEquals('johndoe', $result[1]->parent);

        $request = $this->responses->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/v2/app/?parent=johndoe', $request->getUri());
    }

    public function testGetListPaginates()
    {
        $this->responses->append(
            new Response(200, [], json_encode([
                'next' => 'https://api.hypernode.com/v2/app/?limit=1&offset=1',
                'results' => [
                    [
                        'name' => 'johndoe',
                        'type' => 'persistent',
                        'product' => 'FALCON_M_202203',
                    ],
                ]
            ])),
            new Response(200, [], json_encode([
                'next' => null,
                'results' => [
                    [
                        'name' => 'tdgroot',
                        'type' => 'persistent',
                        'product' => 'FALCON_M_202203',
                    ],
                ]
            ])),
        );

        $result = $this->client->app->getList();

        $this->assertCount(2, $result);
        $this->assertContainsOnlyInstancesOf(\Hypernode\Api\Resource\App::class, $result);
        $this->assertEquals('johndoe', $result[0]->name);
        $this->assertEquals('tdgroot', $result[1]->name);

        $request = $this->responses->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('https://api.hypernode.com/v2/app/?limit=1&offset=1', $request->getUri());
    }

    public function testGetBlockAttackDescriptions()
    {
        $descriptions = [
            'BlockSqliBruteForce' => 'Attempts to deploy NGINX rules to block suspected (blind) SQL injection attack',
            'BlockChinaBruteForce' => 'Attempts to deploy NGINX rules to block brute force attacks from China',
        ];
        $this->responses->append(
            new Response(200, [], json_encode($descriptions)),
        );

        $result = $this->client->app->getBlockAttackDescriptions();

        $request = $this->responses->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/v2/app/block_attack_descriptions/', $request->getUri());
        $this->assertEquals($descriptions, $result);
    }

    public function testGetBlockAttackDescriptionsRaisesClientExceptions()
    {
        $badRequestResponse = new Response(400, [], json_encode([
            'non_field_errors' => ['Your request was invalid.']
        ]));
        $this->responses->append($badRequestResponse);

        $this->expectExceptionObject(new HypernodeApiClientException($badRequestResponse));

        $this->client->app->getBlockAttackDescriptions();
    }

    public function testGetBlockAttackDescriptionsRaisesServerExceptions()
    {
        $badRequestResponse = new Response(500, [], json_encode([
            'non_field_errors' => ['Something went wrong processing your request.']
        ]));
        $this->responses->append($badRequestResponse);

        $this->expectExceptionObject(new HypernodeApiServerException($badRequestResponse));

        $this->client->app->getBlockAttackDescriptions();
    }

    public function testGetEavDescriptions()
    {
        $descriptions = [
            'php_version' => 'The PHP version of the Hypernode',
            'nodejs_version' => 'The Node.js version of the Hypernode',
        ];
        $this->responses->append(
            new Response(200, [], json_encode($descriptions)),
        );

        $result = $this->client->app->getEavDescriptions();

        $request = $this->responses->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/v2/app/eav_descriptions/', $request->getUri());
        $this->assertEquals($descriptions, $result);
    }

    public function testGetEavDescriptionsRaisesClientExceptions()
    {
        $badRequestResponse = new Response(400, [], json_encode([
            'non_field_errors' => ['Your request was invalid.']
        ]));
        $this->responses->append($badRequestResponse);

        $this->expectExceptionObject(new HypernodeApiClientException($badRequestResponse));

        $this->client->app->getEavDescriptions();
    }

    public function testGetEavDescriptionsRaisesServerExceptions()
    {
        $badRequestResponse = new Response(500, [], json_encode([
            'non_field_errors' => ['Something went wrong processing your request.']
        ]));
        $this->responses->append($badRequestResponse);

        $this->expectExceptionObject(new HypernodeApiServerException($badRequestResponse));

        $this->client->app->getEavDescriptions();
    }
}
