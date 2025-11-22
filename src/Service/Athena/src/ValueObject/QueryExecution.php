<?php

namespace AsyncAws\Athena\ValueObject;

use AsyncAws\Athena\Enum\StatementType;

/**
 * Information about a single instance of a query execution.
 */
final class QueryExecution
{
    /**
     * The unique identifier for each query execution.
     *
     * @var string|null
     */
    private $queryExecutionId;

    /**
     * The SQL query statements which the query execution ran.
     *
     * @var string|null
     */
    private $query;

    /**
     * The type of query statement that was run. `DDL` indicates DDL query statements. `DML` indicates DML (Data
     * Manipulation Language) query statements, such as `CREATE TABLE AS SELECT`. `UTILITY` indicates query statements other
     * than DDL and DML, such as `SHOW CREATE TABLE`, `EXPLAIN`, `DESCRIBE`, or `SHOW TABLES`.
     *
     * @var StatementType::*|null
     */
    private $statementType;

    /**
     * The configuration for storing results in Athena owned storage, which includes whether this feature is enabled;
     * whether encryption configuration, if any, is used for encrypting query results.
     *
     * @var ManagedQueryResultsConfiguration|null
     */
    private $managedQueryResultsConfiguration;

    /**
     * The location in Amazon S3 where query and calculation results are stored and the encryption option, if any, used for
     * query results. These are known as "client-side settings". If workgroup settings override client-side settings, then
     * the query uses the location for the query results and the encryption configuration that are specified for the
     * workgroup.
     *
     * @var ResultConfiguration|null
     */
    private $resultConfiguration;

    /**
     * Specifies the query result reuse behavior that was used for the query.
     *
     * @var ResultReuseConfiguration|null
     */
    private $resultReuseConfiguration;

    /**
     * The database in which the query execution occurred.
     *
     * @var QueryExecutionContext|null
     */
    private $queryExecutionContext;

    /**
     * The completion date, current state, submission time, and state change reason (if applicable) for the query execution.
     *
     * @var QueryExecutionStatus|null
     */
    private $status;

    /**
     * Query execution statistics, such as the amount of data scanned, the amount of time that the query took to process,
     * and the type of statement that was run.
     *
     * @var QueryExecutionStatistics|null
     */
    private $statistics;

    /**
     * The name of the workgroup in which the query ran.
     *
     * @var string|null
     */
    private $workGroup;

    /**
     * The engine version that executed the query.
     *
     * @var EngineVersion|null
     */
    private $engineVersion;

    /**
     * A list of values for the parameters in a query. The values are applied sequentially to the parameters in the query in
     * the order in which the parameters occur. The list of parameters is not returned in the response.
     *
     * @var string[]|null
     */
    private $executionParameters;

    /**
     * The kind of query statement that was run.
     *
     * @var string|null
     */
    private $substatementType;

    /**
     * Specifies whether Amazon S3 access grants are enabled for query results.
     *
     * @var QueryResultsS3AccessGrantsConfiguration|null
     */
    private $queryResultsS3AccessGrantsConfiguration;

