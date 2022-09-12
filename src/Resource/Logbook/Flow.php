<?php

declare(strict_types=1);

namespace Hypernode\Api\Resource\Logbook;

use Carbon\Carbon;
use Hypernode\Api\Resource\AbstractResource;

/**
 * @property-read string $state
 * @property-read string $name
 * @property-read string $app_name
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 */
class Flow extends AbstractResource
{
    public const STATE_SUCCESS = 'success';
    public const STATE_REVERTED = 'reverted';
    public const STATE_RUNNING = 'running';

    protected array $dateAttributes = [
        'created_at',
        'updated_at',
    ];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function isReverted(): bool
    {
        return $this->state === self::STATE_REVERTED;
    }

    public function isComplete(): bool
    {
        return $this->state === self::STATE_SUCCESS;
    }

    public function isRunning(): bool
    {
        return $this->state === self::STATE_RUNNING;
    }
}
