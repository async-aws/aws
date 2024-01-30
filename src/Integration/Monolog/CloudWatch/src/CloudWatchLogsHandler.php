<?php

declare(strict_types=1);

namespace AsyncAws\Monolog\CloudWatch;

use AsyncAws\CloudWatchLogs\CloudWatchLogsClient;
use AsyncAws\Core\Exception\InvalidArgument;
use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Monolog\LogRecord;
use Monolog\Level;
use AsyncAws\Core\Exception\Exception;

/**
 * @phpstan-import-type FormattedRecord from AbstractProcessingHandler
 */
class CloudWatchLogsHandler extends AbstractProcessingHandler
{
    /**
     * Requests per second limit (https://docs.aws.amazon.com/AmazonCloudWatch/latest/logs/cloudwatch_limits_cwl.html).
     */
    private const RPS_LIMIT = 5;

    /**
     * Event size limit (https://docs.aws.amazon.com/AmazonCloudWatch/latest/logs/cloudwatch_limits_cwl.html).
     *
     * @var int
     */
    private const EVENT_SIZE_LIMIT = 262118; // 262144 - reserved 26

    /**
     * Data amount limit (http://docs.aws.amazon.com/AmazonCloudWatchLogs/latest/APIReference/API_PutLogEvents.html).
     *
     * @var int
     */
    private const DATA_AMOUNT_LIMIT = 1048576;

    /**
     * @var CloudWatchLogsClient
     */
    private $client;

    /**
     * @var array{
     *   batchSize: int,
     *   group: string,
     *   stream: string,
     * }
     */
    private $options;

    /**
     * @var array<array{message: string, timestamp: int|float}>
     */
    private $buffer = [];

    /**
     * @var int
     */
    private $currentDataAmount = 0;

    /**
     * @var int
     */
    private $remainingRequests = self::RPS_LIMIT;

    /**
     * @var int|null
     */
    private $lastRequestTimestamp;

