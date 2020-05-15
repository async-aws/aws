<?php

namespace AsyncAws\DynamoDbSession\Tests;

use AsyncAws\Core\Test\ResultMockFactory;
use AsyncAws\DynamoDb\DynamoDbClient;
use AsyncAws\DynamoDb\Result\DescribeTableOutput;
use AsyncAws\DynamoDb\Result\GetItemOutput;
use AsyncAws\DynamoDb\ValueObject\AttributeValue;
use AsyncAws\DynamoDb\ValueObject\TableDescription;
use AsyncAws\DynamoDbSession\SessionHandler;
use PHPUnit\Framework\TestCase;

class SessionHandlerTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|DynamoDbClient
     */
    private $client;

    /**
     * @var SessionHandler
     */
    private $handler;

    protected function setUp(): void
    {
        $this->client = $this->getMockBuilder(DynamoDbClient::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->handler = new SessionHandler($this->client, ['table_name' => 'testTable', 'session_lifetime' => 86400]);

        $this->handler->open(null, 'PHPSESSID');
    }

    public function testClose()
    {
        $this->client
            ->expects(self::once())
            ->method('updateItem')
            ->with(self::equalTo([
                'TableName' => 'testTable',
                'Key' => [
                    'id' => ['S' => 'PHPSESSID'],
                ],
                'AttributeUpdates' => [
                    'expires' => ['Value' => ['N' => time() + 86400]],
                ],
            ], 10));

        self::assertTrue($this->handler->close());
    }

    public function testDestroy()
    {
        $this->client
            ->expects(self::once())
            ->method('deleteItem')
            ->with(self::equalTo([
                'TableName' => 'testTable',
                'Key' => [
                    'id' => [
                        'S' => 'PHPSESSID_123456789',
                    ],
                ],
            ]));

        self::assertTrue($this->handler->destroy('123456789'));
    }

    public function testRead()
    {
        $this->client
            ->expects(self::once())
            ->method('getItem')
            ->with(self::equalTo([
                'TableName' => 'testTable',
                'Key' => [
                    'id' => [
                        'S' => 'PHPSESSID_123456789',
                    ],
                ],
                'ConsistentRead' => true,
            ]))
            ->willReturn(ResultMockFactory::create(GetItemOutput::class, [
                'Item' => [
                    'data' => new AttributeValue(['S' => 'test data']),
                    'expires' => new AttributeValue(['N' => time() + 86400]),
                ],
            ]));

        $this::assertEquals('test data', $this->handler->read('123456789'));
    }

    public function testWrite()
    {
        $this->client
            ->expects(self::once())
            ->method('updateItem')
            ->with(self::equalTo([
                'TableName' => 'testTable',
                'Key' => [
                    'id' => ['S' => 'PHPSESSID_123456789'],
                ],
                'AttributeUpdates' => [
                    'expires' => ['Value' => ['N' => time() + 86400]],
                    'data' => ['Value' => ['S' => 'test data']],
                ],
            ], 10));

        self::assertTrue($this->handler->write('123456789', 'test data'));
    }

    public function testWriteWithUnchangedData()
    {
        $this->client
            ->method('getItem')
            ->willReturn(ResultMockFactory::create(GetItemOutput::class, [
                'Item' => [
                    'data' => new AttributeValue(['S' => 'previous data']),
                    'expires' => new AttributeValue(['N' => time() + 86400]),
                ],
            ]));

        $this->client
            ->expects(self::once())
            ->method('updateItem')
            ->with(self::equalTo([
                'TableName' => 'testTable',
                'Key' => [
                    'id' => ['S' => 'PHPSESSID_123456789'],
                ],
                'AttributeUpdates' => [
                    'expires' => ['Value' => ['N' => time() + 86400]],
                ],
            ], 10));

        $this->handler->read('123456789');

        self::assertTrue($this->handler->write('123456789', 'previous data'));
    }

    public function testWriteWithChangedData()
    {
        $this->client
            ->method('getItem')
            ->willReturn(ResultMockFactory::create(GetItemOutput::class, [
                'Item' => [
                    'data' => new AttributeValue(['S' => 'previous data']),
                    'expires' => new AttributeValue(['N' => time() + 86400]),
                ],
            ]));

        $this->client
            ->expects(self::once())
            ->method('updateItem')
            ->with(self::equalTo([
                'TableName' => 'testTable',
                'Key' => [
                    'id' => ['S' => 'PHPSESSID_123456789'],
                ],
                'AttributeUpdates' => [
                    'expires' => ['Value' => ['N' => time() + 86400]],
                    'data' => ['Value' => ['S' => 'new data']],
                ],
            ], 10));

        $this->handler->read('123456789');

        self::assertTrue($this->handler->write('123456789', 'new data'));
    }

    public function testWriteTwice()
    {
        $this->client
            ->method('getItem')
            ->willReturn(ResultMockFactory::create(GetItemOutput::class, [
                'Item' => [
                    'data' => new AttributeValue(['S' => 'previous data']),
                    'expires' => new AttributeValue(['N' => time() + 86400]),
                ],
            ]));

        $this->client
            ->expects(self::exactly(2))
            ->method('updateItem')
            ->withConsecutive(
                [self::equalTo([
                    'TableName' => 'testTable',
                    'Key' => [
                        'id' => ['S' => 'PHPSESSID_123456789'],
                    ],
                    'AttributeUpdates' => [
                        'expires' => ['Value' => ['N' => time() + 86400]],
                        'data' => ['Value' => ['S' => 'new data']],
                    ],
                ], 10)],
                [self::equalTo([
                    'TableName' => 'testTable',
                    'Key' => [
                        'id' => ['S' => 'PHPSESSID_123456789'],
                    ],
                    'AttributeUpdates' => [
                        'expires' => ['Value' => ['N' => time() + 86400]],
                        'data' => ['Value' => ['S' => 'previous data']],
                    ],
                ], 10)]
            );

        $this->handler->read('123456789');
        $this->handler->write('123456789', 'new data');
        $this->handler->write('123456789', 'previous data');
    }

    public function testSetUp()
    {
        $this->client
            ->expects(self::once())
            ->method('createTable')
            ->with(self::equalTo([
                'TableName' => 'testTable',
                'BillingMode' => 'PAY_PER_REQUEST',
                'AttributeDefinitions' => [
                    [
                        'AttributeName' => 'id',
                        'AttributeType' => 'S',
                    ],
                ],
                'KeySchema' => [
                    [
                        'AttributeName' => 'id',
                        'KeyType' => 'HASH',
                    ],
                ],
            ]));

        $this->client
            ->expects(self::exactly(2))
            ->method('describeTable')
            ->with(self::equalTo(['TableName' => 'testTable']))
            ->willReturnOnConsecutiveCalls(
                ResultMockFactory::create(DescribeTableOutput::class, [
                    'Table' => new TableDescription([
                        'TableStatus' => 'CREATING',
                    ]),
                ]),
                ResultMockFactory::create(DescribeTableOutput::class, [
                    'Table' => new TableDescription([
                        'TableStatus' => 'ACTIVE',
                    ]),
                ])
            );

        $this->client
            ->expects(self::once())
            ->method('updateTimeToLive')
            ->with(self::equalTo([
                'TableName' => 'testTable',
                'TimeToLiveSpecification' => [
                    'Enabled' => true,
                    'AttributeName' => 'expires',
                ],
            ]));

        $this->handler->setUp(0);
    }
}
