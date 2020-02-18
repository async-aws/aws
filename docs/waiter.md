# Waiter and Haser

Similar to Official AWS PHP SDK, Async-Aws provides waiters to let you wait
until an long operation finished.

To ease the development, the above code is wrapped in a helper called "waiter".

```php
// create a queue Async and don't wait for the response.
$sqsClient->createQueue(['QueueName' => 'fooBar']);

$waiter = $sqsClient->queueExists(['QueueName' => 'fooBar']);

echo $waiter->isSuccess(); // false

$waiter->wait();

echo $waiter->isSuccess(); // true
```

A waiter provides methods that let you check the status of the operation.
* `isSuccess()` returns true when operation is successful
* `isFailure()` returns true when operation failed
* `isPending()` returns true when the state of the operation is not yet determinate
* `getState()` returns the state of the operation either `sucess`, `failure` or `pending`
* `wait($timeout, $delay)` waits until the state of the operation is determinate.

As usual, Async-Aws is async and not blocking by default:

```php
$waiter = $sqsClient->queueExists(['QueueName' => 'fooBar']);
while(true) {
    if ($waiter->wait(0)) {
        // When method `wait` returns true, the state is resolved.
        break;
    }

    // perform business logic
    sleep(1);
}

echo $waiter->isSuccess();
```
