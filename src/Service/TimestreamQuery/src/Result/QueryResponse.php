<?php

namespace AsyncAws\TimestreamQuery\Result;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\TimestreamQuery\Enum\ScalarType;
use AsyncAws\TimestreamQuery\Input\QueryRequest;
use AsyncAws\TimestreamQuery\TimestreamQueryClient;
use AsyncAws\TimestreamQuery\ValueObject\ColumnInfo;
use AsyncAws\TimestreamQuery\ValueObject\Datum;
use AsyncAws\TimestreamQuery\ValueObject\QueryInsightsResponse;
use AsyncAws\TimestreamQuery\ValueObject\QuerySpatialCoverage;
use AsyncAws\TimestreamQuery\ValueObject\QuerySpatialCoverageMax;
use AsyncAws\TimestreamQuery\ValueObject\QueryStatus;
use AsyncAws\TimestreamQuery\ValueObject\QueryTemporalRange;
use AsyncAws\TimestreamQuery\ValueObject\QueryTemporalRangeMax;
use AsyncAws\TimestreamQuery\ValueObject\Row;
use AsyncAws\TimestreamQuery\ValueObject\TimeSeriesDataPoint;
use AsyncAws\TimestreamQuery\ValueObject\Type;

/**
 * @implements \IteratorAggregate<Row>
 */
class QueryResponse extends Result implements \IteratorAggregate
{
    /**
     * A unique ID for the given query.
     *
     * @var string
     */
    private $queryId;

    /**
     * A pagination token that can be used again on a `Query` call to get the next set of results.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * The result set rows returned by the query.
     *
     * @var Row[]
     */
    private $rows;

    /**
     * The column data types of the returned result set.
     *
     * @var ColumnInfo[]
     */
    private $columnInfo;

    /**
     * Information about the status of the query, including progress and bytes scanned.
     *
     * @var QueryStatus|null
     */
    private $queryStatus;

    /**
     * Encapsulates `QueryInsights` containing insights and metrics related to the query that you executed.
     *
     * @var QueryInsightsResponse|null
     */
    private $queryInsightsResponse;

    /**
     * @return ColumnInfo[]
     */
    public function getColumnInfo(): array
    {
        $this->initialize();

        return $this->columnInfo;
    }

    /**
     * Iterates over Rows.
     *
     * @return \Traversable<Row>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getRows();
    }

    public function getNextToken(): ?string
    {
        $this->initialize();

        return $this->nextToken;
    }

    public function getQueryId(): string
    {
        $this->initialize();

        return $this->queryId;
    }

    public function getQueryInsightsResponse(): ?QueryInsightsResponse
    {
        $this->initialize();

        return $this->queryInsightsResponse;
    }

    public function getQueryStatus(): ?QueryStatus
    {
        $this->initialize();

        return $this->queryStatus;
    }

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<Row>
     */
    public function getRows(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->rows;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof TimestreamQueryClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof QueryRequest) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if (null !== $page->nextToken) {
                $input->setNextToken($page->nextToken);

                $this->registerPrefetch($nextPage = $client->query($input));
            } else {
                $nextPage = null;
            }

            yield from $page->rows;

            if (null === $nextPage) {
                break;
            }

