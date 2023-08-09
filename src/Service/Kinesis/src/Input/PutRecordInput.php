<?php

namespace AsyncAws\Kinesis\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * Represents the input for `PutRecord`.
 */
final class PutRecordInput extends Input
{
    /**
     * The name of the stream to put the data record into.
     *
     * @var string|null
     */
    private $streamName;

    /**
     * The data blob to put into the record, which is base64-encoded when the blob is serialized. When the data blob (the
     * payload before base64-encoding) is added to the partition key size, the total size must not exceed the maximum record
     * size (1 MiB).
     *
     * @required
     *
     * @var string|null
     */
    private $data;

    /**
     * Determines which shard in the stream the data record is assigned to. Partition keys are Unicode strings with a
     * maximum length limit of 256 characters for each key. Amazon Kinesis Data Streams uses the partition key as input to a
     * hash function that maps the partition key and associated data to a specific shard. Specifically, an MD5 hash function
     * is used to map partition keys to 128-bit integer values and to map associated data records to shards. As a result of
     * this hashing mechanism, all data records with the same partition key map to the same shard within the stream.
     *
     * @required
     *
     * @var string|null
     */
    private $partitionKey;

    /**
     * The hash value used to explicitly determine the shard the data record is assigned to by overriding the partition key
     * hash.
     *
     * @var string|null
     */
    private $explicitHashKey;

    /**
     * Guarantees strictly increasing sequence numbers, for puts from the same client and to the same partition key. Usage:
     * set the `SequenceNumberForOrdering` of record *n* to the sequence number of record *n-1* (as returned in the result
     * when putting record *n-1*). If this parameter is not set, records are coarsely ordered based on arrival time.
     *
     * @var string|null
     */
    private $sequenceNumberForOrdering;

    /**
     * The ARN of the stream.
     *
     * @var string|null
     */
    private $streamArn;

    /**
     * @param array{
     *   StreamName?: null|string,
     *   Data?: string,
     *   PartitionKey?: string,
     *   ExplicitHashKey?: null|string,
     *   SequenceNumberForOrdering?: null|string,
     *   StreamARN?: null|string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->streamName = $input['StreamName'] ?? null;
        $this->data = $input['Data'] ?? null;
        $this->partitionKey = $input['PartitionKey'] ?? null;
        $this->explicitHashKey = $input['ExplicitHashKey'] ?? null;
        $this->sequenceNumberForOrdering = $input['SequenceNumberForOrdering'] ?? null;
        $this->streamArn = $input['StreamARN'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   StreamName?: null|string,
     *   Data?: string,
     *   PartitionKey?: string,
     *   ExplicitHashKey?: null|string,
     *   SequenceNumberForOrdering?: null|string,
     *   StreamARN?: null|string,
     *   '@region'?: string|null,
     * }|PutRecordInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getData(): ?string
    {
        return $this->data;
    }

    public function getExplicitHashKey(): ?string
    {
        return $this->explicitHashKey;
    }

    public function getPartitionKey(): ?string
    {
        return $this->partitionKey;
    }

    public function getSequenceNumberForOrdering(): ?string
    {
        return $this->sequenceNumberForOrdering;
    }

    public function getStreamArn(): ?string
    {
        return $this->streamArn;
    }

    public function getStreamName(): ?string
    {
        return $this->streamName;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'Kinesis_20131202.PutRecord',
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

    public function setData(?string $value): self
    {
        $this->data = $value;

        return $this;
    }

    public function setExplicitHashKey(?string $value): self
    {
        $this->explicitHashKey = $value;

        return $this;
    }

    public function setPartitionKey(?string $value): self
    {
        $this->partitionKey = $value;

        return $this;
    }

    public function setSequenceNumberForOrdering(?string $value): self
    {
        $this->sequenceNumberForOrdering = $value;

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

    private function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->streamName) {
            $payload['StreamName'] = $v;
        }
        if (null === $v = $this->data) {
            throw new InvalidArgument(sprintf('Missing parameter "Data" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Data'] = base64_encode($v);
        if (null === $v = $this->partitionKey) {
            throw new InvalidArgument(sprintf('Missing parameter "PartitionKey" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['PartitionKey'] = $v;
        if (null !== $v = $this->explicitHashKey) {
            $payload['ExplicitHashKey'] = $v;
        }
        if (null !== $v = $this->sequenceNumberForOrdering) {
            $payload['SequenceNumberForOrdering'] = $v;
        }
        if (null !== $v = $this->streamArn) {
            $payload['StreamARN'] = $v;
        }

        return $payload;
    }
}
