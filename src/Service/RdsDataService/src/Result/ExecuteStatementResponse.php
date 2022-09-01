<?php

namespace AsyncAws\RdsDataService\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\RdsDataService\ValueObject\ArrayValue;
use AsyncAws\RdsDataService\ValueObject\ColumnMetadata;
use AsyncAws\RdsDataService\ValueObject\Field;

/**
 * The response elements represent the output of a request to run a SQL statement against a database.
 */
class ExecuteStatementResponse extends Result
{
    /**
     * The records returned by the SQL statement. This field is blank if the `formatRecordsAs` parameter is set to `JSON`.
     */
    private $records;

    /**
     * Metadata for the columns included in the results. This field is blank if the `formatRecordsAs` parameter is set to
     * `JSON`.
     */
    private $columnMetadata;

    /**
     * The number of records updated by the request.
     */
    private $numberOfRecordsUpdated;

    /**
     * Values for fields generated during a DML request.
     */
    private $generatedFields;

    /**
     * A string value that represents the result set of a `SELECT` statement in JSON format. This value is only present when
     * the `formatRecordsAs` parameter is set to `JSON`.
     */
    private $formattedRecords;

    /**
     * @return ColumnMetadata[]
     */
    public function getColumnMetadata(): array
    {
        $this->initialize();

        return $this->columnMetadata;
    }

    public function getFormattedRecords(): ?string
    {
        $this->initialize();

        return $this->formattedRecords;
    }

    /**
     * @return Field[]
     */
    public function getGeneratedFields(): array
    {
        $this->initialize();

        return $this->generatedFields;
    }

    public function getNumberOfRecordsUpdated(): ?string
    {
        $this->initialize();

        return $this->numberOfRecordsUpdated;
    }

    /**
     * @return Field[][]
     */
    public function getRecords(): array
    {
        $this->initialize();

        return $this->records;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->records = empty($data['records']) ? [] : $this->populateResultSqlRecords($data['records']);
        $this->columnMetadata = empty($data['columnMetadata']) ? [] : $this->populateResultMetadata($data['columnMetadata']);
        $this->numberOfRecordsUpdated = isset($data['numberOfRecordsUpdated']) ? (string) $data['numberOfRecordsUpdated'] : null;
        $this->generatedFields = empty($data['generatedFields']) ? [] : $this->populateResultFieldList($data['generatedFields']);
        $this->formattedRecords = isset($data['formattedRecords']) ? (string) $data['formattedRecords'] : null;
    }

    /**
     * @return ArrayValue[]
     */
    private function populateResultArrayOfArray(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultArrayValue($item);
        }

        return $items;
    }

    private function populateResultArrayValue(array $json): ArrayValue
    {
        return new ArrayValue([
            'booleanValues' => !isset($json['booleanValues']) ? null : $this->populateResultBooleanArray($json['booleanValues']),
            'longValues' => !isset($json['longValues']) ? null : $this->populateResultLongArray($json['longValues']),
            'doubleValues' => !isset($json['doubleValues']) ? null : $this->populateResultDoubleArray($json['doubleValues']),
            'stringValues' => !isset($json['stringValues']) ? null : $this->populateResultStringArray($json['stringValues']),
            'arrayValues' => !isset($json['arrayValues']) ? null : $this->populateResultArrayOfArray($json['arrayValues']),
        ]);
    }

    /**
     * @return bool[]
     */
    private function populateResultBooleanArray(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? filter_var($item, \FILTER_VALIDATE_BOOLEAN) : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }

    private function populateResultColumnMetadata(array $json): ColumnMetadata
    {
        return new ColumnMetadata([
            'name' => isset($json['name']) ? (string) $json['name'] : null,
            'type' => isset($json['type']) ? (int) $json['type'] : null,
            'typeName' => isset($json['typeName']) ? (string) $json['typeName'] : null,
            'label' => isset($json['label']) ? (string) $json['label'] : null,
            'schemaName' => isset($json['schemaName']) ? (string) $json['schemaName'] : null,
            'tableName' => isset($json['tableName']) ? (string) $json['tableName'] : null,
            'isAutoIncrement' => isset($json['isAutoIncrement']) ? filter_var($json['isAutoIncrement'], \FILTER_VALIDATE_BOOLEAN) : null,
            'isSigned' => isset($json['isSigned']) ? filter_var($json['isSigned'], \FILTER_VALIDATE_BOOLEAN) : null,
            'isCurrency' => isset($json['isCurrency']) ? filter_var($json['isCurrency'], \FILTER_VALIDATE_BOOLEAN) : null,
            'isCaseSensitive' => isset($json['isCaseSensitive']) ? filter_var($json['isCaseSensitive'], \FILTER_VALIDATE_BOOLEAN) : null,
            'nullable' => isset($json['nullable']) ? (int) $json['nullable'] : null,
            'precision' => isset($json['precision']) ? (int) $json['precision'] : null,
            'scale' => isset($json['scale']) ? (int) $json['scale'] : null,
            'arrayBaseColumnType' => isset($json['arrayBaseColumnType']) ? (int) $json['arrayBaseColumnType'] : null,
        ]);
    }

    /**
     * @return float[]
     */
    private function populateResultDoubleArray(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (float) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }

    private function populateResultField(array $json): Field
    {
        return new Field([
            'isNull' => isset($json['isNull']) ? filter_var($json['isNull'], \FILTER_VALIDATE_BOOLEAN) : null,
            'booleanValue' => isset($json['booleanValue']) ? filter_var($json['booleanValue'], \FILTER_VALIDATE_BOOLEAN) : null,
            'longValue' => isset($json['longValue']) ? (string) $json['longValue'] : null,
            'doubleValue' => isset($json['doubleValue']) ? (float) $json['doubleValue'] : null,
            'stringValue' => isset($json['stringValue']) ? (string) $json['stringValue'] : null,
            'blobValue' => isset($json['blobValue']) ? base64_decode((string) $json['blobValue']) : null,
            'arrayValue' => empty($json['arrayValue']) ? null : $this->populateResultArrayValue($json['arrayValue']),
        ]);
    }

    /**
     * @return Field[]
     */
    private function populateResultFieldList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultField($item);
        }

        return $items;
    }

    /**
     * @return string[]
     */
    private function populateResultLongArray(array $json): array
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

    /**
     * @return ColumnMetadata[]
     */
    private function populateResultMetadata(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultColumnMetadata($item);
        }

        return $items;
    }

    /**
     * @return Field[][]
     */
    private function populateResultSqlRecords(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultFieldList($item);
        }

        return $items;
    }

    /**
     * @return string[]
     */
    private function populateResultStringArray(array $json): array
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
}
