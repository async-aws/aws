<?php

namespace AsyncAws\DynamoDb\ValueObject;

/**
 * The consumed capacity for vector index operations, including vector search request bytes and vector write request
 * bytes.
 */
final class VectorCapacity
{
    /**
     * The number of vector search request bytes consumed by a `SearchVectors` operation.
     *
     * @var float|null
     */
    private $vectorSearchRequestBytes;

    /**
     * The number of vector write request bytes consumed when writing to a vector index. Reported for write operations that
     * modify attributes indexed by a vector index.
     *
     * @var float|null
     */
    private $vectorWriteRequestBytes;

    /**
     * @param array{
     *   VectorSearchRequestBytes?: float|null,
     *   VectorWriteRequestBytes?: float|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->vectorSearchRequestBytes = $input['VectorSearchRequestBytes'] ?? null;
        $this->vectorWriteRequestBytes = $input['VectorWriteRequestBytes'] ?? null;
    }

    /**
     * @param array{
     *   VectorSearchRequestBytes?: float|null,
     *   VectorWriteRequestBytes?: float|null,
     * }|VectorCapacity $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getVectorSearchRequestBytes(): ?float
    {
        return $this->vectorSearchRequestBytes;
    }

    public function getVectorWriteRequestBytes(): ?float
    {
        return $this->vectorWriteRequestBytes;
    }
}
