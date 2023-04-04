<?php

declare(strict_types=1);

namespace Hypernode\Api\Service;

class App extends AbstractService
{
    public const V2_APP_LIST_URL = "/v2/app/";
    public const V2_APP_DETAIL_URL = "/v2/app/%s/";
    public const V2_APP_CANCEL_URL = "/v2/app/%s/cancel/";
    public const V2_BRANCHER_APP_URL = "/v2/brancher/app/%s/";
    public const V2_BRANCHER_DETAIL_URL = "/v2/brancher/%s/";
    public const V1_APP_FLOWS_URL = "/logbook/v1/logbooks/%s/flows/";

    /**
     * @param array $params
     * @return \Hypernode\Api\Resource\App[]
     * @throws \Http\Client\Exception
     * @throws \Hypernode\Api\Exception\HypernodeApiClientException
     * @throws \Hypernode\Api\Exception\HypernodeApiServerException
     */
    public function getList(array $params = []): array
    {
        $apps = [];

        $requestUrl = self::V2_APP_LIST_URL;
        if ($params) {
            $requestUrl .= '?' . http_build_query($params);
        }

        while ($requestUrl) {
            $response = $this->client->api->get($requestUrl);

            $this->client->maybeThrowApiExceptions($response);
            $data = $this->client->getJsonFromResponse($response);

            foreach ($data['results'] as $item) {
                $apps[] = new \Hypernode\Api\Resource\App($this->client, $item);
            }

            $requestUrl = $data['next'];
        }

        return $apps;
    }
}
