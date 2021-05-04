<?php

declare(strict_types=1);

namespace AsyncAws\Monolog\CloudWatch;

use AsyncAws\CloudWatchLogs\CloudWatchLogsClient;
use AsyncAws\CloudWatchLogs\ValueObject\LogStream;
use AsyncAws\Core\Exception\Http\ClientException;
use AsyncAws\Core\Exception\InvalidArgument;
use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

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
     * @var array
     */
    private $options;

    /**
     * @var bool
     */
    private $initialized = false;

    /**
     * @var string|null
     */
    private $sequenceToken;

    /**
     * @var array
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
     * @param int  $level
     * @param bool $bubble
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(
        CloudWatchLogsClient $client,
        $options,
        $level = Logger::DEBUG,
        $bubble = true
    ) {
        $options['batchSize'] = $options['batchSize'] ?? 10000;

        if ($options['batchSize'] > 10000) {
            throw new InvalidArgument('Batch size can not be greater than 10000');
        }

        $this->client = $client;
        $this->options = $options;

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
     * {@inheritdoc}
     */
    protected function write(array $record): void
    {
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

        if (!$this->initialized) {
            $this->initialize();
        }

        // send items, retry once with a fresh sequence token
        try {
            $this->send($this->buffer);
        } catch (ClientException $e) {
            $this->initialize();
            $this->send($this->buffer);
        }

        // clear buffer
        $this->buffer = [];

        // clear data amount
        $this->currentDataAmount = 0;
    }

    /**
     * Event size in the batch can not be bigger than 256 KB
     * https://docs.aws.amazon.com/AmazonCloudWatch/latest/logs/cloudwatch_limits_cwl.html.
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
     * @param array $record
     */
    private function getMessageSize($record): int
    {
        return \strlen($record['message']) + 26;
    }

    private function initialize(): void
    {
        $existingStreams = $this->client
            ->describeLogStreams(
                [
                    'logGroupName' => $this->options['group'],
                    'logStreamNamePrefix' => $this->options['stream'],
                ]
            )
            ->getLogStreams(true);

        /** @var LogStream $stream */
        foreach ($existingStreams as $stream) {
            if ($stream->getLogStreamName() === $this->options['stream'] && $stream->getUploadSequenceToken()) {
                $this->sequenceToken = $stream->getUploadSequenceToken();
            }
        }

        $this->initialized = true;
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
            'sequenceToken' => $this->sequenceToken,
        ];

        $this->checkThrottle();

        $response = $this->client->putLogEvents($data);

        $this->sequenceToken = $response->getNextSequenceToken();
    }
}
