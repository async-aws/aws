---
layout: client
category: clients
---

# DynamoDb Client

## Examples

### Create a table

```php
use AsyncAws\DynamoDb\DynamoDbClient;
use AsyncAws\DynamoDb\Input\CreateTableInput;
use AsyncAws\DynamoDb\Input\DescribeTableInput;
use AsyncAws\DynamoDb\ValueObject\AttributeDefinition;
use AsyncAws\DynamoDb\ValueObject\ProvisionedThroughput;

$dynamoDb = new DynamoDbClient();

$dynamoDb->createTable(new CreateTableInput([
        'TableName' => 'errors',
        'AttributeDefinitions' => [
            new AttributeDefinition(['AttributeName' => 'id', 'AttributeType' => 'N']),
            new AttributeDefinition(['AttributeName' => 'time', 'AttributeType' => 'N']),
        ],
        'KeySchema' => [
            new AttributeDefinition(['AttributeName' => 'id', 'KeyType' => 'HASH']),
            new AttributeDefinition(['AttributeName' => 'time', 'KeyType' => 'RANGE']),
        ],
        'ProvisionedThroughput' => new ProvisionedThroughput([
            'ReadCapacityUnits' => 10,
            'WriteCapacityUnits' => 20
        ]),
]));

// Wait for end of creation
$dynamoDb->tableExists(new DescribeTableInput(['TableName' => 'errors']))->wait();
```

### Update a table

```php
use AsyncAws\DynamoDb\DynamoDbClient;
use AsyncAws\DynamoDb\Input\UpdateTableInput;
use AsyncAws\DynamoDb\ValueObject\ProvisionedThroughput;

$dynamoDb = new DynamoDbClient();

$dynamoDb->updateTable(new UpdateTableInput([
        'TableName' => 'errors',
        'ProvisionedThroughput' => new ProvisionedThroughput([
            'ReadCapacityUnits' => 15,
            'WriteCapacityUnits' => 25
        ]),
]));
```

### Insert an item.

```php
use AsyncAws\DynamoDb\DynamoDbClient;
use AsyncAws\DynamoDb\Input\PutItemInput;
use AsyncAws\DynamoDb\ValueObject\AttributeValue;

$dynamoDb = new DynamoDbClient();

$result = $dynamoDb->putItem(new PutItemInput([
    'TableName' => 'errors',
    'Item' => [
        'id' => new AttributeValue(['N' => '1337']),
        'time' => new AttributeValue(['N' => (string) time()]),
        'error' => new AttributeValue(['S' => 'Executive overflow']),
        'message' => new AttributeValue(['S' => 'no vacant areas']),
    ],
]));

echo 'Consumed capacity: ' . ($result->getConsumedCapacity() ? $result->getConsumedCapacity()->getCapacityUnits() : null);
```

### Get table information

```php
use AsyncAws\DynamoDb\DynamoDbClient;
use AsyncAws\DynamoDb\Input\DescribeTableInput;

$dynamoDb = new DynamoDbClient();

$result = $dynamoDb->describeTable(new DescribeTableInput([
    'TableName' => 'errors',
]));

echo 'Item count: ' . $result->getTable()->getItemCount();
echo 'Read capacity: ' . $result->getTable()->getProvisionedThroughput()->getReadCapacityUnits() . "\n";
```

### Get an item.

```php
use AsyncAws\DynamoDb\DynamoDbClient;
use AsyncAws\DynamoDb\Input\GetItemInput;
use AsyncAws\DynamoDb\ValueObject\AttributeValue;

$dynamoDb = new DynamoDbClient();

$result = $dynamoDb->getItem(new GetItemInput([
    'TableName' => 'errors',
    'ConsistentRead' => true,
    'Key' => [
        'id' => new AttributeValue(['N' => '1201']),
        'time' => new AttributeValue(['N' => '1585503599']),
    ],
]));

echo 'Id: '.$result->getItem()['id']->getN() . PHP_EOL;
echo 'Error: '.$result->getItem()['error']->getS() . PHP_EOL;
echo 'Message: '.$result->getItem()['message']->getS() . PHP_EOL;
```
