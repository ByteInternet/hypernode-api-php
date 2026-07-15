<?php

declare(strict_types=1);

namespace Hypernode\Api\Service;

use Hypernode\Api\Exception\HypernodeApiClientException;
use Hypernode\Api\Exception\HypernodeApiServerException;

class InsightsAnnotation extends AbstractService
{
    public const V2_INSIGHTS_ANNOTATION_LIST_URL = "/v2/insights-annotation/";
    public const V2_INSIGHTS_ANNOTATION_CREATE_URL = "/v2/insights-annotation/create/";

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

    /**
     * Create a custom Insights annotation for a Hypernode. The annotation is
     * shown in the Insights graphs at the point in time given by `x_axis` (a
     * unix timestamp in seconds). Optionally restrict the annotation to
     * specific graphs with `metrics`, a comma-separated list of metric names;
     * when omitted, the annotation applies to all metrics.
     *
     * @param array $data Annotation data, requires at least the keys
     *  `name`, `x_axis` and `app`.
     * @return array The created annotation
     * @throws HypernodeApiClientException
     * @throws HypernodeApiServerException
     */
    public function create(array $data): array
    {
        $response = $this->client->api->post(
            self::V2_INSIGHTS_ANNOTATION_CREATE_URL,
            [],
            json_encode($data)
        );

        $this->client->maybeThrowApiExceptions($response);

        return $this->client->getJsonFromResponse($response);
    }
}
