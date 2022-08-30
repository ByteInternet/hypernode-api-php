<?php

declare(strict_types=1);

namespace Hypernode\Api\Resource\Logbook;

use Hypernode\Api\HypernodeClient;
use Hypernode\Api\Resource\AbstractResource;

/**
 * @property-read string $result
 * @property-read string $flow_name
 * @property-read string $app_name
 * @property-read list<array{uuid: string, state: string, name: string}> $jobs
 */
class Job extends AbstractResource
{
    private string $url;
    private bool $exists = false;
    private bool $running = false;
    private bool $complete = false;

    protected HypernodeClient $client;

    public function __construct(HypernodeClient $client, string $urlOrId, array $data = [])
    {
        $this->client = $client;
        $this->url = $urlOrId;
        $this->data = $data;
    }

    /**
     * Refresh the job data, return true when job has not completed yet, return false if job has completed.
     * Useful to run this command like:
     * while (!$job->completed()) {
     *     $job->refresh();
     * }
     */
    public function refresh(): bool
    {
        $response = $this->client->api->get($this->url);

        if ($response->getStatusCode() === 404) {
            $this->data = [];
            $this->exists = false;
            $this->running = false;
            return true;
        }

        if ($response->getStatusCode() === 303) {
            $this->data = [];
            $this->exists = true;
            $this->running = false;
            $this->complete = true;
            return false;
        }

        $this->client->maybeThrowApiExceptions($response);

        $this->data = $this->client->getJsonFromResponse($response);
        $this->exists = true;
        $this->running = true;

        return false;
    }

    public function exists(): bool
    {
        return $this->exists;
    }

    public function completed(): bool
    {
        return $this->complete;
    }
}