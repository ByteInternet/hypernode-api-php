<?php

declare(strict_types=1);

namespace Hypernode\Api\Service;

use Hypernode\Api\Exception\HypernodeApiClientException;
use Hypernode\Api\Exception\HypernodeApiServerException;

class InsightsAnnotation extends AbstractService
{
    public const V2_INSIGHTS_ANNOTATION_LIST_URL = "/v2/insights-annotation/";

    /**
     * List all custom Insights annotations for the Hypernodes you have access
     * to. Only annotations created through the API are listed; system
     * annotations generated from platform events are not included.
     *
     * @param array $params Query parameters, e.g. limit and offset
     * @return array List of Insights annotations
     * @throws HypernodeApiClientException
     * @throws HypernodeApiServerException
     */
    public function getList(array $params = []): array
    {
        $annotations = [];

        $requestUrl = self::V2_INSIGHTS_ANNOTATION_LIST_URL;
        if ($params) {
            $requestUrl .= '?' . http_build_query($params);
        }

        while ($requestUrl) {
            $response = $this->client->api->get($requestUrl);

            $this->client->maybeThrowApiExceptions($response);
            $data = $this->client->getJsonFromResponse($response);

            foreach ($data['results'] as $item) {
                $annotations[] = $item;
            }

            $requestUrl = $data['next'];
        }

        return $annotations;
    }
}
