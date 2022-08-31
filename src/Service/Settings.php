<?php

declare(strict_types=1);

namespace Hypernode\Api\Service;

use Hypernode\Api\Resource\Logbook\Job;

class Settings extends AbstractService
{
    public function set(string $app, string $key, string $value): ?Job
    {
        return $this->setBatch($app, [$key => $value]);
    }

    public function setBatch(string $app, array $settings): ?Job
    {
        $url = sprintf(App::V2_APP_DETAIL_URL, $app);
        $response = $this->client->api->patch($url, [], json_encode($settings));

        $this->client->maybeThrowApiExceptions($response);

        if ($response->getStatusCode() === 202) {
            $job = new Job($this->client, $response->getHeaderLine('Location'));
            return $job;
        }

        return null;
    }
}