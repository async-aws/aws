<?php

namespace AsyncAws\DynamoDbSession\Tests;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\ResultMockFactory;
use AsyncAws\DynamoDb\DynamoDbClient;
use AsyncAws\DynamoDb\Result\GetItemOutput;
use AsyncAws\DynamoDb\Result\TableExistsWaiter;
use AsyncAws\DynamoDb\ValueObject\AttributeValue;
use AsyncAws\DynamoDbSession\SessionHandler;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

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

        self::assertEquals('test data', $this->handler->read('123456789'));
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
                    'expires' => new AttributeValue(['N' => (string) (time() + 86400)]),
                ],
            ]));

        $expectedFirstData = [
            'TableName' => 'testTable',
            'Key' => [
                'id' => ['S' => 'PHPSESSID_123456789'],
            ],
            'AttributeUpdates' => [
                'expires' => ['Value' => ['N' => (string) (time() + 86400)]],
                'data' => ['Value' => ['S' => 'new data']],
            ],
        ];
        $expectedSecondData = [
            'TableName' => 'testTable',
            'Key' => [
                'id' => ['S' => 'PHPSESSID_123456789'],
            ],
            'AttributeUpdates' => [
                'expires' => ['Value' => ['N' => (string) (time() + 86400)]],
                'data' => ['Value' => ['S' => 'previous data']],
            ],
        ];

        $this->client
            ->expects(self::exactly(2))
            ->method('updateItem')
            ->with(self::callback(function (array $data) use ($expectedFirstData, $expectedSecondData): bool {
                static $i = 0;

                return match (++$i) {
                    1 => $data === $expectedFirstData,
                    2 => $data === $expectedSecondData,
                };
            }));

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

        $client = new MockHttpClient(new SimpleMockedResponse('{
            "Table": {
                "TableStatus": "ACTIVE"
            }
        }'));

        $this->client
            ->expects(self::once())
            ->method('tableExists')
            ->with(self::equalTo(['TableName' => 'testTable']))
            ->willReturn(new TableExistsWaiter(
                new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()),
                $this->client,
                null
            ));

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

        $this->handler->setUp();
    }

    public function testCustomKeySeparator()
    {
        $handler = new SessionHandler($this->client, ['table_name' => 'testTable', 'session_lifetime' => 86400, 'id_separator' => '#']);
        $handler->open(null, 'PHPSESSID');

        $this->client
            ->expects(self::once())
            ->method('updateItem')
            ->with(self::equalTo([
                'TableName' => 'testTable',
                'Key' => [
                    'id' => ['S' => 'PHPSESSID#123456789'],
                ],
                'AttributeUpdates' => [
                    'expires' => ['Value' => ['N' => time() + 86400]],
                    'data' => ['Value' => ['S' => 'test data']],
                ],
            ], 10));

        self::assertTrue($handler->write('123456789', 'test data'));
    }
}
