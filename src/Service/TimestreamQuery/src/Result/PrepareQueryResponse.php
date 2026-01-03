<?php

namespace AsyncAws\TimestreamQuery\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\TimestreamQuery\Enum\ScalarType;
use AsyncAws\TimestreamQuery\ValueObject\ColumnInfo;
use AsyncAws\TimestreamQuery\ValueObject\ParameterMapping;
use AsyncAws\TimestreamQuery\ValueObject\SelectColumn;
use AsyncAws\TimestreamQuery\ValueObject\Type;

class PrepareQueryResponse extends Result
{
    /**
     * The query string that you want prepare.
     *
     * @var string
     */
    private $queryString;

    /**
     * A list of SELECT clause columns of the submitted query string.
     *
     * @var SelectColumn[]
     */
    private $columns;

    /**
     * A list of parameters used in the submitted query string.
     *
     * @var ParameterMapping[]
     */
    private $parameters;

    /**
     * @return SelectColumn[]
     */
    public function getColumns(): array
    {
        $this->initialize();

        return $this->columns;
    }

    /**
     * @return ParameterMapping[]
     */
    public function getParameters(): array
    {
        $this->initialize();

        return $this->parameters;
    }

    public function getQueryString(): string
    {
        $this->initialize();

        return $this->queryString;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->queryString = (string) $data['QueryString'];
        $this->columns = $this->populateResultSelectColumnList($data['Columns'] ?? []);
        $this->parameters = $this->populateResultParameterMappingList($data['Parameters'] ?? []);
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

    private function populateResultParameterMapping(array $json): ParameterMapping
    {
        return new ParameterMapping([
            'Name' => (string) $json['Name'],
            'Type' => $this->populateResultType($json['Type']),
        ]);
    }

    /**
     * @return ParameterMapping[]
     */
    private function populateResultParameterMappingList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultParameterMapping($item);
        }

        return $items;
    }

    private function populateResultSelectColumn(array $json): SelectColumn
    {
        return new SelectColumn([
            'Name' => isset($json['Name']) ? (string) $json['Name'] : null,
            'Type' => empty($json['Type']) ? null : $this->populateResultType($json['Type']),
            'DatabaseName' => isset($json['DatabaseName']) ? (string) $json['DatabaseName'] : null,
            'TableName' => isset($json['TableName']) ? (string) $json['TableName'] : null,
            'Aliased' => isset($json['Aliased']) ? filter_var($json['Aliased'], \FILTER_VALIDATE_BOOLEAN) : null,
        ]);
    }

    /**
     * @return SelectColumn[]
     */
    private function populateResultSelectColumnList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultSelectColumn($item);
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
