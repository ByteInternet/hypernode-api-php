<?php

declare(strict_types=1);

namespace Hypernode\Api\Resource\Logbook;

use Hypernode\Api\HypernodeClient;
use Hypernode\Api\Resource\AbstractResource;
use Hypernode\Api\Service\App;

/**
 * @property-read string $result
 * @property-read string $flow_name
 * @property-read string $app_name
 * @property-read list<array{uuid: string, state: string, name: string}> $jobs
 */
class Job extends AbstractResource
{
    private string $id;
    private string $appName;
    private bool $exists = false;
    private bool $running = false;
    private bool $success = false;
    private bool $complete = false;
    protected HypernodeClient $client;

    public function __construct(HypernodeClient $client, string $appName, string $id, array $data = [])
    {
        $this->client = $client;
        $this->appName = $appName;
        $this->id = $id;
        $this->data = $data;
    }

    /**
     * Refresh the job data. Can be used to wait for a job to be completed, like:
     * while (!$job->completed()) {
     *     $job->refresh();
     * }
     */
    public function refresh()
    {
        $url = sprintf(App::V1_APP_FLOWS_URL, $this->appName) . '?' . http_build_query(['tracker_uuid' => $this->id]);
        $response = $this->client->api->get($url);
        $this->data = $this->client->getJsonFromResponse($response);

        if ($response->getStatusCode() === 404 || $this->data['count'] === 0) {
            $this->data = [];
            $this->exists = false;
            $this->running = false;
            return;
        }

        $this->exists = true;

        $result = $this->data['results'][0];
        switch ($result['state']) {
            case 'running':
                $this->running = true;
                break;
            case 'success':
                $this->success = true;
                $this->running = false;
                $this->complete = true;
                break;
            case 'reverted':
                $this->running = false;
                $this->complete = true;
                break;
        }

        $this->client->maybeThrowApiExceptions($response);
    }

    public function id()
    {
        return $this->id;
    }

    public function exists(): bool
    {
        return $this->exists;
    }

    public function running(): bool
    {
        return $this->running;
    }

    public function completed(): bool
    {
        return $this->complete;
    }

    public function success(): bool
    {
        return $this->success;
    }
}
