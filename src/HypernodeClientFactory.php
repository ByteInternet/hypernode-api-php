<?php

declare(strict_types=1);

namespace Hypernode\Api;

use Composer\InstalledVersions;
use Http\Client\Common\HttpMethodsClient;
use Http\Client\Common\Plugin\AddHostPlugin;
use Http\Client\Common\Plugin\AddPathPlugin;
use Http\Client\Common\Plugin\HeaderSetPlugin;
use Http\Client\Common\PluginClient;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Psr\Http\Client\ClientInterface;

class HypernodeClientFactory
{
    public const HYPERNODE_API_URL = 'https://api.hypernode.com/';

    public static function create(string $authToken, ?ClientInterface $httpClient = null): HypernodeClient
    {
        $httpHeaders = [
            'Authorization' => sprintf('Token %s', $authToken),
            'User-Agent' => sprintf(
                'Hypernode API PHP Client v%s',
                InstalledVersions::getVersion('hypernode/api-client'),
            ),
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
        $httpClient = self::getHttpClient(self::HYPERNODE_API_URL, $httpHeaders, $httpClient);

        $apiClient = new HttpMethodsClient(
            $httpClient, Psr17FactoryDiscovery::findRequestFactory(), Psr17FactoryDiscovery::findStreamFactory()
        );

        return new HypernodeClient($apiClient);
    }

    public static function getHttpClient(
        string $apiUrl,
        array $headers,
        ?ClientInterface $httpClient = null
    ): ClientInterface {
        $httpClient = $httpClient ?? Psr18ClientDiscovery::find();
        $uri = Psr17FactoryDiscovery::findUriFactory()->createUri($apiUrl);
        $plugins = [
            new AddHostPlugin($uri),
            new AddPathPlugin($uri),
            new HeaderSetPlugin($headers),
        ];

        return new PluginClient($httpClient, $plugins);
    }
}