<?php

namespace AsyncAws\DynamoDb\ValueObject;

/**
 * Provides visibility into the number of read and write operations your table or secondary index can instantaneously
 * support. The settings can be modified using the `UpdateTable` operation to meet the throughput requirements of an
 * upcoming peak event.
 */
final class WarmThroughput
{
    /**
     * Represents the number of read operations your base table can instantaneously support.
     *
     * @var int|null
     */
    private $readUnitsPerSecond;

    /**
     * Represents the number of write operations your base table can instantaneously support.
     *
     * @var int|null
     */
    private $writeUnitsPerSecond;

    /**
     * @param array{
     *   ReadUnitsPerSecond?: int|null,
     *   WriteUnitsPerSecond?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->readUnitsPerSecond = $input['ReadUnitsPerSecond'] ?? null;
        $this->writeUnitsPerSecond = $input['WriteUnitsPerSecond'] ?? null;
    }

    /**
     * @param array{
     *   ReadUnitsPerSecond?: int|null,
     *   WriteUnitsPerSecond?: int|null,
     * }|WarmThroughput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getReadUnitsPerSecond(): ?int
    {
        return $this->readUnitsPerSecond;
    }

    public function getWriteUnitsPerSecond(): ?int
    {
        return $this->writeUnitsPerSecond;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->readUnitsPerSecond) {
            $payload['ReadUnitsPerSecond'] = $v;
        }
        if (null !== $v = $this->writeUnitsPerSecond) {
            $payload['WriteUnitsPerSecond'] = $v;
        }

        return $payload;
    }
}
