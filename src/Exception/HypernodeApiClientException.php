<?php

declare(strict_types=1);

namespace Hypernode\Api\Exception;

use Psr\Http\Client\ClientExceptionInterface;

class HypernodeApiClientException extends ResponseException implements ClientExceptionInterface
{
}