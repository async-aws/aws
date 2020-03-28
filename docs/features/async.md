---
category: features
---

# Async

Asynchronous stuff can be confusing and because of that it is normal to try to stay
away from it. But hopefully, using this library will straight forward. The goal is
to always be "async first" but developers don't have to care about async if they
don't want to.

## Normal use cases

Let's show some examples to better understand how this library works.

### Ignoring results

Consider the following example:

```php
use AsyncAws\Sqs\SqsClient;
use AsyncAws\Sqs\Input\SendMessageRequest;

$sqs = new SqsClient();

$input = new SendMessageRequest();
$input
    ->setQueueUrl('https://foo.com/bar')
    ->setMessageBody('foobar');

$sqs->sendMessage($input);
```

The HTTP request is sent and a HTTP response is received. Everything works as expected.

A small bonus is that the full HTTP response is not downloaded. Only the first line
with the status code is fetched from the server.

### Getting data from result

The example below is slightly different.

```php
use AsyncAws\Sqs\SqsClient;
use AsyncAws\Sqs\Input\SendMessageRequest;

$sqs = new SqsClient();

$input = new SendMessageRequest();
$input
    ->setQueueUrl('https://foo.com/bar')
    ->setMessageBody('foobar');

$result = $sqs->sendMessage($input);

echo $result->getMessageId();
```

The HTTP request is not actually sent until the properties on `$result` are accessed.
On the first call to a getter, the HTTP response will be downloaded and the body
will be parsed.

This behavior can be observed when using a debugger like xDebug.

### Not using the result

Consider this rare scenario:

```php
use AsyncAws\Sqs\SqsClient;
use AsyncAws\Sqs\Input\SendMessageRequest;

function sendSqsMessage($client) {
    $input = new SendMessageRequest();
    $input
        ->setQueueUrl('https://foo.com/bar')
        ->setMessageBody('foobar');

    $result = $client->sendMessage($input);

    // 100 lines of business logic
    // No line is using $result
}

$sqs = new SqsClient();
sendSqsMessage($sqs);
```

In this example `$result` is never used. The HTTP request will be send on `$result->__desctuct()`.
But that does not happen until the end of the block, after the 100 lines of business
logic. This can be a bit confusing.

This scenario can be solved in a few ways. **Solution A** is the best one to use but
the others are listed as examples.

#### Solution A

```diff
-    $result = $sqs->sendMessage($input);
+    $sqs->sendMessage($input);
```

#### Solution B

```diff
    $result = $sqs->sendMessage($input);
+   $result->getMessageId();
```

#### Solution C

```diff
    $result = $sqs->sendMessage($input);
+   unset($result);
```

#### Solution D

This solution requires some more explanation. See [below](#using-resolve-function)
for more info.

```diff
    $result = $sqs->sendMessage($input);
+   $result->resolve();
```

## Advanced use cases

The normal use cases covers 90% of all code. Let's dig in to the advanced stuff.

### Using resolve-function

The `$result->resolve()` function is making sure the request is executed. There is
normally no point in using this function, but in some scenarios it may be handy.

Calling this function with no arguments will either return `true` or throw an exception.
If the function is called again, it will give you the same output without contacting
the remote server again.

The function has a `?float $timeout = null` argument. If the timeout is set to
`2.0`, we will wait for 2 seconds for a response. If a response is received, the function
will return `true` or thrown an exception. If the timeout is reached, it will return
`false`.

Use `$result->resolve(0)` for a non-blocking call.

### Batch requests

With the knowledge of the resolve function we can do some cool stuff. In this example
we are creating 10 `InvocationRequest`s and printing their result. The result
that is downloaded first will be printed first. The order the requests are created
do not matter.

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

The pseudo code at the lambda function:

```php
return function ($event) {
    $rand = rand (1, 4);
    sleep($rand);

    return 'Name ' . ($event['name']) . ': ' . $rand . 's';
};
```

The output will look like:

```text
Name 4: 1s
Name 2: 1s
Name 6: 1s
Name 3: 2s
Name 9: 2s
Name 7: 2s
Name 8: 3s
Name 5: 3s
Name 0: 4s
Name 1: 4s
```
