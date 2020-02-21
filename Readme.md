# Async AWS client

If you are one of those people that love the Amazon PHP SDK but hate the fact
that you need to download Guzzle, PSR-7 and **every** AWS API client to use it?

This is the library for you!

## What is new?

The official [AWS PHP SDK](https://github.com/aws/aws-sdk-php) is great. They are
feature complete, support all Amazon APIs and is maintained by some really talented
developers. This library is different. It is maintained by some fat and lazy people.

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

Similar to Official AWS PHP SDK, Async-Aws provides waiters to let you wait
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

## Organization

| Repository | Namespace | Package name |
| ---------- | --------- | ------------ |
| async-aws/aws | AsyncAws | async-aws/async-aws
| async-aws/core | AsyncAws\Core | async-aws/core
| async-aws/s3 | AsyncAws\S3 | async-aws/s3
| async-aws/ses | AsyncAws\Ses | async-aws/ses
| async-aws/sqs | AsyncAws\Sqs | async-aws/sqs
| async-aws/symfony | AsyncAws\Symfony | async-aws/async-aws-bundle
