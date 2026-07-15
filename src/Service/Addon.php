<?php

declare(strict_types=1);

namespace Hypernode\Api\Service;

use Hypernode\Api\Exception\HypernodeApiClientException;
use Hypernode\Api\Exception\HypernodeApiServerException;

class Addon extends AbstractService
{
    public const V2_ADDON_SLA_LIST_URL = "/v2/addon/slas/";

    /**
     * List all available SLA addons.
     *
     * @return array List of SLA addons
     * @throws HypernodeApiClientException
     * @throws HypernodeApiServerException
     */
    public function getSlas(): array
    {
        $response = $this->client->api->get(self::V2_ADDON_SLA_LIST_URL);

        $this->client->maybeThrowApiExceptions($response);

        return $this->client->getJsonFromResponse($response);
    }
}
