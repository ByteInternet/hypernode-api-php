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
        $this->jobUrl = "https://api.hypernode.com/logbook/v1/jobs/abcd/";
        $this->job = new Job($this->client, $this->jobUrl);
    }

    public function testIsInstanceOfAbstractResource()
    {
        $this->assertInstanceOf(AbstractResource::class, $this->job);
    }

    public function testRefresh()
    {
        $this->responses->append(
            new Response(404, [], json_encode([])),
            new Response(200, [], json_encode([
                'result' => 'pending',
                'flow_name' => 'update_node',
                'app_name' => 'johndoe'
            ])),
            new Response(200, [], json_encode([
                'result' => 'running',
                'flow_name' => 'update_node',
                'app_name' => 'johndoe'
            ])),
            new Response(303, [], json_encode([])),
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