    /**
     * @param CloudWatchLogsClient $client
     *
     *  Log group names must be unique within a region for an AWS account.
     *  Log group names can be between 1 and 512 characters long.
     *  Log group names consist of the following characters: a-z, A-Z, 0-9, '_' (underscore), '-' (hyphen),
     * '/' (forward slash), and '.' (period).
     *  Log stream names must be unique within the log group.
     *  Log stream names can be between 1 and 512 characters long.
     *  The ':' (colon) and '*' (asterisk) characters are not allowed.
     * @param array{
     *   batchSize?: int,
     *   group: string,
     *   stream: string,
     * } $options
     * @param int|string $level
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(
        CloudWatchLogsClient $client,
        array $options,
        $level = Logger::DEBUG,
        bool $bubble = true
    ) {
        $options['batchSize'] = $options['batchSize'] ?? 10000;

        if ($options['batchSize'] > 10000) {
            throw new InvalidArgument('Batch size can not be greater than 10000');
        }

        $this->client = $client;
        $this->options = $options;

        /**
         * @psalm-suppress ArgumentTypeCoercion
         */
        parent::__construct($level, $bubble);
    }

    /**
     * {@inheritdoc}
     */
    public function close(): void
    {
        $this->flushBuffer();
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultFormatter(): FormatterInterface
    {
        return new LineFormatter('%channel%: %level_name%: %message% %context% %extra%', null, false, true);
    }

    /**
     * @param LogRecord|array $record
     */
    protected function write($record): void
    {
            if($record instanceof LogRecord){
                $formatted = $record->formatted;
                $record = array_merge($record->toArray(), ['formatted'=>$formatted]);
            }
            if (!is_array($record)) {
                throw new InvalidArgument(sprintf('Argument 1 passed to %s must be of the type array or LogRecord, %s given', __METHOD__, gettype($record)));
            }

        $records = $this->formatRecords($record);

        foreach ($records as $record) {
            if ($this->currentDataAmount + $this->getMessageSize($record) >= self::DATA_AMOUNT_LIMIT) {
                $this->flushBuffer();
            }

            $this->currentDataAmount += $this->getMessageSize($record);

            $this->buffer[] = $record;

            if (\count($this->buffer) >= $this->options['batchSize']) {
                $this->flushBuffer();
            }
        }
    }

    /**
     * There is a quota of 5 requests per second per log stream.
     * Additional requests are throttled. This quota can't be changed.
     */
    private function checkThrottle(): void
    {
        $sameSecond = $this->lastRequestTimestamp === time();

        if ($sameSecond && $this->remainingRequests > 0) {
            --$this->remainingRequests;
        } elseif ($sameSecond && 0 === $this->remainingRequests) {
            sleep(1);
            $this->remainingRequests = self::RPS_LIMIT;
        } elseif (!$sameSecond) {
            $this->remainingRequests = self::RPS_LIMIT;
        }

        $this->lastRequestTimestamp = time();
    }

    private function flushBuffer(): void
    {
        if (empty($this->buffer)) {
            return;
        }

        $this->send($this->buffer);

        // clear buffer
        $this->buffer = [];

        // clear data amount
        $this->currentDataAmount = 0;
    }

    /**
     * Event size in the batch can not be bigger than 256 KB
     * https://docs.aws.amazon.com/AmazonCloudWatch/latest/logs/cloudwatch_limits_cwl.html.
     *
     * @phpstan-param FormattedRecord $entry
     *
     * @return list<array{message: string, timestamp: int|float}>
     */
    private function formatRecords(array $entry): array
    {
        $entries = str_split($entry['formatted'], self::EVENT_SIZE_LIMIT);
        $timestamp = $entry['datetime']->format('U.u') * 1000;
        $records = [];

        foreach ($entries as $entry) {
            $records[] = [
                'message' => $entry,
                'timestamp' => $timestamp,
            ];
        }

        return $records;
    }

    /**
     * http://docs.aws.amazon.com/AmazonCloudWatchLogs/latest/APIReference/API_PutLogEvents.html.
     *
     * @param \Monolog\LogRecord|array{message: string, timestamp: int|float} $record
     */
    private function getMessageSize($record): int
    {
            if($record instanceof LogRecord){
                $formatted = $record->formatted;
                $record = array_merge($record->toArray(), ['formatted'=>$formatted]);
            }

            if (!is_array($record)) {
                throw new InvalidArgument(sprintf('Argument 1 passed to %s must be of the type array or LogRecord, %s given', __METHOD__, gettype($record)));
            }

        return \strlen($record['message']) + 26;
    }

    /**
     * The batch of events must satisfy the following constraints:
     *  - The maximum batch size is 1,048,576 bytes, and this size is calculated as the sum of all event messages in
     * UTF-8, plus 26 bytes for each log event.
     *  - None of the log events in the batch can be more than 2 hours in the future.
     *  - None of the log events in the batch can be older than 14 days or the retention period of the log group.
     *  - The log events in the batch must be in chronological ordered by their timestamp (the time the event occurred,
     * expressed as the number of milliseconds since Jan 1, 1970 00:00:00 UTC).
     *  - The maximum number of log events in a batch is 10,000.
     *  - A batch of log events in a single request cannot span more than 24 hours. Otherwise, the operation fails.
     *
     * @param array<array{message: string, timestamp: int|float}> $entries
     */
    private function send(array $entries): void
    {
        // AWS expects to receive entries in chronological order...
        usort($entries, static function (array $a, array $b) {
            if ($a['timestamp'] < $b['timestamp']) {
                return -1;
            } elseif ($a['timestamp'] > $b['timestamp']) {
                return 1;
            }

            return 0;
        });

        $data = [
            'logGroupName' => $this->options['group'],
            'logStreamName' => $this->options['stream'],
            'logEvents' => $entries,
        ];

        $this->checkThrottle();

        $this->client->putLogEvents($data);
    }

    /**
     * @param LogRecord|array $record
     */
    public function handle($record): bool
    {
            if (is_array($record) && class_exists(LogRecord::class)) {
                $message = $record['message'] ?? null;
                $channel = $record['channel'] ?? null;;
                $level = $record['level'] ? Level::fromValue($record['level']) : null;;
                $context = $record['context'] ?? null;;
                $extra = $record['extra'] ?? null;;
                $datetime = $record['datetime'] ? \DateTimeImmutable::createFromMutable($record['datetime']) : null;;
                $formatted = $record['formatted'] ?? null;;
                $record = new LogRecord($datetime, $channel, $level, $message, $context, $extra, $formatted);
            }

        if (!$this->isHandling($record)) {
            return false;
        }

        if (\count($this->processors) > 0) {
            $record = $this->processRecord($record);
        }

        if(is_array($record))
        {
            $record['formatted'] = $this->getFormatter()->format($record);
        }else
        {
            $record->formatted = $this->getFormatter()->format($record);
        }

        $this->write($record);

        return false === $this->bubble;
    }
}
