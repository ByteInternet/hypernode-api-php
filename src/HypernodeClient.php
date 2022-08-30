<?php

declare(strict_types=1);

namespace Hypernode\Api;

use Http\Client\Common\HttpMethodsClient;
use Hypernode\Api\Exception\HypernodeApiClientException;
use Hypernode\Api\Exception\HypernodeApiServerException;
use Hypernode\Api\Service\App;
use Hypernode\Api\Service\Settings;
use Psr\Http\Message\ResponseInterface;

class HypernodeClient
{
    public const VERSION = '0.1.0';

    public HttpMethodsClient $api;
    public App $app;
    public Settings $settings;

    public function __construct(HttpMethodsClient $apiClient)
    {
        $this->api = $apiClient;
        $this->app = new App($this);
        $this->settings = new Settings($this);
    }

    public function getJsonFromResponse(ResponseInterface $response)
    {
        return json_decode((string)$response->getBody(), true, JSON_THROW_ON_ERROR);
    }

    public function maybeThrowApiExceptions(ResponseInterface $response)
    {
        if ($response->getStatusCode() >= 500) {
            throw new HypernodeApiServerException($response);
        }
        if ($response->getStatusCode() >= 400) {
            throw new HypernodeApiClientException($response);
        }
    }
}