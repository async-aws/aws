<?php

namespace AsyncAws\Athena\ValueObject;

/**
 * The amount of data scanned during the query execution and the amount of time that it took to execute, and the type of
 * statement that was run.
 */
final class QueryExecutionStatistics
{
    /**
     * The number of milliseconds that the query took to execute.
     */
    private $engineExecutionTimeInMillis;

    /**
     * The number of bytes in the data that was queried.
     */
    private $dataScannedInBytes;

    /**
     * The location and file name of a data manifest file. The manifest file is saved to the Athena query results location
     * in Amazon S3. The manifest file tracks files that the query wrote to Amazon S3. If the query fails, the manifest file
     * also tracks files that the query intended to write. The manifest is useful for identifying orphaned files resulting
     * from a failed query. For more information, see Working with Query Results, Output Files, and Query History [^1] in
     * the *Amazon Athena User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/athena/latest/ug/querying.html
     */
    private $dataManifestLocation;

    /**
     * The number of milliseconds that Athena took to run the query.
     */
    private $totalExecutionTimeInMillis;

    /**
     * The number of milliseconds that the query was in your query queue waiting for resources. Note that if transient
     * errors occur, Athena might automatically add the query back to the queue.
     */
    private $queryQueueTimeInMillis;

    /**
     * The number of milliseconds that Athena took to plan the query processing flow. This includes the time spent
     * retrieving table partitions from the data source. Note that because the query engine performs the query planning,
     * query planning time is a subset of engine processing time.
     */
    private $queryPlanningTimeInMillis;

    /**
     * The number of milliseconds that Athena took to finalize and publish the query results after the query engine finished
     * running the query.
     */
    private $serviceProcessingTimeInMillis;

    /**
     * Contains information about whether previous query results were reused for the query.
     */
    private $resultReuseInformation;

    /**
     * @param array{
     *   EngineExecutionTimeInMillis?: null|string,
     *   DataScannedInBytes?: null|string,
     *   DataManifestLocation?: null|string,
     *   TotalExecutionTimeInMillis?: null|string,
     *   QueryQueueTimeInMillis?: null|string,
     *   QueryPlanningTimeInMillis?: null|string,
     *   ServiceProcessingTimeInMillis?: null|string,
     *   ResultReuseInformation?: null|ResultReuseInformation|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->engineExecutionTimeInMillis = $input['EngineExecutionTimeInMillis'] ?? null;
        $this->dataScannedInBytes = $input['DataScannedInBytes'] ?? null;
        $this->dataManifestLocation = $input['DataManifestLocation'] ?? null;
        $this->totalExecutionTimeInMillis = $input['TotalExecutionTimeInMillis'] ?? null;
        $this->queryQueueTimeInMillis = $input['QueryQueueTimeInMillis'] ?? null;
        $this->queryPlanningTimeInMillis = $input['QueryPlanningTimeInMillis'] ?? null;
        $this->serviceProcessingTimeInMillis = $input['ServiceProcessingTimeInMillis'] ?? null;
        $this->resultReuseInformation = isset($input['ResultReuseInformation']) ? ResultReuseInformation::create($input['ResultReuseInformation']) : null;
    }

    /**
     * @param array{
     *   EngineExecutionTimeInMillis?: null|string,
     *   DataScannedInBytes?: null|string,
     *   DataManifestLocation?: null|string,
     *   TotalExecutionTimeInMillis?: null|string,
     *   QueryQueueTimeInMillis?: null|string,
     *   QueryPlanningTimeInMillis?: null|string,
     *   ServiceProcessingTimeInMillis?: null|string,
     *   ResultReuseInformation?: null|ResultReuseInformation|array,
     * }|QueryExecutionStatistics $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDataManifestLocation(): ?string
    {
        return $this->dataManifestLocation;
    }

    public function getDataScannedInBytes(): ?string
    {
        return $this->dataScannedInBytes;
    }

    public function getEngineExecutionTimeInMillis(): ?string
    {
        return $this->engineExecutionTimeInMillis;
    }

    public function getQueryPlanningTimeInMillis(): ?string
    {
        return $this->queryPlanningTimeInMillis;
    }

    public function getQueryQueueTimeInMillis(): ?string
    {
        return $this->queryQueueTimeInMillis;
    }

    public function getResultReuseInformation(): ?ResultReuseInformation
    {
        return $this->resultReuseInformation;
    }

    public function getServiceProcessingTimeInMillis(): ?string
    {
        return $this->serviceProcessingTimeInMillis;
    }

    public function getTotalExecutionTimeInMillis(): ?string
    {
        return $this->totalExecutionTimeInMillis;
    }
}
