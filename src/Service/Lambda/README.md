# Async AWS Lambda Client

![](https://github.com/async-aws/lambda/workflows/Tests/badge.svg?branch=master)
![](https://github.com/async-aws/lambda/workflows/BC%20Check/badge.svg?branch=master)

An API client for Lambda.

## Install

```cli
composer require async-aws/lambda
```

## Usage

```cli
export AWS_ACCESS_KEY_ID=AKIAIOSFODNN7EXAMPLE
export AWS_SECRET_ACCESS_KEY=wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY
```
See [docs/authentication.md](https://github.com/async-aws/aws/blob/master/docs/authentication.md) to see how to authenticate against AWS server.

```php
$client = new LambdaClient([
    'region' => 'us-east-1',
]);

$result = $client->invoke([
  'FunctionName' => 'app-dev-hello_world', // Find it here: https://console.aws.amazon.com/lambda/home?region=us-east-1#/functions
  'Payload' => '{"name": "async-aws/lambda"}',
]);

$result->getPayload(); // hello async-aws/lambda
```
