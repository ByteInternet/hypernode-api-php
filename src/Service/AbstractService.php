<?php

declare(strict_types=1);

namespace Hypernode\Api\Service;

use Hypernode\Api\HypernodeClient;

abstract class AbstractService
{
    protected HypernodeClient $client;

    public function __construct(HypernodeClient $client)
    {
        $this->client = $client;
    }
}