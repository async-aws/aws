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

## Installation and usage

All APIs are located in different packages. To install SQS run

```
composer require working-title/sqs
```

```php
use WorkingTitle\Sqs\SqsClient;
use Symfony\Component\HttpClient\HttpClient;

$sqsClient = new SqsClient(HttpClient::create(), [
    'region' => 'eu-central-1',
    'accessKeyId' => 'foo',
    'accessKeySecret' => 'bar',
]);

$promise = $sqsClient->sendMessage('my_queue', 'foobar');

$x = $promise->resolve();
```


You may also install the base package only. This allows you to do authenticated requests to any endpoint. 

```
composer require working-title/aws
```

```php
use WorkingTitle\Aws\AwsClient;
use Symfony\Component\HttpClient\HttpClient;

$awsClient = new AwsClient(HttpClient::create(), [
    'region' => 'eu-central-1',
    'accessKeyId' => 'foo',
    'accessKeySecret' => 'bar',
]);

$promise = $awsClient->request('POST', [
    'Action' => 'SendMessage',
    'MessageBody' => 'foobar'
], 
[
  /* headers */
], 
 'https://sqs.eu-central-1.amazonaws.com/5555555555/my_queue'
);

$promise->resolve(false);
```
