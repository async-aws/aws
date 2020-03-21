<?php

namespace AsyncAws\DynamoDb\Tests\Integration;

use AsyncAws\Core\Credentials\Credentials;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\DynamoDb\DynamoDbClient;
use AsyncAws\DynamoDb\Enum\KeyType;
use AsyncAws\DynamoDb\Enum\ProjectionType;
use AsyncAws\DynamoDb\Input\CreateTableInput;
use AsyncAws\DynamoDb\Input\DeleteItemInput;
use AsyncAws\DynamoDb\Input\DeleteTableInput;
use AsyncAws\DynamoDb\Input\DescribeTableInput;
use AsyncAws\DynamoDb\Input\GetItemInput;
use AsyncAws\DynamoDb\Input\ListTablesInput;
use AsyncAws\DynamoDb\Input\QueryInput;
use AsyncAws\DynamoDb\Input\ScanInput;
use AsyncAws\DynamoDb\Input\UpdateItemInput;
use AsyncAws\DynamoDb\Input\UpdateTableInput;
use AsyncAws\DynamoDb\ValueObject\AttributeDefinition;
use AsyncAws\DynamoDb\ValueObject\KeySchemaElement;
use AsyncAws\DynamoDb\ValueObject\LocalSecondaryIndex;
use AsyncAws\DynamoDb\ValueObject\Projection;
use AsyncAws\DynamoDb\ValueObject\ProvisionedThroughput;
use AsyncAws\DynamoDb\ValueObject\Tag;

class DynamoDbClientTest extends TestCase
{
    /**
     * @var string
     */
    private $tableName;

    /**
     * @var DynamoDbClient|null
     */
    private $client;

    /**
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/APIReference/API_CreateTable.html#API_CreateTable_Examples
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/APIReference/API_PutItem.html#API_PutItem_Examples
     */
    public function setUp(): void
    {
        $client = $this->getClient();

        $this->tableName = 'Thread' . rand(1, 100000000);
        $input = new CreateTableInput([
            'AttributeDefinitions' => [
                new AttributeDefinition(['AttributeName' => 'ForumName', 'AttributeType' => 'S']),
                new AttributeDefinition(['AttributeName' => 'Subject', 'AttributeType' => 'S']),
                new AttributeDefinition(['AttributeName' => 'LastPostDateTime', 'AttributeType' => 'S']),
            ],
            'TableName' => $this->tableName,
            'KeySchema' => [
                new KeySchemaElement(['AttributeName' => 'ForumName', 'KeyType' => KeyType::HASH]),
                new KeySchemaElement(['AttributeName' => 'Subject', 'KeyType' => KeyType::RANGE]),
            ],
            'LocalSecondaryIndexes' => [
                new LocalSecondaryIndex([
                    'IndexName' => 'LastPostIndex',
                    'KeySchema' => [
                        new KeySchemaElement(['AttributeName' => 'ForumName', 'KeyType' => KeyType::HASH]),
                        new KeySchemaElement(['AttributeName' => 'LastPostDateTime', 'KeyType' => KeyType::RANGE]),
                    ],
                    'Projection' => new Projection(['ProjectionType' => ProjectionType::KEYS_ONLY]),
                ]),
            ],
            'ProvisionedThroughput' => new ProvisionedThroughput([
                'ReadCapacityUnits' => 5,
                'WriteCapacityUnits' => 5,
            ]),
            'Tags' => [
                new Tag(['Key' => 'Owner', 'Value' => 'BlueTeam']),
            ],
        ]);
        $result = $client->CreateTable($input);

        $result->resolve();

        // Add some data

        $client->PutItem([
            'TableName' => $this->tableName,
            'Item' => [
                'LastPostDateTime' => ['S' => '201303190422'],
                'Tags' => ['SS' => ['Update', 'Multiple Items', 'HelpMe']],
                'ForumName' => ['S' => 'Amazon DynamoDB'],
                'Message' => ['S' => 'I want to update multiple items in a single call. What\'s the best way to do that?'],
                'Subject' => ['S' => 'How do I update multiple items?'],
                'LastPostedBy' => ['S' => 'fred@example.com'],
            ],
            'ConditionExpression' => 'ForumName <> :f and Subject <> :s',
            'ExpressionAttributeValues' => [
                ':f' => ['S' => 'Amazon DynamoDB'],
                ':s' => ['S' => 'How do I update multiple items?'],
            ],
        ]);

        $client->PutItem([
            'TableName' => $this->tableName,
            'Item' => [
                'LastPostDateTime' => ['S' => '201303190422'],
                'Tags' => ['SS' => ['Update', 'Multiple Items', 'HelpMe']],
                'ForumName' => ['S' => 'Amazon DynamoDB'],
                'Message' => ['S' => 'What is the maximum number of items?'],
                'Subject' => ['S' => 'Maximum number of items?'],
                'LastPostedBy' => ['S' => 'fred@example.com'],
            ],
            'ConditionExpression' => 'ForumName <> :f and Subject <> :s',
            'ExpressionAttributeValues' => [
                ':f' => ['S' => 'Amazon DynamoDB'],
                ':s' => ['S' => 'How do I update multiple items?'],
            ],
        ]);
    }

    public function tearDown(): void
    {
        $client = $this->getClient();

        $input = new DeleteTableInput(['TableName' => $this->tableName]);
        $result = $client->DeleteTable($input);

        $result->resolve();
    }

