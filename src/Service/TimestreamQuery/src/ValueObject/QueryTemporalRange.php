<?php

namespace AsyncAws\TimestreamQuery\ValueObject;

/**
 * Provides insights into the temporal range of the query, including the table with the largest (max) time range.
 */
final class QueryTemporalRange
{
    /**
     * Encapsulates the following properties that provide insights into the most sub-optimal performing table on the
     * temporal axis:
     *
     * - `Value` – The maximum duration in nanoseconds between the start and end of the query.
     * - `TableArn` – The Amazon Resource Name (ARN) of the table which is queried with the largest time range.
     *
     * @var QueryTemporalRangeMax|null
     */
    private $max;

    /**
     * @param array{
     *   Max?: QueryTemporalRangeMax|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->max = isset($input['Max']) ? QueryTemporalRangeMax::create($input['Max']) : null;
    }

    /**
     * @param array{
     *   Max?: QueryTemporalRangeMax|array|null,
     * }|QueryTemporalRange $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getMax(): ?QueryTemporalRangeMax
    {
        return $this->max;
    }
}
