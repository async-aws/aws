<?php

namespace AsyncAws\TimestreamQuery\ValueObject;

/**
 * Provides insights into the table with the most sub-optimal spatial range scanned by your query.
 */
final class QuerySpatialCoverageMax
{
    /**
     * The maximum ratio of spatial coverage.
     *
     * @var float|null
     */
    private $value;

    /**
     * The Amazon Resource Name (ARN) of the table with the most sub-optimal spatial pruning.
     *
     * @var string|null
     */
    private $tableArn;

    /**
     * The partition key used for partitioning, which can be a default `measure_name` or a customer defined partition key
     * [^1].
     *
     * [^1]: https://docs.aws.amazon.com/timestream/latest/developerguide/customer-defined-partition-keys.html
     *
     * @var string[]|null
     */
    private $partitionKey;

    /**
     * @param array{
     *   Value?: float|null,
     *   TableArn?: string|null,
     *   PartitionKey?: string[]|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->value = $input['Value'] ?? null;
        $this->tableArn = $input['TableArn'] ?? null;
        $this->partitionKey = $input['PartitionKey'] ?? null;
    }

    /**
     * @param array{
     *   Value?: float|null,
     *   TableArn?: string|null,
     *   PartitionKey?: string[]|null,
     * }|QuerySpatialCoverageMax $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return string[]
     */
    public function getPartitionKey(): array
    {
        return $this->partitionKey ?? [];
    }

    public function getTableArn(): ?string
    {
        return $this->tableArn;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }
}
