<?php

declare(strict_types=1);

namespace Hypernode\Api\Service;

use Hypernode\Api\Defaults;
use Hypernode\Api\Resource\Logbook\Job;

class Settings extends AbstractService
{
    public const JOB_URL_REGEX = '#' . Defaults::HYPERNODE_API_URL. 'logbook/v1/jobs/(.*)/#';

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
            $location = $response->getHeaderLine('Location');
            if (!preg_match(self::JOB_URL_REGEX, $location, $matches)) {
                return null;
            }
            $job = new Job($this->client, $app, $matches[1]);
            return $job;
        }

        return null;
    }
}