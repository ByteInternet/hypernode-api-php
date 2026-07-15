<?php

declare(strict_types=1);

namespace Hypernode\Api\Service;

use Hypernode\Api\Exception\HypernodeApiClientException;
use Hypernode\Api\Exception\HypernodeApiServerException;

class Whitelist extends AbstractService
{
    public const V2_WHITELIST_URL = "/v2/whitelist/%s/";

    /**
     * Get all whitelist entries for the Hypernode.
     *
     * @param string $app Name of the Hypernode
     * @param array $params Query parameters to filter the results, e.g. ['type' => 'waf']
     * @return array List of whitelist entries
     * @throws HypernodeApiClientException
     * @throws HypernodeApiServerException
     */
    public function getList(string $app, array $params = []): array
    {
        $url = sprintf(self::V2_WHITELIST_URL, $app);
        if ($params) {
            $url .= '?' . http_build_query($params);
        }

        $response = $this->client->api->get($url);

        $this->client->maybeThrowApiExceptions($response);

        return $this->client->getJsonFromResponse($response);
    }

    /**
     * Add a whitelist entry for the Hypernode.
     *
     * @param string $app Name of the Hypernode
     * @param array $data Whitelist entry data, requires at least the key `ip`.
     *  Optional keys are `type` (database, ftp, phpmyadmin, ssh or waf,
     *  defaults to database) and `description`.
     * @return array The created whitelist entry
     * @throws HypernodeApiClientException
     * @throws HypernodeApiServerException
     */
    public function create(string $app, array $data): array
    {
        $url = sprintf(self::V2_WHITELIST_URL, $app);

        $response = $this->client->api->post($url, [], json_encode($data));

        $this->client->maybeThrowApiExceptions($response);

        return $this->client->getJsonFromResponse($response);
    }
}
