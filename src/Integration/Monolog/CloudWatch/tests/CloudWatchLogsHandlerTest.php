<?php

namespace AsyncAws\Monolog\CloudWatch\Tests;

use AsyncAws\CloudWatchLogs\CloudWatchLogsClient;
use AsyncAws\CloudWatchLogs\Result\DescribeLogStreamsResponse;
use AsyncAws\CloudWatchLogs\Result\PutLogEventsResponse;
use AsyncAws\CloudWatchLogs\ValueObject\LogStream;
use AsyncAws\Core\Test\ResultMockFactory;
use AsyncAws\Monolog\CloudWatch\CloudWatchLogsHandler;
use Monolog\Formatter\LineFormatter;
use Monolog\Logger;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CloudWatchLogsHandlerTest extends TestCase
{
    /**
     * @var MockObject|CloudWatchLogsClient
     */
    private $clientMock;

    /**
     * @var string
     */
    private $groupName = 'group';

    /**
     * @var string
     */
    private $streamName = 'stream';

    protected function setUp(): void
    {
        $this->clientMock = $this->getMockBuilder(CloudWatchLogsClient::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['describeLogStreams', 'putLogEvents'])
            ->getMock();
    }

    public function testConfig()
    {
        $handler = new CloudWatchLogsHandler($this->clientMock, [
            'batchSize' => 1337,
            'group' => $this->groupName,
            'stream' => $this->streamName,
        ], Logger::CRITICAL, false);

        self::assertEquals(Logger::CRITICAL, $handler->getLevel());
        self::assertFalse($handler->getBubble());
    }

    public function testInitialize()
    {
        $this->mockDescribeLogStreams();

        $handler = new CloudWatchLogsHandler($this->clientMock, [
            'group' => $this->groupName,
            'stream' => $this->streamName,
        ]);

        $reflection = new \ReflectionClass($handler);
        $reflectionMethod = $reflection->getMethod('initialize');
        $reflectionMethod->setAccessible(true);
        $reflectionMethod->invoke($handler);
    }

    public function testLimitExceeded()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->getHandler(10001);
    }

    public function testSendsOnClose()
    {
        $this->mockDescribeLogStreams();

        $this->clientMock
            ->expects(self::once())
            ->method('putLogEvents')
            ->willReturn(ResultMockFactory::create(PutLogEventsResponse::class));

        $handler = $this->getHandler(1);

        $handler->handle($this->getRecord(Logger::DEBUG));

        $handler->close();
    }

    public function testSendsBatches()
    {
        $this->mockDescribeLogStreams();

        $this->clientMock
            ->expects(self::exactly(2))
            ->method('putLogEvents')
            ->willReturn(ResultMockFactory::create(PutLogEventsResponse::class));

        $handler = $this->getHandler(3);

        foreach ($this->getMultipleRecords() as $record) {
            $handler->handle($record);
        }

        $handler->close();
    }

    public function testFormatter()
    {
        $handler = $this->getHandler();

        $formatter = $handler->getFormatter();

        $expected = new LineFormatter('%channel%: %level_name%: %message% %context% %extra%', null, false, true);

        self::assertEquals($expected, $formatter);
    }

    public function testSortsEntriesChronologically()
    {
        $this->mockDescribeLogStreams();

        $this->clientMock
            ->expects(self::once())
            ->method('putLogEvents')
            ->willReturnCallback(function (array $data) {
                self::assertStringContainsString('record1', $data['logEvents'][0]['message']);
                self::assertStringContainsString('record2', $data['logEvents'][1]['message']);
                self::assertStringContainsString('record3', $data['logEvents'][2]['message']);
                self::assertStringContainsString('record4', $data['logEvents'][3]['message']);

                return ResultMockFactory::create(PutLogEventsResponse::class);
            });

        $handler = $this->getHandler(4);

        // created with chronological timestamps:
        $records = [];

        for ($i = 1; $i <= 4; ++$i) {
            $record = $this->getRecord(Logger::INFO, 'record' . $i);
            $record['datetime'] = \DateTime::createFromFormat('U', time() + $i);
            $records[] = $record;
        }

        // but submitted in a different order:
        $handler->handle($records[2]);
        $handler->handle($records[0]);
        $handler->handle($records[3]);
        $handler->handle($records[1]);

        $handler->close();
    }

    private function mockDescribeLogStreams(): void
    {
        $this->clientMock
            ->expects(self::once())
            ->method('describeLogStreams')
            ->with([
                'logGroupName' => $this->groupName,
                'logStreamNamePrefix' => $this->streamName,
            ])
            ->willReturn(ResultMockFactory::create(DescribeLogStreamsResponse::class, [
                'logStreams' => [
                    new LogStream([
                        'logStreamName' => $this->streamName,
                        'uploadSequenceToken' => '49559307804604887372466686181995921714853186581450198322',
                    ]),
                ],
            ]));
    }

    private function getHandler($batchSize = 1000): CloudWatchLogsHandler
    {
        return new CloudWatchLogsHandler($this->clientMock, [
            'batchSize' => $batchSize,
            'group' => $this->groupName,
            'stream' => $this->streamName,
        ]);
    }

    private function getRecord(int $level = Logger::WARNING, string $message = 'test', array $context = []): array
    {
        return [
            'message' => $message,
            'context' => $context,
            'level' => $level,
            'level_name' => Logger::getLevelName($level),
            'channel' => 'test',
            'datetime' => \DateTime::createFromFormat('U.u', sprintf('%.6F', microtime(true))),
            'extra' => [],
        ];
    }

    private function getMultipleRecords(): array
    {
        return [
            $this->getRecord(Logger::DEBUG, 'debug message 1'),
            $this->getRecord(Logger::DEBUG, 'debug message 2'),
            $this->getRecord(Logger::INFO, 'information'),
            $this->getRecord(Logger::WARNING, 'warning'),
            $this->getRecord(Logger::ERROR, 'error'),
        ];
    }
}