    public function testCreateTable(): void
    {
        self::markTestSkipped('This is tested in setUp()');
    }

    public function testDeleteItem(): void
    {
        $client = $this->getClient();

        $input = new DeleteItemInput([
            'TableName' => $this->tableName,
            'Key' => [
                'ForumName' => ['S' => 'Amazon DynamoDB'],
                'Subject' => ['S' => 'How do I update multiple items?'],
            ],
            'ReturnValues' => 'ALL_OLD',
            'ConditionExpression' => 'attribute_not_exists(Replies)',
        ]);
        $result = $client->DeleteItem($input);

        $result->resolve();

        self::assertEquals(200, $result->info()['status']);
    }

    public function testDeleteTable(): void
    {
        self::markTestSkipped('This is tested in tearDown()');
    }

    public function testDescribeTable(): void
    {
        $client = $this->getClient();

        $input = new DescribeTableInput([
            'TableName' => $this->tableName,
        ]);
        $result = $client->DescribeTable($input);

        self::assertEquals($this->tableName, $result->getTable()->getTableName());
    }

    public function testGetItem(): void
    {
        $client = $this->getClient();

        $input = new GetItemInput([
            'TableName' => $this->tableName,
            'Key' => [
                'ForumName' => ['S' => 'Amazon DynamoDB'],
                'Subject' => ['S' => 'How do I update multiple items?'],
            ],
            'ConsistentRead' => true,
            'ReturnConsumedCapacity' => 'TOTAL',
            'ProjectionExpression' => 'LastPostDateTime, Message, Tags',
        ]);
        $result = $client->GetItem($input);

        self::assertArrayHasKey('Message', $result->getItem());
        self::assertEquals('I want to update multiple items in a single call. What\'s the best way to do that?', $result->getItem()['Message']->getS());
    }

    public function testListTables(): void
    {
        $client = $this->getClient();

        $input = new ListTablesInput([
            'ExclusiveStartTableName' => 'Thr',
            'Limit' => 5,
        ]);
        $result = $client->ListTables($input);

        $names = iterator_to_array($result->getTableNames(true));
        self::assertTrue(\count($names) >= 0);
    }

    public function testPutItem(): void
    {
        self::markTestSkipped('This is tested in setUp()');
    }

    public function testQuery(): void
    {
        $client = $this->getClient();

        $input = new QueryInput([
            'TableName' => $this->tableName,
            'ConsistentRead' => true,
            'KeyConditionExpression' => 'ForumName = :val',
            'ExpressionAttributeValues' => [':val' => ['S' => 'Amazon DynamoDB']],
        ]);
        $result = $client->Query($input);

        self::assertSame(2, $result->getCount());
        self::assertSame(2, $result->getScannedCount());
    }

    public function testScan(): void
    {
        $client = $this->getClient();

        $input = new ScanInput([
            'TableName' => $this->tableName,
            'ReturnConsumedCapacity' => 'TOTAL',
        ]);
        $result = $client->Scan($input);

        self::assertSame(2, $result->getCount());
        self::assertSame(2, $result->getScannedCount());
    }

    public function testUpdateItem(): void
    {
        $client = $this->getClient();

        $input = new UpdateItemInput([
            'TableName' => $this->tableName,
            'Key' => [
                'ForumName' => ['S' => 'Amazon DynamoDB'],
                'Subject' => ['S' => 'Maximum number of items?'],
            ],
            'UpdateExpression' => 'set LastPostedBy = :val1',
            'ConditionExpression' => 'LastPostedBy = :val2',
            'ExpressionAttributeValues' => [
                ':val1' => ['S' => 'alice@example.com'],
                ':val2' => ['S' => 'fred@example.com'],
            ],
            'ReturnValues' => 'ALL_NEW',
        ]);
        $result = $client->UpdateItem($input);

        $result->resolve();
        self::assertEquals(200, $result->info()['status']);
    }

    public function testUpdateTable(): void
    {
        $client = $this->getClient();

        $input = new UpdateTableInput([
            'TableName' => $this->tableName,
            'ProvisionedThroughput' => new ProvisionedThroughput([
                'ReadCapacityUnits' => 7,
                'WriteCapacityUnits' => 8,
            ]),
        ]);
        $result = $client->UpdateTable($input);

        self::assertEquals(7, $result->getTableDescription()->getProvisionedThroughput()->getReadCapacityUnits());
        self::assertEquals(8, $result->getTableDescription()->getProvisionedThroughput()->getWriteCapacityUnits());
    }

    public function testTableExists(): void
    {
        $client = $this->getClient();

        $input = new DescribeTableInput([
            'TableName' => $this->tableName,
        ]);

        self::assertTrue($client->tableExists($input)->isSuccess());
        self::assertFalse($client->tableExists(['TableName' => 'does-not-exists'])->isSuccess());
    }

    public function testTableNotExists(): void
    {
        $client = $this->getClient();

        $input = new DescribeTableInput([
            'TableName' => $this->tableName,
        ]);

        self::assertFalse($client->tableNotExists($input)->isSuccess());
        self::assertTrue($client->tableNotExists(['TableName' => 'does-not-exists'])->isSuccess());
    }

    private function getClient(): DynamoDbClient
    {
        if ($this->client instanceof DynamoDbClient) {
            return $this->client;
        }

        return $this->client = new DynamoDbClient([
            'endpoint' => 'http://localhost:8000',
        ], new Credentials('aws_id', 'aws_secret'));
    }
}
