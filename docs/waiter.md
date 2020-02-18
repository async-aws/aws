# Waiter and Haser

Similar to Official AWS PHP SDK, Async-Aws provides waiters to let you wait
until an long operation finished.

## Haser

Clients provides "blocking" methods that return whether or not the operation is
successful.

```php
// create a queue Async and don't wait for the response.
$sqsClient->createQueue(['QueueName' => 'fooBar']);

while (!$sqsClient->queueExists(['QueueName' => 'fooBar'])) {
    // ...
    sleep(1);
}
```

## Waiter

To ease the development, the above code is wrapped in a helper called "waiter".

```php
// create a queue Async and don't wait for the response.
$sqsClient->createQueue(['QueueName' => 'fooBar']);

$sqsClient->waitForQueueExists(['QueueName' => 'fooBar']);
```
