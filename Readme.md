# Async AWS client

If you are one of those people that like the Amazon PHP SDK but hate the fact
that you need to download Guzzle, PSR-7 and **every** AWS API client to use it?

This is the library for you!

## What is new?

The official [AWS PHP SDK](https://github.com/aws/aws-sdk-php) is great. It is
feature complete, it supports all Amazon APIs and is maintained by some really talented
developers. This library is different. It is maintained by some dumb and lazy people.

The goals of this client are:

1) **Async first.** Everything is asynchronous and responses are only downloaded if needed.
2) **Not feature complete.** We are only covering the handful of services that are used by most people. We are not even covering all of those popular services.
3) **No frequent updates.** Updates are great, but if changes are released multiple times every week you cannot keep up-to-date with the changelog. That is especially annoying when the changes are not related to services you use.
4) **Readable code.** One should be able to read the code and follow the logic.

[and much more...](./docs/compare.md)

## Installation and usage

All APIs are located in different packages. To install SQS run

```
composer require async-aws/sqs
```

```php
use AsyncAws\Sqs\SqsClient;
use AsyncAws\Sqs\Input\SendMessageRequest;

$sqsClient = new SqsClient([
    'region' => 'eu-central-1',
    'accessKeyId' => 'foo',
    'accessKeySecret' => 'bar',
]);

// Call a client's method with an array
$result = $sqsClient->createQueue(['QueueName' => 'bar']);
// Make sure the request is sent
$result->resolve();


// You can also call a client's method with an input object
$input = new SendMessageRequest();
$input
    ->setQueueUrl('https://foo.com/bar')
    ->setMessageBody('foobar');

$result = $sqsClient->sendMessage($input);

// Request is automatically sent when reading the response
echo $result->getMessageId();
```

## How is it async first?