    /**
     * @param array{
     *   QueryExecutionId?: string|null,
     *   Query?: string|null,
     *   StatementType?: StatementType::*|null,
     *   ManagedQueryResultsConfiguration?: ManagedQueryResultsConfiguration|array|null,
     *   ResultConfiguration?: ResultConfiguration|array|null,
     *   ResultReuseConfiguration?: ResultReuseConfiguration|array|null,
     *   QueryExecutionContext?: QueryExecutionContext|array|null,
     *   Status?: QueryExecutionStatus|array|null,
     *   Statistics?: QueryExecutionStatistics|array|null,
     *   WorkGroup?: string|null,
     *   EngineVersion?: EngineVersion|array|null,
     *   ExecutionParameters?: string[]|null,
     *   SubstatementType?: string|null,
     *   QueryResultsS3AccessGrantsConfiguration?: QueryResultsS3AccessGrantsConfiguration|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->queryExecutionId = $input['QueryExecutionId'] ?? null;
        $this->query = $input['Query'] ?? null;
        $this->statementType = $input['StatementType'] ?? null;
        $this->managedQueryResultsConfiguration = isset($input['ManagedQueryResultsConfiguration']) ? ManagedQueryResultsConfiguration::create($input['ManagedQueryResultsConfiguration']) : null;
        $this->resultConfiguration = isset($input['ResultConfiguration']) ? ResultConfiguration::create($input['ResultConfiguration']) : null;
        $this->resultReuseConfiguration = isset($input['ResultReuseConfiguration']) ? ResultReuseConfiguration::create($input['ResultReuseConfiguration']) : null;
        $this->queryExecutionContext = isset($input['QueryExecutionContext']) ? QueryExecutionContext::create($input['QueryExecutionContext']) : null;
        $this->status = isset($input['Status']) ? QueryExecutionStatus::create($input['Status']) : null;
        $this->statistics = isset($input['Statistics']) ? QueryExecutionStatistics::create($input['Statistics']) : null;
        $this->workGroup = $input['WorkGroup'] ?? null;
        $this->engineVersion = isset($input['EngineVersion']) ? EngineVersion::create($input['EngineVersion']) : null;
        $this->executionParameters = $input['ExecutionParameters'] ?? null;
        $this->substatementType = $input['SubstatementType'] ?? null;
        $this->queryResultsS3AccessGrantsConfiguration = isset($input['QueryResultsS3AccessGrantsConfiguration']) ? QueryResultsS3AccessGrantsConfiguration::create($input['QueryResultsS3AccessGrantsConfiguration']) : null;
    }

    /**
     * @param array{
     *   QueryExecutionId?: string|null,
     *   Query?: string|null,
     *   StatementType?: StatementType::*|null,
     *   ManagedQueryResultsConfiguration?: ManagedQueryResultsConfiguration|array|null,
     *   ResultConfiguration?: ResultConfiguration|array|null,
     *   ResultReuseConfiguration?: ResultReuseConfiguration|array|null,
     *   QueryExecutionContext?: QueryExecutionContext|array|null,
     *   Status?: QueryExecutionStatus|array|null,
     *   Statistics?: QueryExecutionStatistics|array|null,
     *   WorkGroup?: string|null,
     *   EngineVersion?: EngineVersion|array|null,
     *   ExecutionParameters?: string[]|null,
     *   SubstatementType?: string|null,
     *   QueryResultsS3AccessGrantsConfiguration?: QueryResultsS3AccessGrantsConfiguration|array|null,
     * }|QueryExecution $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getEngineVersion(): ?EngineVersion
    {
        return $this->engineVersion;
    }

    /**
     * @return string[]
     */
    public function getExecutionParameters(): array
    {
        return $this->executionParameters ?? [];
    }

    public function getManagedQueryResultsConfiguration(): ?ManagedQueryResultsConfiguration
    {
        return $this->managedQueryResultsConfiguration;
    }

    public function getQuery(): ?string
    {
        return $this->query;
    }

    public function getQueryExecutionContext(): ?QueryExecutionContext
    {
        return $this->queryExecutionContext;
    }

    public function getQueryExecutionId(): ?string
    {
        return $this->queryExecutionId;
    }

    public function getQueryResultsS3AccessGrantsConfiguration(): ?QueryResultsS3AccessGrantsConfiguration
    {
        return $this->queryResultsS3AccessGrantsConfiguration;
    }

    public function getResultConfiguration(): ?ResultConfiguration
    {
        return $this->resultConfiguration;
    }

    public function getResultReuseConfiguration(): ?ResultReuseConfiguration
    {
        return $this->resultReuseConfiguration;
    }

    /**
     * @return StatementType::*|null
     */
    public function getStatementType(): ?string
    {
        return $this->statementType;
    }

    public function getStatistics(): ?QueryExecutionStatistics
    {
        return $this->statistics;
    }

    public function getStatus(): ?QueryExecutionStatus
    {
        return $this->status;
    }

    public function getSubstatementType(): ?string
    {
        return $this->substatementType;
    }

    public function getWorkGroup(): ?string
    {
        return $this->workGroup;
    }
}
