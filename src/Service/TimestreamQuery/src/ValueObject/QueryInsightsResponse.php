<?php

namespace AsyncAws\TimestreamQuery\ValueObject;

/**
 * Provides various insights and metrics related to the query that you executed.
 */
final class QueryInsightsResponse
{
    /**
     * Provides insights into the spatial coverage of the query, including the table with sub-optimal (max) spatial pruning.
     * This information can help you identify areas for improvement in your partitioning strategy to enhance spatial
     * pruning.
     *
     * @var QuerySpatialCoverage|null
     */
    private $querySpatialCoverage;

    /**
     * Provides insights into the temporal range of the query, including the table with the largest (max) time range.
     * Following are some of the potential options for optimizing time-based pruning:
     *
     * - Add missing time-predicates.
     * - Remove functions around the time predicates.
     * - Add time predicates to all the sub-queries.
     *
     * @var QueryTemporalRange|null
     */
    private $queryTemporalRange;

    /**
     * Indicates the number of tables in the query.
     *
     * @var int|null
     */
    private $queryTableCount;

    /**
     * Indicates the total number of rows returned as part of the query result set. You can use this data to validate if the
     * number of rows in the result set have changed as part of the query tuning exercise.
     *
     * @var int|null
     */
    private $outputRows;

    /**
     * Indicates the size of query result set in bytes. You can use this data to validate if the result set has changed as
     * part of the query tuning exercise.
     *
     * @var int|null
     */
    private $outputBytes;

    /**
     * Indicates the partitions created by the `Unload` operation.
     *
     * @var int|null
     */
    private $unloadPartitionCount;

    /**
     * Indicates the rows written by the `Unload` query.
     *
     * @var int|null
     */
    private $unloadWrittenRows;

    /**
     * Indicates the size, in bytes, written by the `Unload` operation.
     *
     * @var int|null
     */
    private $unloadWrittenBytes;

    /**
     * @param array{
     *   QuerySpatialCoverage?: QuerySpatialCoverage|array|null,
     *   QueryTemporalRange?: QueryTemporalRange|array|null,
     *   QueryTableCount?: int|null,
     *   OutputRows?: int|null,
     *   OutputBytes?: int|null,
     *   UnloadPartitionCount?: int|null,
     *   UnloadWrittenRows?: int|null,
     *   UnloadWrittenBytes?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->querySpatialCoverage = isset($input['QuerySpatialCoverage']) ? QuerySpatialCoverage::create($input['QuerySpatialCoverage']) : null;
        $this->queryTemporalRange = isset($input['QueryTemporalRange']) ? QueryTemporalRange::create($input['QueryTemporalRange']) : null;
        $this->queryTableCount = $input['QueryTableCount'] ?? null;
        $this->outputRows = $input['OutputRows'] ?? null;
        $this->outputBytes = $input['OutputBytes'] ?? null;
        $this->unloadPartitionCount = $input['UnloadPartitionCount'] ?? null;
        $this->unloadWrittenRows = $input['UnloadWrittenRows'] ?? null;
        $this->unloadWrittenBytes = $input['UnloadWrittenBytes'] ?? null;
    }

    /**
     * @param array{
     *   QuerySpatialCoverage?: QuerySpatialCoverage|array|null,
     *   QueryTemporalRange?: QueryTemporalRange|array|null,
     *   QueryTableCount?: int|null,
     *   OutputRows?: int|null,
     *   OutputBytes?: int|null,
     *   UnloadPartitionCount?: int|null,
     *   UnloadWrittenRows?: int|null,
     *   UnloadWrittenBytes?: int|null,
     * }|QueryInsightsResponse $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getOutputBytes(): ?int
    {
        return $this->outputBytes;
    }

    public function getOutputRows(): ?int
    {
        return $this->outputRows;
    }

    public function getQuerySpatialCoverage(): ?QuerySpatialCoverage
    {
        return $this->querySpatialCoverage;
    }

    public function getQueryTableCount(): ?int
    {
        return $this->queryTableCount;
    }

    public function getQueryTemporalRange(): ?QueryTemporalRange
    {
        return $this->queryTemporalRange;
    }

    public function getUnloadPartitionCount(): ?int
    {
        return $this->unloadPartitionCount;
    }

    public function getUnloadWrittenBytes(): ?int
    {
        return $this->unloadWrittenBytes;
    }

    public function getUnloadWrittenRows(): ?int
    {
        return $this->unloadWrittenRows;
    }
}
