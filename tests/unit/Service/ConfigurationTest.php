<?php

declare(strict_types=1);

namespace Hypernode\Api\Service;

use GuzzleHttp\Psr7\Response;
use Hypernode\Api\Exception\HypernodeApiClientException;
use Hypernode\Api\Exception\HypernodeApiServerException;
use Hypernode\Api\HypernodeClientTestCase;

class ConfigurationTest extends HypernodeClientTestCase
{
    public function testGetList()
    {
        $configurations = [
            [
                'name' => 'Magento 2',
                'configuration_id' => 'magento_2',
                'php_version' => '8.1',
                'mysql_version' => '8.0',
                'varnish_enabled' => false,
            ],
            [
                'name' => 'Shopware 6',
                'configuration_id' => 'shopware_6',
                'php_version' => '8.2',
                'mysql_version' => '8.0',
                'varnish_enabled' => true,
            ],
        ];
        $this->responses->append(
            new Response(200, [], json_encode([
                'count' => 2,
                'next' => null,
                'previous' => null,
                'results' => $configurations,
            ])),
        );

        $result = $this->client->configuration->getList();

        $request = $this->responses->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/v2/configuration/', $request->getUri());
        $this->assertEquals($configurations, $result);
    }

    public function testGetListAcceptsParams()
    {
        $this->responses->append(
            new Response(200, [], json_encode([
                'count' => 0,
                'next' => null,
                'previous' => null,
                'results' => [],
            ])),
        );

        $result = $this->client->configuration->getList(['allow_preinstall' => 'true']);

        $request = $this->responses->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/v2/configuration/?allow_preinstall=true', $request->getUri());
        $this->assertEquals([], $result);
    }

    public function testGetListPaginates()
    {
        $this->responses->append(
            new Response(200, [], json_encode([
                'count' => 2,
                'next' => 'https://api.hypernode.com/v2/configuration/?limit=1&offset=1',
                'previous' => null,
                'results' => [
                    [
                        'name' => 'Magento 2',
                        'configuration_id' => 'magento_2',
                    ],
                ],
            ])),
            new Response(200, [], json_encode([
                'count' => 2,
                'next' => null,
                'previous' => null,
                'results' => [
                    [
                        'name' => 'Shopware 6',
                        'configuration_id' => 'shopware_6',
                    ],
                ],
            ])),
        );

        $result = $this->client->configuration->getList();

        $this->assertCount(2, $result);
        $this->assertEquals('magento_2', $result[0]['configuration_id']);
        $this->assertEquals('shopware_6', $result[1]['configuration_id']);

        $request = $this->responses->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals(
            'https://api.hypernode.com/v2/configuration/?limit=1&offset=1',
            $request->getUri()
        );
    }

    public function testGetListRaisesClientExceptions()
    {
        $badRequestResponse = new Response(400, [], json_encode([
            'non_field_errors' => ['Your request was invalid.']
        ]));
        $this->responses->append($badRequestResponse);

        $this->expectExceptionObject(new HypernodeApiClientException($badRequestResponse));

        $this->client->configuration->getList();
    }

    public function testGetListRaisesServerExceptions()
    {
        $badRequestResponse = new Response(500, [], json_encode([
            'non_field_errors' => ['Something went wrong processing your request.']
        ]));
        $this->responses->append($badRequestResponse);

        $this->expectExceptionObject(new HypernodeApiServerException($badRequestResponse));

        $this->client->configuration->getList();
    }
}
