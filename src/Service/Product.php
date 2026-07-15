<?php

declare(strict_types=1);

namespace Hypernode\Api\Service;

use Hypernode\Api\Exception\HypernodeApiClientException;
use Hypernode\Api\Exception\HypernodeApiServerException;

class Product extends AbstractService
{
    public const V2_PRODUCT_LIST_URL = "/v2/product/";

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
}
