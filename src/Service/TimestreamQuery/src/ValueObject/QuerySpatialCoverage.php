<?php

namespace AsyncAws\TimestreamQuery\ValueObject;

/**
 * Provides insights into the spatial coverage of the query, including the table with sub-optimal (max) spatial pruning.
 * This information can help you identify areas for improvement in your partitioning strategy to enhance spatial pruning.
 *
 * For example, you can do the following with the `QuerySpatialCoverage` information:
 *
 * - Add measure_name or use customer-defined partition key [^1] (CDPK) predicates.
 * - If you've already done the preceding action, remove functions around them or clauses, such as `LIKE`.
 *
 * [^1]: https://docs.aws.amazon.com/timestream/latest/developerguide/customer-defined-partition-keys.html
 */
final class QuerySpatialCoverage
{
    /**
     * Provides insights into the spatial coverage of the executed query and the table with the most inefficient spatial
     * pruning.
     *
     * - `Value` – The maximum ratio of spatial coverage.
     * - `TableArn` – The Amazon Resource Name (ARN) of the table with sub-optimal spatial pruning.
     * - `PartitionKey` – The partition key used for partitioning, which can be a default `measure_name` or a CDPK.
     *
     * @var QuerySpatialCoverageMax|null
     */
    private $max;

    /**
     * @param array{
     *   Max?: QuerySpatialCoverageMax|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->max = isset($input['Max']) ? QuerySpatialCoverageMax::create($input['Max']) : null;
    }

    /**
     * @param array{
     *   Max?: QuerySpatialCoverageMax|array|null,
     * }|QuerySpatialCoverage $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getMax(): ?QuerySpatialCoverageMax
    {
        return $this->max;
    }
}
