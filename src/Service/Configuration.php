<?php

declare(strict_types=1);

namespace Hypernode\Api\Service;

use Hypernode\Api\Exception\HypernodeApiClientException;
use Hypernode\Api\Exception\HypernodeApiServerException;

class Configuration extends AbstractService
{
    public const V2_CONFIGURATION_URL = "/v2/configuration/";

    /**
     * List all available configurations for preinstalls.
     *
     * @param array $params Query parameters, e.g. allow_preinstall, limit and offset
     * @return array List of app configurations
     * @throws HypernodeApiClientException
     * @throws HypernodeApiServerException
     */
    public function getList(array $params = []): array
    {
        $configurations = [];

        $requestUrl = self::V2_CONFIGURATION_URL;
        if ($params) {
            $requestUrl .= '?' . http_build_query($params);
        }

        while ($requestUrl) {
            $response = $this->client->api->get($requestUrl);

            $this->client->maybeThrowApiExceptions($response);
            $data = $this->client->getJsonFromResponse($response);

            foreach ($data['results'] as $item) {
                $configurations[] = $item;
            }

            $requestUrl = $data['next'];
        }

        return $configurations;
    }
}
