---
category: clients
---

# Clients overview

AsyncAws has implemented the most popular API clients, but not all of them are promoted
with their own page in the documentation. On this page you find some installation
instructions and a complete list of clients.

## Install and configure

You install a client with Composer. Here is an example to install DynamoDb:

```shell
composer require async-aws/dynamo-db
```

To instantiate a DynamoDb client (or any other client) you could provide four arguments.
All arguments are optional and sensible defaults are used.

```php
use AsyncAws\Core\Configuration;
use AsyncAws\Core\Credentials\ConfigurationProvider;
use AsyncAws\DynamoDb\DynamoDbClient;

$config = Configuration::create([
    'region' => 'eu-central-1',
]);
$credentialProvider = new ConfigurationProvider();
$httpClient = // ... Instance of Symfony's HttpClientInterface
$logger = // ... A PSR-3 logger

$dynamoDb = new DynamoDbClient($config, $credentialProvider, $httpClient, $logger);
```

A common way to instantiate a DynamoDb client might look like:

```php
use AsyncAws\DynamoDb\DynamoDbClient;

$dynamoDb = new DynamoDbClient([
   'region' => 'eu-central-1',
   'accessKeyId' => 'AKIAIOSFODNN7EXAMPLE',
   'accessKeySecret' => 'wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY',
]);
```

See section about [authentication](/authentication/index.md) to learn more about
different ways to authenticate.

### Dependency injection container

If your application is using a dependency injection container, you may configure a
client like:

#### Symfony

```yaml
services:
    AsyncAws\DynamoDb\DynamoDbClient:
        arguments:
            - region: 'eu-central-1'
              accessKeyId: 'AKIAIOSFODNN7EXAMPLE'
              accessKeySecret: 'wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY'
            - ~ # Use the default authentication providers
            - '@http_client'
            - '@logger'
```

If you are using Symfony you may ease configuration by install the [Symfony Bundle](/integration/symfony-bundle.md).

#### Laravel

```php
use Illuminate\Support\ServiceProvider;
use AsyncAws\DynamoDb\DynamoDbClient;

class AsyncAwsProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            DynamoDbClient::class,
            function($app) {
                return new DynamoDbClient([
                   'region' => 'eu-central-1',
                   'accessKeyId' => 'AKIAIOSFODNN7EXAMPLE',
                   'accessKeySecret' => 'wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY',
                ]);
            }
        );
    }
}
```

### Client factory

If you don't use dependency injection, you might be interested in the `AwsClientFactory`
that can be used to instantiate API clients.

```php
use AsyncAws\Core\AwsClientFactory;

$factory = new AwsClientFactory([
   'region' => 'eu-central-1',
   'accessKeyId' => 'AKIAIOSFODNN7EXAMPLE',
   'accessKeySecret' => 'wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY',
]);

$dynamoDb = $factory->dynamoDb();
$sqs = $factory->sqs();
```

## Use

Every API client has functions that represent "operations" or API calls. Every such
function takes an Input object (or array) as parameter and will return a Result.
See the API client's class to learn what operations are supported and what the input
and output are.

All Input objects for SQS is located in [`AsyncAws\Sqs\Input\*`](https://github.com/async-aws/aws/tree/master/src/Service/Sqs/src/Input)
and all Result objects are located in [`AsyncAws\Sqs\Result\*`](https://github.com/async-aws/aws/tree/master/src/Service/Sqs/src/Result).

Your IDE will also be helpful to discover function and how to use them. See example
from PHPStorm:

[![PHPStorm function help](/assets/image/compare/aa-method.png)](/assets/image/compare/aa-method.png)

## All supported clients

Here is a list of supported clients. If there is a need for another client or a new
operation, it can be automatically generated. See the [contribution guide](/contribute/index.md)
for more information.

| Api Client                  | Package name
| --------------------------- | ----------------------------------------------------------------------------------------
| [CloudFormation](./cf.md)   | [async-aws/cloud-formation](https://packagist.org/packages/async-aws/cloud-formation)
| [DynamoDb]('./dynamodb.md)  | [async-aws/dynamo-db](https://packagist.org/packages/async-aws/dynamo-db)
| [Lambda](./lambda.md)       | [async-aws/lambda](https://packagist.org/packages/async-aws/lambda)
| [S3](./s3.md)               | [async-aws/s3](https://packagist.org/packages/async-aws/s3)
| [SES](./ses.md)             | [async-aws/ses](https://packagist.org/packages/async-aws/ses)
| [SNS](./sns.md)             | [async-aws/sns](https://packagist.org/packages/async-aws/sns)
| [STS](./sts.md)             | [async-aws/core](https://packagist.org/packages/async-aws/core)
| [SQS](./sqs.md)             | [async-aws/sqs](https://packagist.org/packages/async-aws/sqs)
