---
category: features
---

# Waiter

Similar to Official AWS PHP SDK, AsyncAws provides waiters to let you wait
until an long operation finished.

To ease the development, the above code is wrapped in a helper called "waiter".

```php
use AsyncAws\DynamoDb\DynamoDbClient;

$dynamoDb = new DynamoDbClient();
$dynamoDb->createTable([]);

// Create a new table.
// It normally takes around 5 seconds to complete this action.
$dynamoDb->createTable(['TableName' => 'errors', /* ...*/]);

$waiter = $dynamoDb->tableExists(['TableName' => 'errors']);
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

To make sure to use `wait()` as non blocking. Set the timeout to `0`:

```php
use AsyncAws\DynamoDb\DynamoDbClient;

$dynamoDb = new DynamoDbClient();

$waiter = $dynamoDb->tableExists(['TableName' => 'errors']);
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
