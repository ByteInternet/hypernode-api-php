<?php

declare(strict_types=1);

namespace Hypernode\Api\Service;

use Hypernode\Api\Exception\HypernodeApiClientException;
use Hypernode\Api\Exception\HypernodeApiServerException;

class Product extends AbstractService
{
    public const V2_PRODUCT_LIST_URL = "/v2/product/";
    public const V2_PRODUCT_APP_CURRENT_URL = "/v2/product/app/%s/current/";

    /**
     * Show all Hypernode products.
     *
     * @return array List of products
     * @throws HypernodeApiClientException
     * @throws HypernodeApiServerException
     */
    public function getList(): array
    {
        $response = $this->client->api->get(self::V2_PRODUCT_LIST_URL);

        $this->client->maybeThrowApiExceptions($response);

        return $this->client->getJsonFromResponse($response);
    }

    /**
     * Show the current product for the Hypernode.
     *
     * @param string $app Name of the Hypernode
     * @return array The current product
     * @throws HypernodeApiClientException
     * @throws HypernodeApiServerException
     */
    public function getCurrent(string $app): array
    {
        $url = sprintf(self::V2_PRODUCT_APP_CURRENT_URL, $app);

        $response = $this->client->api->get($url);

        $this->client->maybeThrowApiExceptions($response);

        return $this->client->getJsonFromResponse($response);
    }
}
