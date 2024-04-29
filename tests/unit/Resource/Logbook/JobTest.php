<?php

declare(strict_types=1);

namespace Hypernode\Api\Resource\Logbook;

use GuzzleHttp\Psr7\Response;
use Hypernode\Api\HypernodeClientTestCase;
use Hypernode\Api\Resource\AbstractResource;

class JobTest extends HypernodeClientTestCase
{
    private Job $job;
    private string $jobUrl;

    protected function setUp(): void
    {
        parent::setUp();
        $this->job = new Job($this->client, 'johndoe', 'abcd');
    }

    public function testIsInstanceOfAbstractResource()
    {
        $this->assertInstanceOf(AbstractResource::class, $this->job);
    }

    public function testRefresh()
    {
        $this->responses->append(
            new Response(404, [], json_encode([])),
            new Response(200, [], json_encode(['count' => 0, 'results' => []])),
            new Response(200, [], json_encode([
                'count' => 1,
                'results' => [
                    [
                        'uuid' => 'abcd',
                        'state' => NULL,
                        'name' => 'update_node'
                    ]
                ]
            ])),
            new Response(200, [], json_encode([
                'count' => 1,
                'results' => [
                    [
                        'uuid' => 'abcd',
                        'state' => 'running',
                        'name' => 'update_node'
                    ]
                ]
            ])),
            new Response(200, [], json_encode([
                'count' => 1,
                'results' => [
                    [
                        'uuid' => 'abcd',
                        'state' => 'success',
                        'name' => 'update_node'
                    ]
                ]
            ])),
        );

        $this->job->refresh();

        $this->assertFalse($this->job->exists());
        $this->assertFalse($this->job->completed());

        $this->job->refresh();

        $this->assertFalse($this->job->exists());
        $this->assertFalse($this->job->completed());

        $this->job->refresh();

        $this->assertTrue($this->job->exists());
        $this->assertFalse($this->job->completed());

        $this->job->refresh();

        $this->assertTrue($this->job->exists());
        $this->assertFalse($this->job->completed());

        $this->job->refresh();

        $this->assertTrue($this->job->exists());
        $this->assertTrue($this->job->completed());
        $this->assertTrue($this->job->success());
    }

    public function testRefreshFailedJob()
    {
        $this->responses->append(
            new Response(404, [], json_encode([])),
            new Response(200, [], json_encode([
                'count' => 1,
                'results' => [
                    [
                        'uuid' => 'abcd',
                        'state' => NULL,
                        'name' => 'update_node'
                    ]
                ]
            ])),
            new Response(200, [], json_encode([
                'count' => 1,
                'results' => [
                    [
                        'uuid' => 'abcd',
                        'state' => 'running',
                        'name' => 'update_node'
                    ]
                ]
            ])),
            new Response(200, [], json_encode([
                'count' => 1,
                'results' => [
                    [
                        'uuid' => 'abcd',
                        'state' => 'reverted',
                        'name' => 'update_node'
                    ]
                ]
            ])),
        );

        $this->job->refresh();

        $this->assertFalse($this->job->exists());
        $this->assertFalse($this->job->completed());

        $this->job->refresh();

        $this->assertTrue($this->job->exists());
        $this->assertFalse($this->job->completed());

        $this->job->refresh();

        $this->assertTrue($this->job->exists());
        $this->assertFalse($this->job->completed());

        $this->job->refresh();

        $this->assertTrue($this->job->exists());
        $this->assertTrue($this->job->completed());
        $this->assertFalse($this->job->success());
    }

    public function testExistsReturnsFalseByDefault()
    {
        $this->assertFalse($this->job->exists());
    }

    public function testCompletedReturnsFalseByDefault()
    {
        $this->assertFalse($this->job->completed());
    }
}
