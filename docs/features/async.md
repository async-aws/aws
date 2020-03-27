---
category: features
---

# Async

TODO: When to call `resolve()`?

## Batch requests

```php
use AsyncAws\Lambda\LambdaClient;
use AsyncAws\Lambda\Input\InvocationRequest;

$lambda = new LambdaClient();

$results = [];
for ($i = 0; $i < 10; ++$i) {
    $results[] = $lambda->invoke(new InvocationRequest([
        'FunctionName' => 'app-dev-hellow_world',
        'Payload' => "{\"name\": $i}",
    ]));
}

while (!empty($results)) {
    foreach ($results as $i => $result) {
        if ($result->resolve(0.01)) {
            echo $result->getPayload();
            unset($results[$i]);
        }
    }
}
```
