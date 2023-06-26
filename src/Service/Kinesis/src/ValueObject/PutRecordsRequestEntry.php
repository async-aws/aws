<?php

namespace AsyncAws\Kinesis\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Represents the output for `PutRecords`.
 */
final class PutRecordsRequestEntry
{
    /**
     * The data blob to put into the record, which is base64-encoded when the blob is serialized. When the data blob (the
     * payload before base64-encoding) is added to the partition key size, the total size must not exceed the maximum record
     * size (1 MiB).
     *
     * @var string
     */
    private $data;

    /**
     * The hash value used to determine explicitly the shard that the data record is assigned to by overriding the partition
     * key hash.
     *
     * @var string|null
     */
    private $explicitHashKey;

    /**
     * Determines which shard in the stream the data record is assigned to. Partition keys are Unicode strings with a
     * maximum length limit of 256 characters for each key. Amazon Kinesis Data Streams uses the partition key as input to a
     * hash function that maps the partition key and associated data to a specific shard. Specifically, an MD5 hash function
     * is used to map partition keys to 128-bit integer values and to map associated data records to shards. As a result of
     * this hashing mechanism, all data records with the same partition key map to the same shard within the stream.
     *
     * @var string
     */
    private $partitionKey;

    /**
     * @param array{
     *   Data: string,
     *   ExplicitHashKey?: null|string,
     *   PartitionKey: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->data = $input['Data'] ?? $this->throwException(new InvalidArgument('Missing required field "Data".'));
        $this->explicitHashKey = $input['ExplicitHashKey'] ?? null;
        $this->partitionKey = $input['PartitionKey'] ?? $this->throwException(new InvalidArgument('Missing required field "PartitionKey".'));
    }

    /**
     * @param array{
     *   Data: string,
     *   ExplicitHashKey?: null|string,
     *   PartitionKey: string,
     * }|PutRecordsRequestEntry $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getData(): string
    {
        return $this->data;
    }

    public function getExplicitHashKey(): ?string
    {
        return $this->explicitHashKey;
    }

    public function getPartitionKey(): string
    {
        return $this->partitionKey;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->data;
        $payload['Data'] = base64_encode($v);
        if (null !== $v = $this->explicitHashKey) {
            $payload['ExplicitHashKey'] = $v;
        }
        $v = $this->partitionKey;
        $payload['PartitionKey'] = $v;

        return $payload;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
