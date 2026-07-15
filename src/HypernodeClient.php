<?php

declare(strict_types=1);

namespace Hypernode\Api;

use Http\Client\Common\HttpMethodsClientInterface;
use Hypernode\Api\Exception\HypernodeApiClientException;
use Hypernode\Api\Exception\HypernodeApiServerException;
use Hypernode\Api\Service\Addon;
use Hypernode\Api\Service\App;
use Hypernode\Api\Service\Backup;
use Hypernode\Api\Service\BrancherApp;
use Hypernode\Api\Service\Configuration;
use Hypernode\Api\Service\Logbook;
use Hypernode\Api\Service\Settings;
use Psr\Http\Message\ResponseInterface;

class HypernodeClient
{
    public HttpMethodsClientInterface $api;
    public Addon $addon;
    public App $app;
    public Backup $backup;
    public BrancherApp $brancherApp;
    public Configuration $configuration;
    public Settings $settings;
    public Logbook $logbook;

    public function __construct(HttpMethodsClientInterface $apiClient)
    {
        $this->api = $apiClient;
        $this->addon = new Addon($this);
        $this->app = new App($this);
        $this->backup = new Backup($this);
        $this->brancherApp = new BrancherApp($this);
        $this->configuration = new Configuration($this);
        $this->settings = new Settings($this);
        $this->logbook = new Logbook($this);
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
