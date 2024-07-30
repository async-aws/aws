<?php

namespace AsyncAws\Monolog\CloudWatch\Tests;

use AsyncAws\CloudWatchLogs\CloudWatchLogsClient;
use AsyncAws\CloudWatchLogs\Result\PutLogEventsResponse;
use AsyncAws\Core\Test\ResultMockFactory;
use AsyncAws\Monolog\CloudWatch\CloudWatchLogsHandler;
use Monolog\Formatter\LineFormatter;
use Monolog\Level;
use Monolog\Logger;
use Monolog\LogRecord;
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
        $level = !\is_int($handler->getLevel()) ? $handler->getLevel()->value : $handler->getLevel();
        self::assertEquals(Logger::CRITICAL, $level);
        self::assertFalse($handler->getBubble());
    }

    public function testLimitExceeded()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->getHandler(10001);
    }

    public function testSendsOnClose()
    {
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
            $record = $this->getRecord(Logger::INFO, 'record' . $i, [], \DateTimeImmutable::createFromFormat('U', time() + $i));
            $records[] = $record;
        }

        // but submitted in a different order:
        $handler->handle($records[2]);
        $handler->handle($records[0]);
        $handler->handle($records[3]);
        $handler->handle($records[1]);

        $handler->close();
    }

    private function getHandler($batchSize = 1000): CloudWatchLogsHandler
    {
        return new CloudWatchLogsHandler($this->clientMock, [
            'batchSize' => $batchSize,
            'group' => $this->groupName,
            'stream' => $this->streamName,
        ]);
    }

    private function getRecord(int $level = Logger::WARNING, string $message = 'test', array $context = [], ?\DateTimeImmutable $datetime = null)
    {
        $data = [
            'message' => $message,
            'context' => $context,
            'level' => $level,
            'channel' => 'test',
            'datetime' => $datetime ?? \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', microtime(true))),
            'extra' => [],
        ];

        if (!class_exists(LogRecord::class)) {
            $data['level_name'] = Logger::getLevelName($data['level']);

            return $data;
        }

        return new LogRecord(
            $data['datetime'],
            $data['channel'],
            Level::fromValue($data['level']),
            $data['message'],
            $data['context'],
            $data['extra']
        );
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
