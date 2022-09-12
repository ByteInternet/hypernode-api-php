<?php

declare(strict_types=1);

namespace Hypernode\Api\Resource;

use Carbon\Carbon;

class AbstractResource
{
    protected array $data;
    protected array $dateAttributes = [];

    public function __get(string $name)
    {
        $value = $this->data[$name] ?? null;

        if (in_array($name, $this->dateAttributes)) {
            return Carbon::create($value);
        }

        return $value;
    }

    public function __isset($name)
    {
        return array_key_exists($name, $this->data);
    }
}