<?php

declare(strict_types=1);

namespace Hypernode\Api\Service;

use GuzzleHttp\Psr7\Response;
use Hypernode\Api\HypernodeClientTestCase;

class SettingsTest extends HypernodeClientTestCase
{
    public function testSetSingleSettingToSameValue()
    {
        $this->responses->append(
            new Response(200, [], null),
        );

        $job = $this->client->settings->set('johndoe', 'php_version', '8.1');

        $request = $this->responses->getLastRequest();

        $this->assertNull($job);
        $this->assertEquals('PATCH', $request->getMethod());
        $this->assertEquals('/v2/app/johndoe/', $request->getUri());
        $this->assertJson((string)$request->getBody());
        $this->assertEquals(
            ['php_version' => '8.1'],
            json_decode((string)$request->getBody(), true)
        );
    }

    public function testSetSingleSettingToDifferentValue()
    {
        $jobUrl = 'https://api.hypernode.com/logbook/v1/jobs/abcd/';
        $this->responses->append(
            new Response(202, ['Location' => $jobUrl], null),
        );

        $job = $this->client->settings->set('johndoe', 'php_version', '8.1');

        $request = $this->responses->getLastRequest();

        $this->assertNotNull($job);
        $this->assertEquals('abcd', $job->id());
        $this->assertEquals('PATCH', $request->getMethod());
        $this->assertEquals('/v2/app/johndoe/', $request->getUri());
        $this->assertJson((string)$request->getBody());
        $this->assertEquals(
            ['php_version' => '8.1'],
            json_decode((string)$request->getBody(), true)
        );
    }

    public function testSetMultipleSettings()
    {
        $jobUrl = 'https://api.hypernode.com/logbook/v1/jobs/abcd/';
        $this->responses->append(
            new Response(202, ['Location' => $jobUrl], null),
        );

        $job = $this->client->settings->setBatch(
            'johndoe',
            [
                'php_version' => '8.1',
                'nodejs_version' => '18'
            ]
        );

        $request = $this->responses->getLastRequest();

        $this->assertNotNull($job);
        $this->assertEquals('abcd', $job->id());
        $this->assertEquals('PATCH', $request->getMethod());
        $this->assertEquals('/v2/app/johndoe/', $request->getUri());
        $this->assertJson((string)$request->getBody());
        $this->assertEquals(
            [
                'php_version' => '8.1',
                'nodejs_version' => '18'
            ],
            json_decode((string)$request->getBody(), true)
        );
    }
}
