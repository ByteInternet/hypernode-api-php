<?php

declare(strict_types=1);

namespace Hypernode\Api\Service;

class App extends AbstractService
{
    public const V2_APP_DETAIL_URL = "/v2/app/%s/";
    public const V2_APP_CANCEL_URL = "/v2/app/%s/cancel/";
    public const V2_APP_EPHEMERAL_URL = "/v2/app/%s/ephemeral/";
}