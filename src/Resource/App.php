<?php

declare(strict_types=1);

namespace Hypernode\Api\Resource;

use Hypernode\Api\HypernodeClient;
use Hypernode\Api\Resource\AbstractResource;

/**
 * @property-read string $name
 * @property-read string $type
 * @property-read string|null $parent
 * @property-read string[] $labels
 */
class App extends AbstractResource
{
    protected HypernodeClient $client;

    public function __construct(HypernodeClient $client, array $data = [])
    {
        $this->client = $client;
        $this->data = $data;
    }
}
