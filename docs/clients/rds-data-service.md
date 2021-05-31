---
layout: client
category: clients
name: RdsDataService
package: async-aws/rds-data-service
---

## Usage

### Execute an sql statement

```php
use AsyncAws\RdsDataService\RdsDataServiceClient;

$client = new RdsDataServiceClient();
$response = $client->executeStatement([
    'database' => 'my_database',
    'resourceArn' => 'arn:resource',
    'secretArn' => 'arn:secret',
    'sql' => 'SELECT name FROM users WHERE id = :id',
    'parameters' => [
        ['name' => 'id', 'value' => ['longValue' => 5]],
    ]
]);

foreach ($response->getRecords() as $record) {
    echo "name: " . $record[0]->getStringValue() . PHP_EOL;
}
```


### Run a transaction

```php

use AsyncAws\RdsDataService\RdsDataServiceClient;

$database = [
    'database' => 'my_database',
    'resourceArn' => 'arn:resource',
    'secretArt' => 'arn:secret',
];

$client = new RdsDataServiceClient();
$transaction = $client->beginTransaction($database);

try {
    $result = $client->executeStatement($database + [
        'transaction' => $transaction->getTransactionId(),
        'sql' => 'SELECT age FROM users WHERE id = :id FOR UPDATE',
        'parameters' => [
            ['name' => 'id', 'value' => ['longValue' => 5]],
        ]
    ]);

    $user = $result->getRecords()[0] ?? null;
    if ($user === null) {
        throw new \RuntimeException("User 5 not found.");
    }

    $newAge = $user[0]->getLongValue() + 1;
    $client->executeStatement($database + [
        'transaction' => $transaction->getTransactionId(),
        'sql' => 'UPDATE users SET age = :new_age WHERE id = :id',
        'parameters' => [
            ['name' => 'id', 'value' => ['longValue' => 5]],
            ['name' => 'new_age', 'value' => ['longValue' => $newAge]],
        ]
    ]);

    $client->commitTransaction($database + [
        'transaction' => $transaction->getTransactionId(),
    ]);
} catch (\Throwable $e) {
    // Make sure to allways rollback since there is no connection.
    // If you forget than the transaction might block tables for up to 5 minutes.
    $client->rollbackTransaction($database + [
        'transaction' => $transaction->getTransactionId(),
    ]);
    throw $e;
}
```

###
