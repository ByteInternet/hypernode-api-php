<?php

declare(strict_types=1);

namespace Hypernode\Api\Service;

use Hypernode\Api\Exception\HypernodeApiClientException;
use Hypernode\Api\Exception\HypernodeApiServerException;

class BrancherApp extends AbstractService
{
    /**
     * Create an brancher app for given parent app.
     *
     * @param string $app Name of the parent app
     * @return string Name of the brancher app
     * @throws HypernodeApiClientException
     * @throws HypernodeApiServerException
     */
    public function create(string $app): string
    {
        $url = sprintf(App::V2_APP_BRANCHER_URL, $app);

        $response = $this->client->api->post($url);

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