The secret ingredient in creating asynchronous first is not implemented in this library.
It actually comes from the [Symfony HTTP client](https://symfony.com/doc/current/components/http_client.html).
They have implemented all the cool async features that this AWS library just take
advantage of.

#### So what is this library really doing?

Except for being a wrapper around Symfony's HTTP client and make sure we use the
async features properly, we also [handle authentication](./docs/authentication.md), exceptions
and provide some response objects.

## Pagination

Some API Results are lists of items, like the result of `S3Client::listObjectsV2()`.
These results implement `\IteratorAggregate` and will automatically use AWS's pagination
API to make a new request to fetch the remaining resources in the list.

```php
use AsyncAws\S3\S3Client;
use AsyncAws\S3\Result\AwsObject;
use AsyncAws\S3\Result\CommonPrefix;

$s3Client = new S3Client();
$result = $s3Client->listObjectsV2(['Bucket' => 'foo']);

/** @var AwsObject|CommonPrefix $file */
foreach($result as $file) {
    if ($file instanceof AwsObject) {
        echo $file->getKey();
    }
}
```

## Waiter

Similar to Official AWS PHP SDK, AsyncAws provides waiters to let you wait
until an long operation finished.

```php
// create a queue Async and don't wait for the response.
$sqsClient->createQueue(['QueueName' => 'fooBar']);

$waiter = $sqsClient->queueExists(['QueueName' => 'fooBar']);
echo $waiter->isSuccess(); // false
$waiter->wait();
echo $waiter->isSuccess(); // true
```

[more information about waiters and hasers...](./docs/waiter.md)

## Packages overview

| Package name                                                                  | Badges                                                                                                                                                                                                                                                                                                  | BC check                  |
| ----------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------- |
| [async-aws/core](https://github.com/async-aws/core)                           | [![Latest Stable Version](https://poser.pugx.org/async-aws/core/v/stable)](https://packagist.org/packages/async-aws/core)                            [![Total Downloads](https://poser.pugx.org/async-aws/core/downloads)](https://packagist.org/packages/async-aws/core)                               | [![](https://github.com/async-aws/core/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/core/actions)
| [async-aws/cloud-formation](https://github.com/async-aws/cloud-formation)     | [![Latest Stable Version](https://poser.pugx.org/async-aws/cloud-formation/v/stable)](https://packagist.org/packages/async-aws/cloud-formation)      [![Total Downloads](https://poser.pugx.org/async-aws/cloud-formation/downloads)](https://packagist.org/packages/async-aws/cloud-formation)         | [![](https://github.com/async-aws/cloud-formation/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/cloud-formation/actions)
| [async-aws/dynamo-db](https://github.com/async-aws/dynamo-db)                 | [![Latest Stable Version](https://poser.pugx.org/async-aws/dynamo-db/v/stable)](https://packagist.org/packages/async-aws/dynamo-db)                  [![Total Downloads](https://poser.pugx.org/async-aws/dynamo-db/downloads)](https://packagist.org/packages/async-aws/dynamo-db)                     | [![](https://github.com/async-aws/dynamo-db/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/dynamo-db/actions)
| [async-aws/lambda](https://github.com/async-aws/lambda)                       | [![Latest Stable Version](https://poser.pugx.org/async-aws/lambda/v/stable)](https://packagist.org/packages/async-aws/lambda)                        [![Total Downloads](https://poser.pugx.org/async-aws/lambda/downloads)](https://packagist.org/packages/async-aws/lambda)                           | [![](https://github.com/async-aws/lambda/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/lambda/actions)
| [async-aws/s3](https://github.com/async-aws/s3)                               | [![Latest Stable Version](https://poser.pugx.org/async-aws/s3/v/stable)](https://packagist.org/packages/async-aws/s3)                                [![Total Downloads](https://poser.pugx.org/async-aws/s3/downloads)](https://packagist.org/packages/async-aws/s3)                                   | [![](https://github.com/async-aws/s3/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/s3/actions)
| [async-aws/ses](https://github.com/async-aws/ses)                             | [![Latest Stable Version](https://poser.pugx.org/async-aws/ses/v/stable)](https://packagist.org/packages/async-aws/ses)                              [![Total Downloads](https://poser.pugx.org/async-aws/ses/downloads)](https://packagist.org/packages/async-aws/ses)                                 | [![](https://github.com/async-aws/ses/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/ses/actions)
| [async-aws/sns](https://github.com/async-aws/sns)                             | [![Latest Stable Version](https://poser.pugx.org/async-aws/sns/v/stable)](https://packagist.org/packages/async-aws/sns)                              [![Total Downloads](https://poser.pugx.org/async-aws/sns/downloads)](https://packagist.org/packages/async-aws/sns)                                 | [![](https://github.com/async-aws/sns/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/sns/actions)
| [async-aws/sqs](https://github.com/async-aws/sqs)                             | [![Latest Stable Version](https://poser.pugx.org/async-aws/sqs/v/stable)](https://packagist.org/packages/async-aws/sqs)                              [![Total Downloads](https://poser.pugx.org/async-aws/sqs/downloads)](https://packagist.org/packages/async-aws/sqs)                                 | [![](https://github.com/async-aws/sqs/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/sqs/actions)
| [async-aws/flysystem-s3](https://github.com/async-aws/flysystem-s3)           | [![Latest Stable Version](https://poser.pugx.org/async-aws/flysystem-s3/v/stable)](https://packagist.org/packages/async-aws/flysystem-s3)            [![Total Downloads](https://poser.pugx.org/async-aws/flysystem-s3/downloads)](https://packagist.org/packages/async-aws/flysystem-s3)               | [![](https://github.com/async-aws/flysystem-s3/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/flysystem-s3/actions)
| [async-aws/async-aws-bundle](https://github.com/async-aws/symfony-bundle)     | [![Latest Stable Version](https://poser.pugx.org/async-aws/async-aws-bundle/v/stable)](https://packagist.org/packages/async-aws/async-aws-bundle)    [![Total Downloads](https://poser.pugx.org/async-aws/async-aws-bundle/downloads)](https://packagist.org/packages/async-aws/async-aws-bundle)       | [![](https://github.com/async-aws/symfony-bundle/workflows/BC%20Check/badge.svg?branch=master)](https://github.com/async-aws/symfony-bundle/actions)

The main repository is not tagged and cannot be installed as a composer package.
