<?php

declare(strict_types=1);

namespace Hypernode\Api\Service;

use GuzzleHttp\Psr7\Response;
use Hypernode\Api\Exception\HypernodeApiClientException;
use Hypernode\Api\Exception\HypernodeApiServerException;
use Hypernode\Api\HypernodeClientTestCase;

class BackupTest extends HypernodeClientTestCase
{
    public function testGetList()
    {
        $backups = [
            [
                'backup_id' => 'backup-123',
                'type' => 'periodic',
                'backup_created_at' => '2026-07-14T01:00:00Z',
                'expired_at' => '2026-07-28T01:00:00Z',
            ],
            [
                'backup_id' => 'backup-456',
                'type' => 'instant',
                'backup_created_at' => '2026-07-15T09:30:00Z',
                'expired_at' => '2026-07-29T09:30:00Z',
            ],
        ];
        $this->responses->append(
            new Response(200, [], json_encode([
                'count' => 2,
                'next' => null,
                'previous' => null,
                'results' => $backups,
            ])),
        );

        $result = $this->client->backup->getList('johndoe');

        $request = $this->responses->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/v2/app/johndoe/backup/', $request->getUri());
        $this->assertEquals($backups, $result);
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

        $result = $this->client->backup->getList('johndoe', ['limit' => 10, 'offset' => 20]);

        $request = $this->responses->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/v2/app/johndoe/backup/?limit=10&offset=20', $request->getUri());
        $this->assertEquals([], $result);
    }

    public function testGetListPaginates()
    {
        $this->responses->append(
            new Response(200, [], json_encode([
                'count' => 2,
                'next' => 'https://api.hypernode.com/v2/app/johndoe/backup/?limit=1&offset=1',
                'previous' => null,
                'results' => [
                    [
                        'backup_id' => 'backup-123',
                        'type' => 'periodic',
                        'backup_created_at' => '2026-07-14T01:00:00Z',
                        'expired_at' => '2026-07-28T01:00:00Z',
                    ],
                ],
            ])),
            new Response(200, [], json_encode([
                'count' => 2,
                'next' => null,
                'previous' => null,
                'results' => [
                    [
                        'backup_id' => 'backup-456',
                        'type' => 'instant',
                        'backup_created_at' => '2026-07-15T09:30:00Z',
                        'expired_at' => '2026-07-29T09:30:00Z',
                    ],
                ],
            ])),
        );

        $result = $this->client->backup->getList('johndoe');

        $this->assertCount(2, $result);
        $this->assertEquals('backup-123', $result[0]['backup_id']);
        $this->assertEquals('backup-456', $result[1]['backup_id']);

        $request = $this->responses->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals(
            'https://api.hypernode.com/v2/app/johndoe/backup/?limit=1&offset=1',
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

        $this->client->backup->getList('johndoe');
    }

    public function testGetListRaisesServerExceptions()
    {
        $badRequestResponse = new Response(500, [], json_encode([
            'non_field_errors' => ['Something went wrong processing your request.']
        ]));
        $this->responses->append($badRequestResponse);

        $this->expectExceptionObject(new HypernodeApiServerException($badRequestResponse));

        $this->client->backup->getList('johndoe');
    }

    public function testCreate()
    {
        $this->responses->append(
            new Response(200, [], json_encode('Backup creation initiated')),
        );

        $result = $this->client->backup->create('johndoe');

        $request = $this->responses->getLastRequest();
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('/v2/app/johndoe/backup/', $request->getUri());
        $this->assertEquals('Backup creation initiated', $result);
    }

    public function testCreateRaisesClientExceptions()
    {
        $badRequestResponse = new Response(400, [], json_encode(
            'Creating backups requires sla-standard to be enabled.'
        ));
        $this->responses->append($badRequestResponse);

        $this->expectExceptionObject(new HypernodeApiClientException($badRequestResponse));

        $this->client->backup->create('johndoe');
    }

    public function testCreateRaisesServerExceptions()
    {
        $badRequestResponse = new Response(500, [], json_encode([
            'non_field_errors' => ['Something went wrong processing your request.']
        ]));
        $this->responses->append($badRequestResponse);

        $this->expectExceptionObject(new HypernodeApiServerException($badRequestResponse));

        $this->client->backup->create('johndoe');
    }
}
