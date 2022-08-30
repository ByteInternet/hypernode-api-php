<?php

declare(strict_types=1);

namespace Hypernode\Api\Exception;

use Exception;
use Psr\Http\Message\ResponseInterface;

class ResponseException extends Exception
{
    private ResponseInterface $response;

    public function __construct(ResponseInterface $response)
    {
        parent::__construct();

        $this->response = $response;
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}