---
layout: client
category: clients
---

# Lambda Client

## Examples

### Invoke a function

```php
use AsyncAws\Lambda\LambdaClient;

$lambda = new LambdaClient();

// Find FunctionName here: https://console.aws.amazon.com/lambda/home?region=us-east-1#/functions
$result = $lambda->invoke([
  'FunctionName' => 'app-dev-hello_world',
  'Payload' => '{"name": "async-aws/lambda"}',
]);

$result->getPayload();
```
