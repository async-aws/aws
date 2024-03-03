<?php

namespace AsyncAws\DynamoDbSession\Tests;

use AsyncAws\Core\AwsError\AwsError;
use AsyncAws\Core\Test\ResultMockFactory;
use AsyncAws\DynamoDb\DynamoDbClient;
use AsyncAws\DynamoDb\Exception\ConditionalCheckFailedException;
use AsyncAws\DynamoDb\Result\UpdateItemOutput;
use AsyncAws\DynamoDb\ValueObject\AttributeValue;
use AsyncAws\DynamoDbSession\SessionHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\ResponseInterface;

class SessionHandlerLockingTest extends TestCase
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
        $this->client = $this->createMock(DynamoDbClient::class);

        $this->handler = new SessionHandler($this->client, [
            'table_name' => 'testTable',
            'session_lifetime' => 86400,
            'locking' => true,
            'max_lock_wait_time' => 1.0,
            'min_lock_retry_microtime' => 300000,
            'max_lock_retry_microtime' => 300000,
        ]);

        $this->handler->open(null, 'PHPSESSID');
    }

    public function testRead(): void
    {
        $this->client
            ->expects(self::once())
            ->method('updateItem')
            ->with(self::equalTo([
                'TableName' => 'testTable',
                'Key' => [
                    'id' => [
                        'S' => 'PHPSESSID_123456789',
                    ],
                ],
                'ConsistentRead' => true,
                'Expected' => ['lock' => ['Exists' => false]],
                'AttributeUpdates' => ['lock' => ['Value' => ['N' => '1']]],
                'ReturnValues' => 'ALL_NEW',
            ]))
            ->willReturn(ResultMockFactory::create(UpdateItemOutput::class, [
                'Attributes' => [
                    'data' => new AttributeValue(['S' => 'test data']),
                    'expires' => new AttributeValue(['N' => (string) (time() + 86400)]),
                ],
            ]));

        self::assertEquals('test data', $this->handler->read('123456789'));
    }

    public static function readLockProvider(): array
    {
        return [
            'success' => [3, 2, true],
            'timeout' => [4, 4, false],
        ];
    }

    /**
     * @dataProvider readLockProvider
     */
    public function testReadLock(int $attempts, int $failCount, bool $expectedSuccess): void
    {
        $this->client
            ->expects($matcher = self::exactly($attempts))
            ->method('updateItem')
            ->with(self::equalTo([
                'TableName' => 'testTable',
                'Key' => [
                    'id' => [
                        'S' => 'PHPSESSID_123456789',
                    ],
                ],
                'ConsistentRead' => true,
                'Expected' => ['lock' => ['Exists' => false]],
                'AttributeUpdates' => ['lock' => ['Value' => ['N' => '1']]],
                'ReturnValues' => 'ALL_NEW',
            ]))
            ->willReturnCallback(function () use ($matcher, $failCount) {
                if ($matcher->getInvocationCount() <= $failCount) {
                    $mockResponse = self::createMock(ResponseInterface::class);
                    $mockResponse->method('getInfo')->willReturnMap([['http_code', 400]]);

                    throw new ConditionalCheckFailedException($mockResponse, new AwsError('a', 'b', 'c', 'd'));
                }

                return ResultMockFactory::create(UpdateItemOutput::class, [
                    'Attributes' => [
                        'data' => new AttributeValue(['S' => 'test data']),
                        'expires' => new AttributeValue(['N' => (string) (time() + 86400)]),
                    ],
                ]);
            });

        if (!$expectedSuccess) {
            self::expectException(ConditionalCheckFailedException::class);
        }

        self::assertEquals('test data', $this->handler->read('123456789'));
    }

    public function testWriteWithUnchangedData(): void
    {
        $this->client
            ->method('updateItem')
            ->willReturnMap([
                [
                    [
                        'TableName' => 'testTable',
                        'Key' => ['id' => ['S' => 'PHPSESSID_123456789']],
                        'ConsistentRead' => true,
                        'Expected' => ['lock' => ['Exists' => false]],
                        'AttributeUpdates' => ['lock' => ['Value' => ['N' => '1']]],
                        'ReturnValues' => 'ALL_NEW',
                    ],
                    ResultMockFactory::create(UpdateItemOutput::class, [
                        'Attributes' => [
                            'data' => new AttributeValue(['S' => 'previous data']),
                            'expires' => new AttributeValue(['N' => (string) (time() + 86400)]),
                        ],
                    ]),
                ],
                [
                    [
                        'TableName' => 'testTable',
                        'Key' => [
                            'id' => ['S' => 'PHPSESSID_123456789'],
                        ],
                        'AttributeUpdates' => [
                            'expires' => ['Value' => ['N' => (string) (time() + 86400)]],
                            'lock' => ['Action' => 'DELETE'],
                        ],
                    ],
                    ResultMockFactory::create(UpdateItemOutput::class, [
                        'Attributes' => [
                            'data' => new AttributeValue(['S' => 'previous data']),
                            'expires' => new AttributeValue(['N' => (string) (time() + 86400)]),
                        ],
                    ]),
                ],
            ]);

        $this->handler->read('123456789');

        self::assertTrue($this->handler->write('123456789', 'previous data'));
    }

    public function testWriteWithChangedData(): void
    {
        $this->client
            ->method('updateItem')
            ->willReturnMap([
                [
                    [
                        'TableName' => 'testTable',
                        'Key' => ['id' => ['S' => 'PHPSESSID_123456789']],
                        'ConsistentRead' => true,
                        'Expected' => ['lock' => ['Exists' => false]],
                        'AttributeUpdates' => [
                            'lock' => ['Value' => ['N' => '1']],
                        ],
                        'ReturnValues' => 'ALL_NEW',
                    ],
                    ResultMockFactory::create(UpdateItemOutput::class, [
                        'Attributes' => [
                            'data' => new AttributeValue(['S' => 'previous data']),
                            'expires' => new AttributeValue(['N' => (string) (time() + 86400)]),
                        ],
                    ]),
                ],
                [
                    [
                        'TableName' => 'testTable',
                        'Key' => [
                            'id' => ['S' => 'PHPSESSID_123456789'],
                        ],
                        'AttributeUpdates' => [
                            'expires' => ['Value' => ['N' => (string) (time() + 86400)]],
                            'lock' => ['Action' => 'DELETE'],
                            'data' => ['Value' => ['S' => 'new data']],
                        ],
                    ],
                    ResultMockFactory::create(UpdateItemOutput::class, [
                        'Attributes' => [
                            'data' => new AttributeValue(['S' => 'previous data']),
                            'expires' => new AttributeValue(['N' => (string) (time() + 86400)]),
                        ],
                    ]),
                ],
            ]);

        $this->handler->read('123456789');

        self::assertTrue($this->handler->write('123456789', 'new data'));
    }
}
