# Hypernode API PHP Client

_**Please note: this project is still in its early stages and the API may be subject to change.**_

## Installation

```bash
composer require hypernode/api-client
```

The API client is HTTP client agnostic, which means that it's compatible with
any HTTP client implementing [PSR-18 interface](https://www.php-fig.org/psr/psr-18/).

Popular HTTP client implementations are: [Guzzle](https://packagist.org/packages/guzzlehttp/guzzle) and [Symfony HTTP Client](https://packagist.org/packages/symfony/http-client).

A full list of implementations, can be [found here](https://packagist.org/providers/psr/http-client-implementation).

## Usage

### Acquiring an API token

Each Hypernode has an API token associated with it, you can use that to talk to the API directly. You can find the token in `/etc/hypernode/hypernode_api_token`. For API tokens with special permissions please contact support@hypernode.com.

### Using the client

``` php
use Hypernode\Api\HypernodeClientFactory;

require_once 'vendor/autoload.php';

$client = HypernodeClientFactory::create(getenv('HYPERNODE_API_TOKEN'));

// For the Hypernode `johndoe` PHP version to 8.1 and Node.js version to 18
$job = $client->settings->setBatch('johndoe', [
    'php_version' => '8.1',
    'nodejs_version' => '18'
]);

// If something has changed, wait for the changes to be applied.
while ($job && !$job->completed()) {
    sleep(2);
    $job->refresh();
}
```

## Supported features

Here's a list of Hypernode API features implemented in the client.

- Updating one or multiple Hypernode settings at once.
- Querying/polling the logbook for the status of a job.
- Creating and cancelling Brancher Hypernode instances.

## Related projects

- The official [Hypernode API Python Client](https://github.com/byteinternet/hypernode-api-python)
- The official [Hypernode Deploy](https://github.com/byteinternet/hypernode-deploy-configuration) tool
- The official [Hypernode Docker](https://github.com/byteinternet/hypernode-docker) image
