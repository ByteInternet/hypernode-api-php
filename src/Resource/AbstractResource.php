<?php

declare(strict_types=1);

namespace Hypernode\Api\Resource;

class AbstractResource
{
    protected array $data;

    public function __get(string $name)
    {
        return $this->data[$name] ?? null;
    }

    public function __isset($name)
    {
        return array_key_exists($name, $this->data);
    }
}