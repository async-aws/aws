<?php

namespace AsyncAws\Kinesis\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Kinesis\Enum\ShardIteratorType;

/**
 * Represents the input for `GetShardIterator`.
 */
final class GetShardIteratorInput extends Input
{
    /**
     * The name of the Amazon Kinesis data stream.
     *
     * @var string|null
     */
    private $streamName;

    /**
     * The shard ID of the Kinesis Data Streams shard to get the iterator for.
     *
     * @required
     *
     * @var string|null
     */
    private $shardId;

    /**
     * Determines how the shard iterator is used to start reading data records from the shard.
     *
     * The following are the valid Amazon Kinesis shard iterator types:
     *
     * - AT_SEQUENCE_NUMBER - Start reading from the position denoted by a specific sequence number, provided in the value
     *   `StartingSequenceNumber`.
     * - AFTER_SEQUENCE_NUMBER - Start reading right after the position denoted by a specific sequence number, provided in
     *   the value `StartingSequenceNumber`.
     * - AT_TIMESTAMP - Start reading from the position denoted by a specific time stamp, provided in the value `Timestamp`.
     * - TRIM_HORIZON - Start reading at the last untrimmed record in the shard in the system, which is the oldest data
     *   record in the shard.
     * - LATEST - Start reading just after the most recent record in the shard, so that you always read the most recent data
     *   in the shard.
     *
     * @required
     *
     * @var ShardIteratorType::*|null
     */
    private $shardIteratorType;

    /**
     * The sequence number of the data record in the shard from which to start reading. Used with shard iterator type
     * AT_SEQUENCE_NUMBER and AFTER_SEQUENCE_NUMBER.
     *
     * @var string|null
     */
    private $startingSequenceNumber;

    /**
     * The time stamp of the data record from which to start reading. Used with shard iterator type AT_TIMESTAMP. A time
     * stamp is the Unix epoch date with precision in milliseconds. For example, `2016-04-04T19:58:46.480-00:00` or
     * `1459799926.480`. If a record with this exact time stamp does not exist, the iterator returned is for the next
     * (later) record. If the time stamp is older than the current trim horizon, the iterator returned is for the oldest
     * untrimmed data record (TRIM_HORIZON).
     *
     * @var \DateTimeImmutable|null
     */
    private $timestamp;

    /**
     * The ARN of the stream.
     *
     * @var string|null
     */
    private $streamArn;

    /**
     * @param array{
     *   StreamName?: string,
     *   ShardId?: string,
     *   ShardIteratorType?: ShardIteratorType::*,
     *   StartingSequenceNumber?: string,
     *   Timestamp?: \DateTimeImmutable|string,
     *   StreamARN?: string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->streamName = $input['StreamName'] ?? null;
        $this->shardId = $input['ShardId'] ?? null;
        $this->shardIteratorType = $input['ShardIteratorType'] ?? null;
        $this->startingSequenceNumber = $input['StartingSequenceNumber'] ?? null;
        $this->timestamp = !isset($input['Timestamp']) ? null : ($input['Timestamp'] instanceof \DateTimeImmutable ? $input['Timestamp'] : new \DateTimeImmutable($input['Timestamp']));
        $this->streamArn = $input['StreamARN'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getShardId(): ?string
    {
        return $this->shardId;
    }

    /**
     * @return ShardIteratorType::*|null
     */
    public function getShardIteratorType(): ?string
    {
        return $this->shardIteratorType;
    }

    public function getStartingSequenceNumber(): ?string
    {
        return $this->startingSequenceNumber;
    }

    public function getStreamArn(): ?string
    {
        return $this->streamArn;
    }

    public function getStreamName(): ?string
    {
        return $this->streamName;
    }

    public function getTimestamp(): ?\DateTimeImmutable
    {
        return $this->timestamp;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'Kinesis_20131202.GetShardIterator',
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setShardId(?string $value): self
    {
        $this->shardId = $value;

        return $this;
    }

    /**
     * @param ShardIteratorType::*|null $value
     */
    public function setShardIteratorType(?string $value): self
    {
        $this->shardIteratorType = $value;

        return $this;
    }

    public function setStartingSequenceNumber(?string $value): self
    {
        $this->startingSequenceNumber = $value;

        return $this;
    }

    public function setStreamArn(?string $value): self
    {
        $this->streamArn = $value;

        return $this;
    }

    public function setStreamName(?string $value): self
    {
        $this->streamName = $value;

        return $this;
    }

    public function setTimestamp(?\DateTimeImmutable $value): self
    {
        $this->timestamp = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->streamName) {
            $payload['StreamName'] = $v;
        }
        if (null === $v = $this->shardId) {
            throw new InvalidArgument(sprintf('Missing parameter "ShardId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['ShardId'] = $v;
        if (null === $v = $this->shardIteratorType) {
            throw new InvalidArgument(sprintf('Missing parameter "ShardIteratorType" for "%s". The value cannot be null.', __CLASS__));
        }
        if (!ShardIteratorType::exists($v)) {
            throw new InvalidArgument(sprintf('Invalid parameter "ShardIteratorType" for "%s". The value "%s" is not a valid "ShardIteratorType".', __CLASS__, $v));
        }
        $payload['ShardIteratorType'] = $v;
        if (null !== $v = $this->startingSequenceNumber) {
            $payload['StartingSequenceNumber'] = $v;
        }
        if (null !== $v = $this->timestamp) {
            $payload['Timestamp'] = $v->getTimestamp();
        }
        if (null !== $v = $this->streamArn) {
            $payload['StreamARN'] = $v;
        }

        return $payload;
    }
}
