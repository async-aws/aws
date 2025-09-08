<?php

namespace AsyncAws\TimestreamQuery\ValueObject;

/**
 * Provides insights into the table with the most sub-optimal temporal pruning scanned by your query.
 */
final class QueryTemporalRangeMax
{
    /**
     * The maximum duration in nanoseconds between the start and end of the query.
     *
     * @var int|null
     */
    private $value;

    /**
     * The Amazon Resource Name (ARN) of the table which is queried with the largest time range.
     *
     * @var string|null
     */
    private $tableArn;

    /**
     * @param array{
     *   Value?: int|null,
     *   TableArn?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->value = $input['Value'] ?? null;
        $this->tableArn = $input['TableArn'] ?? null;
    }

    /**
     * @param array{
     *   Value?: int|null,
     *   TableArn?: string|null,
     * }|QueryTemporalRangeMax $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getTableArn(): ?string
    {
        return $this->tableArn;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }
}
