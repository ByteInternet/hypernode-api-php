<?php

declare(strict_types=1);

namespace Hypernode\Api\Service;

use Hypernode\Api\Exception\HypernodeApiClientException;
use Hypernode\Api\Exception\HypernodeApiServerException;

class Backup extends AbstractService
{
    public const V2_APP_BACKUP_URL = "/v2/app/%s/backup/";

    /**
     * Show all backups that are currently available for the given Hypernode.
     *
     * @param string $app Name of the Hypernode
     * @param array $params Query parameters, e.g. limit and offset
     * @return array List of backups
     * @throws HypernodeApiClientException
     * @throws HypernodeApiServerException
     */
    public function getList(string $app, array $params = []): array
    {
        $backups = [];

        $requestUrl = sprintf(self::V2_APP_BACKUP_URL, $app);
        if ($params) {
            $requestUrl .= '?' . http_build_query($params);
        }

        while ($requestUrl) {
            $response = $this->client->api->get($requestUrl);

            $this->client->maybeThrowApiExceptions($response);
            $data = $this->client->getJsonFromResponse($response);

            foreach ($data['results'] as $item) {
                $backups[] = $item;
            }

            $requestUrl = $data['next'];
        }

        return $backups;
    }

    /**
     * Create a new snapshot backup. This requires sla-standard to be enabled
     * on the Hypernode.
     *
     * @param string $app Name of the Hypernode
     * @return string Response message
     * @throws HypernodeApiClientException
     * @throws HypernodeApiServerException
     */
    public function create(string $app): string
    {
        $url = sprintf(self::V2_APP_BACKUP_URL, $app);

        $response = $this->client->api->post($url);

        $this->client->maybeThrowApiExceptions($response);

        return $this->client->getJsonFromResponse($response);
    }
}
