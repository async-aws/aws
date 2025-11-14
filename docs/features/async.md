---
category: features
---

# How to work asynchronous

Asynchronous stuff can be confusing, and because of that, it is reasonable to try to stay away from it. But hopefully, using this library will be straightforward. The goal is always to be "async first," but developers don't have to care about async if they don't want to.

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
    ->setQueueUrl('https://sqs.us-east-2.amazonaws.com/123456789012/invoice')
    ->setMessageBody('invoiceId: 1337');

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
    ->setQueueUrl('https://sqs.us-east-2.amazonaws.com/123456789012/invoice')
    ->setMessageBody('invoiceId: 1337');

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
        ->setQueueUrl('https://sqs.us-east-2.amazonaws.com/123456789012/invoice')
        ->setMessageBody('invoiceId: 1337');

    $result = $client->sendMessage($input);

    // 100 lines of business logic
    // No line is using $result
}

$sqs = new SqsClient();
sendSqsMessage($sqs);
```

In this example `$result` is never used. The HTTP request will be sent on `$result->__destruct()`.
But that does not happen until the end of the block, after the 100 lines of business
logic. This can be a bit confusing, especially when an exception is thrown.

This scenario can be solved in a few ways. **Solution A** is the best one to use, but
the others are listed as examples.

#### Solution A

```diff
-    $result = $sqs->sendMessage($input);
+    $sqs->sendMessage($input);
```

#### Solution B

```diff
     $result = $sqs->sendMessage($input);
+    $result->getMessageId();
```

#### Solution C

```diff
     $result = $sqs->sendMessage($input);
+    unset($result);
```

#### Solution D

This solution requires some more explanation. See [below](#using-resolve-function)
for more information.

```diff
     $result = $sqs->sendMessage($input);
+    $result->resolve();
```

## Advanced use cases

The common use cases covers 90% of all code. Let's dig in to the advanced stuff.

### Using resolve-function

The `$result->resolve()` function is making sure the request is executed. There is normally no point in using this function, but it may be handy in some scenarios.

Calling this function with no arguments will either return `true` or throw an exception.
If the function is called again, it will give you the same output without contacting
the remote server again.

The function has a `?float $timeout = null` argument. If the timeout is set to
`2.0`, the HTTP client will wait for 2 seconds for a response. If a response is received,
the function will return `true` or throw an exception. If the timeout is reached,
it will return `false`.

> **Note:** The call `$result->resolve()` is a blocking call because we are waiting
> for the response. Use `$result->resolve(0)` for a non-blocking call.

### Batch requests

Consider the following example. It is creating 10 Lambda `InvocationRequest`s and printing
their result. The HTTP response that is downloaded first will be printed first. The order
the requests are created do not matter.

The `Result::wait()` function will iterate over provided results, and yield
the response as soon as it has been resolved.

The function has a `?float $timeout = null` argument. If the timeout is set to
`2.0`, the HTTP client will wait up to 2 seconds for responses. Each time a response
is received, the function will yield the response. If the timeout is reached, or
all responses are resolved, the loop will stop.

The function has also a `$fullResponse` argument. When `false` (default value)
the HTTP client will wait only for HTTP status code and headers. When `true`,
the HTTP client will wait until receiving the full response body.

```php
use AsyncAws\Core\Result;
use AsyncAws\Lambda\LambdaClient;
use AsyncAws\Lambda\Input\InvocationRequest;

$lambda = new LambdaClient();

$results = [];
for ($i = 0; $i < 10; ++$i) {
    $results[] = $lambda->invoke(new InvocationRequest([
        'FunctionName' => 'app-dev-hello_world',
        'Payload' => "{\"name\": $i}",
    ]));
}

foreach (Result::wait($results, null, true) as $result) {
    echo $result->getPayload();
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

Note that if you do not need to use the result of the HTTP calls, you can skip `Result::wait()`. For example:

```php
use AsyncAws\Lambda\LambdaClient;
use AsyncAws\Lambda\Input\InvocationRequest;

function foo() {
    $lambda = new LambdaClient();

    $results = [];
    for ($i = 0; $i < 10; ++$i) {
        $results[] = $lambda->invoke(new InvocationRequest([
            'FunctionName' => 'app-dev-hello_world',
            'Payload' => "{\"name\": $i}",
        ]));
    }
}
```

By holding all results in the `$results` array, we prevent destructing each response immediately. When `$results` is destructed (at the end of the function), all pending HTTP requests will be awaited.
