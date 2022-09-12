<?php

declare(strict_types=1);

namespace Hypernode\Api\Service;

use Hypernode\Api\Exception\HypernodeApiClientException;
use Hypernode\Api\Exception\HypernodeApiServerException;
use Hypernode\Api\Resource\Logbook\Flow;

class Logbook extends AbstractService
{
    /**
     * Get jobs (complete and non-complete) from logbook for given Hypernode.
     *
     * @param string $app Name of the hypernode
     * @param int $amount
     * @return Flow[]
     * @throws HypernodeApiClientException
     * @throws HypernodeApiServerException
     */
    public function getList(string $app, int $amount = 50): array
    {
        /** @var Flow[] $result */
        $result = [];

        $url = sprintf(App::V1_APP_FLOWS_URL, $app);

        $data = ['next' => $url];
        while (count($result) < $amount && $data['next']) {
            $response = $this->client->api->get($data['next']);
            $this->client->maybeThrowApiExceptions($response);
            $data = $this->client->getJsonFromResponse($response);
            foreach ($data['results'] as $flowData) {
                $result[] = new Flow($flowData);
            }
        }

        return $result;
    }
}
