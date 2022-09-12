<?php

declare(strict_types=1);

namespace Hypernode\Api\Service;

use GuzzleHttp\Psr7\Response;
use Hypernode\Api\HypernodeClientTestCase;
use Hypernode\Api\Resource\Logbook\Flow;

class LogbookTest extends HypernodeClientTestCase
{
    public function testGetListFetchesLogbook()
    {
        $this->responses->append(
            new Response(200, [], json_encode([
                'count' => 2,
                'next' => null,
                'previous' => null,
                'results' => [
                    [
                        'uuid' => 'c130c04f-794e-4f55-b34b-eb8f60472224',
                        'state' => 'running',
                        'name' => 'update_node',
                        'created_at' => '2022-09-12T12:19:15Z',
                        'updated_at' => '2022-09-12T12:20:30Z',
                        'logbook' => 'johndoe',
                    ],
                    [
                        'uuid' => 'e2f39684-3ce0-48d1-acda-13fa064709b5',
                        'state' => 'success',
                        'name' => 'create_backup',
                        'created_at' => '2022-09-12T11:08:10Z',
                        'updated_at' => '2022-09-12T11:08:27Z',
                        'logbook' => 'johndoe',
                    ],
                ]
            ])),
        );

        $flows = $this->client->logbook->getList('johndoe');
        [$flow1, $flow2] = $flows;

        $this->assertCount(2, $flows);

        $this->assertInstanceOf(Flow::class, $flow1);
        $this->assertEquals('running', $flow1->state);
        $this->assertEquals('update_node', $flow1->name);

        $this->assertInstanceOf(Flow::class, $flow2);
        $this->assertEquals('success', $flow2->state);
        $this->assertEquals('create_backup', $flow2->name);
    }

    public function testGetListPagesThroughLogbook()
    {
        $this->responses->append(
            new Response(200, [], json_encode([
                'count' => 1,
                'next' => 'https://api.hypernode.com/logbook/v1/logbooks/johndoe/flows/',
                'previous' => null,
                'results' => [
                    [
                        'uuid' => 'c130c04f-794e-4f55-b34b-eb8f60472224',
                        'state' => 'running',
                        'name' => 'update_node',
                        'created_at' => '2022-09-12T12:19:15Z',
                        'updated_at' => '2022-09-12T12:20:30Z',
                        'logbook' => 'johndoe',
                    ],
                ]
            ])),
            new Response(200, [], json_encode([
                'count' => 1,
                'next' => null,
                'previous' => null,
                'results' => [
                    [
                        'uuid' => 'e2f39684-3ce0-48d1-acda-13fa064709b5',
                        'state' => 'success',
                        'name' => 'create_backup',
                        'created_at' => '2022-09-12T11:08:10Z',
                        'updated_at' => '2022-09-12T11:08:27Z',
                        'logbook' => 'johndoe',
                    ],
                ]
            ])),
        );

        $flows = $this->client->logbook->getList('johndoe');
        [$flow1, $flow2] = $flows;

        $this->assertCount(2, $flows);

        $this->assertInstanceOf(Flow::class, $flow1);
        $this->assertEquals('running', $flow1->state);
        $this->assertEquals('update_node', $flow1->name);

        $this->assertInstanceOf(Flow::class, $flow2);
        $this->assertEquals('success', $flow2->state);
        $this->assertEquals('create_backup', $flow2->name);
    }
}
