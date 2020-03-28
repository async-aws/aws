---
layout: home
---

# AsyncAWS client

If you are one of those people that like the Amazon PHP SDK but hate the fact
that you need to download Guzzle, PSR-7 and **every** AWS API client to use it?

This is the library for you!

## What is new?

The official [AWS PHP SDK](https://github.com/aws/aws-sdk-php) is great. It is
feature complete, it supports all Amazon APIs and is maintained by some really talented
developers. This library is different. It is maintained by some dumb and lazy people.

The goals of this client are:

1. **Async first.** Everything is asynchronous and responses are only downloaded if needed.
1. **Not feature complete.** We are only covering the handful of services that are used by most people. We are not even covering all of those popular services.
1. **No frequent updates.** Updates are great, but if changes are released multiple times every week you cannot keep up-to-date with the changelog. That is especially annoying when the changes are not related to services you use.
1. **Readable code.** One should be able to read the code and follow the logic.

See [full comparison](./compare.md).

## How is it async first?

The secret ingredient in creating asynchronous first is not implemented in this library.
It actually comes from the [Symfony HTTP client](https://symfony.com/doc/current/components/http_client.html).
They have implemented all the cool async features that this AWS library just take
advantage of.

#### So what is this library really doing?

Except for being a wrapper around Symfony's HTTP client and make sure we use the
async features properly, we also [handle authentication](./authtentication/authentication.md), exceptions
and provide some response objects.

Read more about the async features [here](./features/async.md).


## Installation and usage

All APIs are located in different packages. To install SQS run

```shell
$ composer require async-aws/sqs
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

// Request is automatically sent when reading the result
echo $result->getQueueUrl();

// You can also call a client's method with an input object
$input = new SendMessageRequest();
$input
    ->setQueueUrl('https://foo.com/bar')
    ->setMessageBody('foobar');

// Since we dont use a $result to get the return value,
// the HTTP request is sent automatically.
$sqsClient->sendMessage($input);

```
