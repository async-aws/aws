---
layout: home
---

# AsyncAws client

Are you one of those people that like the Amazon PHP SDK but hate the fact
that you need to download Guzzle, PSR-7 and **every** AWS API client to use it?

This is the library for you!

## What is new?

The official [AWS PHP SDK](https://github.com/aws/aws-sdk-php) is great. It is
feature complete, it supports all Amazon APIs and is maintained by some really talented
developers. This library is different. It is maintained by some dumb and lazy people.

The goals of this client are:

1. **Async first.** Everything is asynchronous and responses are only downloaded
   if needed.
1. **Not feature complete.** AsyncAws is currently covering around 40 AWS
   APIs (the most popular ones). We are happy to generate more features if needed.
   Just ask or [contribute](./contribute/index.md).
1. **Only relevant updates.** Updates are great, but if changes are released multiple
   times every week you cannot keep up-to-date with the changelog. AsyncAws uses
   one package per client so packages are small and the updates are always related
   to your application.
1. **Readable code.** One should be able to read the code and follow the logic.

See [full comparison](./compare.md) between AsyncAws and the official AWS PHP SDK.

## Installation and usage

All APIs are located in different packages. To install SQS run

```shell
composer require async-aws/sqs
```

```php
use AsyncAws\Sqs\SqsClient;
use AsyncAws\Sqs\Input\SendMessageRequest;

$sqs = new SqsClient([
    'region' => 'eu-central-1',
    'accessKeyId' => 'AKIAIOSFODNN7EXAMPLE',
    'accessKeySecret' => 'wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY',
]);

// Call a client's method with an array
$result = $sqs->createQueue(['QueueName' => 'invoice']);

// Request is automatically sent when reading the result
echo $result->getQueueUrl();

// You can also call a client's method with an input object
$input = new SendMessageRequest();
$input
    ->setQueueUrl('https://sqs.us-east-2.amazonaws.com/123456789012/invoice')
    ->setMessageBody('invoiceId: 1337');

// Since the returned value is ignored,
// the HTTP request is sent immediately.
$sqs->sendMessage($input);
```

Continue reading about our different [API clients](/clients/index.md) or select
a topic in the sidebar.

## How is it async first?

The secret ingredient in creating asynchronous first is not implemented in this library.
It actually comes from [Symfony's HTTP client](https://symfony.com/doc/current/components/http_client.html).
They have implemented all the cool asynchronous features that this AWS library just
take advantage of.

#### So what is this library really doing?

Except for being a wrapper around Symfony's HTTP client and making sure the asynchronous
features are properly used, AsyncAws also [handles authentication](./authentication/index.md),
exceptions and provide input and result objects.

Read more about [how to use asynchronous features](./features/async.md).

## Backwards compatibility promise

Backwards compatibility is the most important feature of any open source library.
This project is inspired by Symfony and will strictly follow their process to
keep backwards compatibility.

See [Symfony backwards compatibility promise](https://symfony.com/bc) for more information.
