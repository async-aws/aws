---
category: clients
---

# Clients overview

There are a few API Clients, but not all of them are promoted with a separate page
in the documentation. On this page you find a complete list of clients and some
installation instructions.

## Install and configure

You install each client with composer. Here is an example to install DynamoDb:

```shell
$ composer require async-aws/dynamo-db
```

To instantiate a DynamoDb client (or any other client) you could provide four arguments.
All arguments are optional. Sensible defaults are used for each argument.

```php
use AsyncAws\DynamoDb\DynamoDbClient;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\Credentials\ConfigurationProvider;

$config = Configuration::create([
    'region' => 'eu-central-1',
]);
$credentialProvider = new ConfigurationProvider();
$httpClient = // ... Instance of Symfony's HttpClientInterface
$logger = // ... A PSR-3 logger

$dynamoDb = new DynamoDbClient($config, $credentialProvider, $httpClient, $logger);
```

A normal call to instantiate a DynamoDb client might look like:

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

## Use

Every API client has functions that represent operations or API calls. Every such
function takes an Input object (or array) as parameter and will return a Result.
See the API client's class to learn what operations are supported and what the input
and output are.

Your IDE will also be helpful. See example from PHPStorm:

[![async-aws method doc](/assets/image/compare/aa-method.png)](/assets/image/compare/aa-method.png)

## All supported clients

Here is a list of supported clients. If need another client or a new operation you
may be able to automatically generate that. See the [contribution guide](/contribute/index.md)
for more information.

| Api Client                  | Package name
| --------------------------- | ----------------------------------------------------------------------------------------
| CloudFormation              | [async-aws/cloud-formation](https://packagist.org/packages/async-aws/cloud-formation)
| DynamoDb                    | [async-aws/dynamo-db](https://packagist.org/packages/async-aws/dynamo-db)
| [Lambda](./lambda.md)       | [async-aws/lambda](https://packagist.org/packages/async-aws/lambda)
| [S3](./s3.md)               | [async-aws/s3](https://packagist.org/packages/async-aws/s3)
| SES                         | [async-aws/ses](https://packagist.org/packages/async-aws/ses)
| SMS                         | [async-aws/sns](https://packagist.org/packages/async-aws/sns)
| STS                         | [async-aws/core](https://packagist.org/packages/async-aws/core)
| SQS                         | [async-aws/sqs](https://packagist.org/packages/async-aws/sqs)