            $this->unregisterPrefetch($nextPage);
            $page = $nextPage;
        }
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->queryId = (string) $data['QueryId'];
        $this->nextToken = isset($data['NextToken']) ? (string) $data['NextToken'] : null;
        $this->rows = $this->populateResultRowList($data['Rows'] ?? []);
        $this->columnInfo = $this->populateResultColumnInfoList($data['ColumnInfo'] ?? []);
        $this->queryStatus = empty($data['QueryStatus']) ? null : $this->populateResultQueryStatus($data['QueryStatus']);
        $this->queryInsightsResponse = empty($data['QueryInsightsResponse']) ? null : $this->populateResultQueryInsightsResponse($data['QueryInsightsResponse']);
    }

    private function populateResultColumnInfo(array $json): ColumnInfo
    {
        return new ColumnInfo([
            'Name' => isset($json['Name']) ? (string) $json['Name'] : null,
            'Type' => $this->populateResultType($json['Type']),
        ]);
    }

    /**
     * @return ColumnInfo[]
     */
    private function populateResultColumnInfoList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultColumnInfo($item);
        }

        return $items;
    }

    private function populateResultDatum(array $json): Datum
    {
        return new Datum([
            'ScalarValue' => isset($json['ScalarValue']) ? (string) $json['ScalarValue'] : null,
            'TimeSeriesValue' => !isset($json['TimeSeriesValue']) ? null : $this->populateResultTimeSeriesDataPointList($json['TimeSeriesValue']),
            'ArrayValue' => !isset($json['ArrayValue']) ? null : $this->populateResultDatumList($json['ArrayValue']),
            'RowValue' => empty($json['RowValue']) ? null : $this->populateResultRow($json['RowValue']),
            'NullValue' => isset($json['NullValue']) ? filter_var($json['NullValue'], \FILTER_VALIDATE_BOOLEAN) : null,
        ]);
    }

    /**
     * @return Datum[]
     */
    private function populateResultDatumList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultDatum($item);
        }

        return $items;
    }

    /**
     * @return string[]
     */
    private function populateResultPartitionKeyList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (string) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }

    private function populateResultQueryInsightsResponse(array $json): QueryInsightsResponse
    {
        return new QueryInsightsResponse([
            'QuerySpatialCoverage' => empty($json['QuerySpatialCoverage']) ? null : $this->populateResultQuerySpatialCoverage($json['QuerySpatialCoverage']),
            'QueryTemporalRange' => empty($json['QueryTemporalRange']) ? null : $this->populateResultQueryTemporalRange($json['QueryTemporalRange']),
            'QueryTableCount' => isset($json['QueryTableCount']) ? (int) $json['QueryTableCount'] : null,
            'OutputRows' => isset($json['OutputRows']) ? (int) $json['OutputRows'] : null,
            'OutputBytes' => isset($json['OutputBytes']) ? (int) $json['OutputBytes'] : null,
            'UnloadPartitionCount' => isset($json['UnloadPartitionCount']) ? (int) $json['UnloadPartitionCount'] : null,
            'UnloadWrittenRows' => isset($json['UnloadWrittenRows']) ? (int) $json['UnloadWrittenRows'] : null,
            'UnloadWrittenBytes' => isset($json['UnloadWrittenBytes']) ? (int) $json['UnloadWrittenBytes'] : null,
        ]);
    }

    private function populateResultQuerySpatialCoverage(array $json): QuerySpatialCoverage
    {
        return new QuerySpatialCoverage([
            'Max' => empty($json['Max']) ? null : $this->populateResultQuerySpatialCoverageMax($json['Max']),
        ]);
    }

    private function populateResultQuerySpatialCoverageMax(array $json): QuerySpatialCoverageMax
    {
        return new QuerySpatialCoverageMax([
            'Value' => isset($json['Value']) ? (float) $json['Value'] : null,
            'TableArn' => isset($json['TableArn']) ? (string) $json['TableArn'] : null,
            'PartitionKey' => !isset($json['PartitionKey']) ? null : $this->populateResultPartitionKeyList($json['PartitionKey']),
        ]);
    }

    private function populateResultQueryStatus(array $json): QueryStatus
    {
        return new QueryStatus([
            'ProgressPercentage' => isset($json['ProgressPercentage']) ? (float) $json['ProgressPercentage'] : null,
            'CumulativeBytesScanned' => isset($json['CumulativeBytesScanned']) ? (int) $json['CumulativeBytesScanned'] : null,
            'CumulativeBytesMetered' => isset($json['CumulativeBytesMetered']) ? (int) $json['CumulativeBytesMetered'] : null,
        ]);
    }

    private function populateResultQueryTemporalRange(array $json): QueryTemporalRange
    {
        return new QueryTemporalRange([
            'Max' => empty($json['Max']) ? null : $this->populateResultQueryTemporalRangeMax($json['Max']),
        ]);
    }

    private function populateResultQueryTemporalRangeMax(array $json): QueryTemporalRangeMax
    {
        return new QueryTemporalRangeMax([
            'Value' => isset($json['Value']) ? (int) $json['Value'] : null,
            'TableArn' => isset($json['TableArn']) ? (string) $json['TableArn'] : null,
        ]);
    }

    private function populateResultRow(array $json): Row
    {
        return new Row([
            'Data' => $this->populateResultDatumList($json['Data']),
        ]);
    }

    /**
     * @return Row[]
     */
    private function populateResultRowList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultRow($item);
        }

        return $items;
    }

    private function populateResultTimeSeriesDataPoint(array $json): TimeSeriesDataPoint
    {
        return new TimeSeriesDataPoint([
            'Time' => (string) $json['Time'],
            'Value' => $this->populateResultDatum($json['Value']),
        ]);
    }

    /**
     * @return TimeSeriesDataPoint[]
     */
    private function populateResultTimeSeriesDataPointList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultTimeSeriesDataPoint($item);
        }

        return $items;
    }

    private function populateResultType(array $json): Type
    {
        return new Type([
            'ScalarType' => isset($json['ScalarType']) ? (!ScalarType::exists((string) $json['ScalarType']) ? ScalarType::UNKNOWN_TO_SDK : (string) $json['ScalarType']) : null,
            'ArrayColumnInfo' => empty($json['ArrayColumnInfo']) ? null : $this->populateResultColumnInfo($json['ArrayColumnInfo']),
            'TimeSeriesMeasureValueColumnInfo' => empty($json['TimeSeriesMeasureValueColumnInfo']) ? null : $this->populateResultColumnInfo($json['TimeSeriesMeasureValueColumnInfo']),
            'RowColumnInfo' => !isset($json['RowColumnInfo']) ? null : $this->populateResultColumnInfoList($json['RowColumnInfo']),
        ]);
    }
}
