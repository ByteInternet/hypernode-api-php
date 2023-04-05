<?php

use Hypernode\Api\HypernodeClientFactory;

require_once 'vendor/autoload.php';

$client = HypernodeClientFactory::create('1298269edeef1c19f05883e800d95bef2eca5212');

$active = $client->brancherApp->list('hntestgroot');

var_dump($active[0]['labels']);

$result = $client->brancherApp->update('hntestgroot-ephx0u2z7', ['labels' => ['mykey='.time()]]);

var_dump($result);