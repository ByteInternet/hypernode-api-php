<?php

declare(strict_types=1);

namespace Hypernode\Api\Service;

use GuzzleHttp\Psr7\Response;
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
}
