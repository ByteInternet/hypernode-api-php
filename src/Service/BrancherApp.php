<?php

declare(strict_types=1);

namespace Hypernode\Api\Service;

use Hypernode\Api\Exception\HypernodeApiClientException;
use Hypernode\Api\Exception\HypernodeApiServerException;

class BrancherApp extends AbstractService
{
    /**
     * List all brancher nodes for given parent app.
     *
     * @param string $app Name of the parent app
     * @param array|null $data Extra data to be provided
     * @return array Array containing brancher nodes
     * @throws HypernodeApiClientException
     * @throws HypernodeApiServerException
     */
    public function list(string $app, ?array $data = null): array
    {
        $url = sprintf(App::V2_BRANCHER_APP_URL, $app);

        $response = $this->client->api->get($url, [], $data ? json_encode($data) : null);

        $this->client->maybeThrowApiExceptions($response);

        $data = $this->client->getJsonFromResponse($response);

        return $data['branchers'];
    }

    /**
     * Create a brancher app for given parent app.
     *
     * @param string $app Name of the parent app
     * @param array|null $data Extra data to be provided
     * @return string Name of the brancher app
     * @throws HypernodeApiClientException
     * @throws HypernodeApiServerException
     */
    public function create(string $app, ?array $data = null): string
    {
        $url = sprintf(App::V2_BRANCHER_APP_URL, $app);

        $response = $this->client->api->post($url, [], $data ? json_encode($data) : null);

        $this->client->maybeThrowApiExceptions($response);

        $data = $this->client->getJsonFromResponse($response);

        return $data['name'];
    }

    /**
     * Cancel an brancher app.
     *
     * @param string $app Name of the brancher app
     * @return void
     * @throws HypernodeApiClientException
     * @throws HypernodeApiServerException
     */
    public function cancel(string $app): void
    {
        $url = sprintf(App::V2_APP_CANCEL_URL, $app);

        $response = $this->client->api->post($url);

        $this->client->maybeThrowApiExceptions($response);
    }
}
