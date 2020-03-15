<?php

namespace AsyncAws\DynamoDb\Tests\Unit;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\DynamoDb\DynamoDbClient;
use AsyncAws\DynamoDb\Enum\KeyType;
use AsyncAws\DynamoDb\Input\CreateTableInput;
use AsyncAws\DynamoDb\Input\DeleteItemInput;
use AsyncAws\DynamoDb\Input\DeleteTableInput;
use AsyncAws\DynamoDb\Input\DescribeTableInput;
use AsyncAws\DynamoDb\Input\GetItemInput;
use AsyncAws\DynamoDb\Input\ListTablesInput;
use AsyncAws\DynamoDb\Input\PutItemInput;
use AsyncAws\DynamoDb\Input\QueryInput;
use AsyncAws\DynamoDb\Input\ScanInput;
use AsyncAws\DynamoDb\Input\UpdateItemInput;
use AsyncAws\DynamoDb\Input\UpdateTableInput;
use AsyncAws\DynamoDb\Result\CreateTableOutput;
use AsyncAws\DynamoDb\Result\DeleteItemOutput;
use AsyncAws\DynamoDb\Result\DeleteTableOutput;
use AsyncAws\DynamoDb\Result\DescribeTableOutput;
use AsyncAws\DynamoDb\Result\GetItemOutput;
use AsyncAws\DynamoDb\Result\ListTablesOutput;
use AsyncAws\DynamoDb\Result\PutItemOutput;
use AsyncAws\DynamoDb\Result\QueryOutput;
use AsyncAws\DynamoDb\Result\ScanOutput;
use AsyncAws\DynamoDb\Result\UpdateItemOutput;
use AsyncAws\DynamoDb\Result\UpdateTableOutput;
use AsyncAws\DynamoDb\ValueObject\KeySchemaElement;
use Symfony\Component\HttpClient\MockHttpClient;

class DynamoDbClientTest extends TestCase
{
    public function testCreateTable(): void
    {
        $client = new DynamoDbClient([], new NullProvider(), new MockHttpClient());

        $input = new CreateTableInput([
            'TableName' => 'Foobar',
            'KeySchema' => [
                new KeySchemaElement(['AttributeName' => 'ForumName', 'KeyType' => KeyType::HASH]),
                new KeySchemaElement(['AttributeName' => 'Subject', 'KeyType' => KeyType::RANGE]),
            ],

        ]);
        $result = $client->CreateTable($input);

        self::assertInstanceOf(CreateTableOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testDeleteItem(): void
    {
        $client = new DynamoDbClient([], new NullProvider(), new MockHttpClient());

        $input = new DeleteItemInput([
            'TableName' => 'Foobar',

        ]);
        $result = $client->DeleteItem($input);

        self::assertInstanceOf(DeleteItemOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testDeleteTable(): void
    {
        $client = new DynamoDbClient([], new NullProvider(), new MockHttpClient());

        $input = new DeleteTableInput([
            'TableName' => 'Foobar',
        ]);
        $result = $client->DeleteTable($input);

        self::assertInstanceOf(DeleteTableOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testDescribeTable(): void
    {
        $client = new DynamoDbClient([], new NullProvider(), new MockHttpClient());

        $input = new DescribeTableInput([
            'TableName' => 'Foobar',
        ]);
        $result = $client->DescribeTable($input);

        self::assertInstanceOf(DescribeTableOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testGetItem(): void
    {
        $client = new DynamoDbClient([], new NullProvider(), new MockHttpClient());

        $input = new GetItemInput([
            'TableName' => 'Foobar',

        ]);
        $result = $client->GetItem($input);

        self::assertInstanceOf(GetItemOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testListTables(): void
    {
        $client = new DynamoDbClient([], new NullProvider(), new MockHttpClient());

        $input = new ListTablesInput([

        ]);
        $result = $client->ListTables($input);

        self::assertInstanceOf(ListTablesOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testPutItem(): void
    {
        $client = new DynamoDbClient([], new NullProvider(), new MockHttpClient());

        $input = new PutItemInput([
            'TableName' => 'Foobar',

        ]);
        $result = $client->PutItem($input);

        self::assertInstanceOf(PutItemOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testQuery(): void
    {
        $client = new DynamoDbClient([], new NullProvider(), new MockHttpClient());

        $input = new QueryInput([
            'TableName' => 'Foobar',

        ]);
        $result = $client->Query($input);

        self::assertInstanceOf(QueryOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testScan(): void
    {
        $client = new DynamoDbClient([], new NullProvider(), new MockHttpClient());

        $input = new ScanInput([
            'TableName' => 'Foobar',

        ]);
        $result = $client->Scan($input);

        self::assertInstanceOf(ScanOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testUpdateItem(): void
    {
        $client = new DynamoDbClient([], new NullProvider(), new MockHttpClient());

        $input = new UpdateItemInput([
            'TableName' => 'Foobar',

        ]);
        $result = $client->UpdateItem($input);

        self::assertInstanceOf(UpdateItemOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testUpdateTable(): void
    {
        $client = new DynamoDbClient([], new NullProvider(), new MockHttpClient());

        $input = new UpdateTableInput([
            'TableName' => 'Foobar',
        ]);
        $result = $client->UpdateTable($input);

        self::assertInstanceOf(UpdateTableOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
