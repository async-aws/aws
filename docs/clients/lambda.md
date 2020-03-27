---
layout: client
category: clients
---

# Lambda Client

```bash
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
