<?php

namespace AsyncAws\RdsDataService\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\RdsDataService\ValueObject\ArrayValue;
use AsyncAws\RdsDataService\ValueObject\ColumnMetadata;
use AsyncAws\RdsDataService\ValueObject\Field;

class ExecuteStatementResponse extends Result
{
    /**
     * Metadata for the columns included in the results.
     */
    private $columnMetadata = [];

    /**
     * Values for fields generated during the request.
     */
    private $generatedFields = [];

    /**
     * The number of records updated by the request.
     */
    private $numberOfRecordsUpdated;

    /**
     * The records returned by the SQL statement.
     */
    private $records = [];

    /**
     * @return ColumnMetadata[]
     */
    public function getColumnMetadata(): array
    {
        $this->initialize();

        return $this->columnMetadata;
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

        $this->columnMetadata = empty($data['columnMetadata']) ? [] : $this->populateResultMetadata($data['columnMetadata']);
        $this->generatedFields = empty($data['generatedFields']) ? [] : $this->populateResultFieldList($data['generatedFields']);
        $this->numberOfRecordsUpdated = isset($data['numberOfRecordsUpdated']) ? (string) $data['numberOfRecordsUpdated'] : null;
        $this->records = empty($data['records']) ? [] : $this->populateResultSqlRecords($data['records']);
    }

    /**
     * @return ArrayValue[]
     */
    private function populateResultArrayOfArray(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new ArrayValue([
                'arrayValues' => empty($item['arrayValues']) ? [] : $this->populateResultArrayOfArray($item['arrayValues']),
                'booleanValues' => empty($item['booleanValues']) ? [] : $this->populateResultBooleanArray($item['booleanValues']),
                'doubleValues' => empty($item['doubleValues']) ? [] : $this->populateResultDoubleArray($item['doubleValues']),
                'longValues' => empty($item['longValues']) ? [] : $this->populateResultLongArray($item['longValues']),
                'stringValues' => empty($item['stringValues']) ? [] : $this->populateResultStringArray($item['stringValues']),
            ]);
        }

        return $items;
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

    /**
     * @return Field[]
     */
    private function populateResultFieldList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new Field([
                'arrayValue' => empty($item['arrayValue']) ? null : new ArrayValue([
                    'arrayValues' => empty($item['arrayValue']['arrayValues']) ? [] : $this->populateResultArrayOfArray($item['arrayValue']['arrayValues']),
                    'booleanValues' => empty($item['arrayValue']['booleanValues']) ? [] : $this->populateResultBooleanArray($item['arrayValue']['booleanValues']),
                    'doubleValues' => empty($item['arrayValue']['doubleValues']) ? [] : $this->populateResultDoubleArray($item['arrayValue']['doubleValues']),
                    'longValues' => empty($item['arrayValue']['longValues']) ? [] : $this->populateResultLongArray($item['arrayValue']['longValues']),
                    'stringValues' => empty($item['arrayValue']['stringValues']) ? [] : $this->populateResultStringArray($item['arrayValue']['stringValues']),
                ]),
                'blobValue' => isset($item['blobValue']) ? base64_decode((string) $item['blobValue']) : null,
                'booleanValue' => isset($item['booleanValue']) ? filter_var($item['booleanValue'], \FILTER_VALIDATE_BOOLEAN) : null,
                'doubleValue' => isset($item['doubleValue']) ? (float) $item['doubleValue'] : null,
                'isNull' => isset($item['isNull']) ? filter_var($item['isNull'], \FILTER_VALIDATE_BOOLEAN) : null,
                'longValue' => isset($item['longValue']) ? (string) $item['longValue'] : null,
                'stringValue' => isset($item['stringValue']) ? (string) $item['stringValue'] : null,
            ]);
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
            $items[] = new ColumnMetadata([
                'arrayBaseColumnType' => isset($item['arrayBaseColumnType']) ? (int) $item['arrayBaseColumnType'] : null,
                'isAutoIncrement' => isset($item['isAutoIncrement']) ? filter_var($item['isAutoIncrement'], \FILTER_VALIDATE_BOOLEAN) : null,
                'isCaseSensitive' => isset($item['isCaseSensitive']) ? filter_var($item['isCaseSensitive'], \FILTER_VALIDATE_BOOLEAN) : null,
                'isCurrency' => isset($item['isCurrency']) ? filter_var($item['isCurrency'], \FILTER_VALIDATE_BOOLEAN) : null,
                'isSigned' => isset($item['isSigned']) ? filter_var($item['isSigned'], \FILTER_VALIDATE_BOOLEAN) : null,
                'label' => isset($item['label']) ? (string) $item['label'] : null,
                'name' => isset($item['name']) ? (string) $item['name'] : null,
                'nullable' => isset($item['nullable']) ? (int) $item['nullable'] : null,
                'precision' => isset($item['precision']) ? (int) $item['precision'] : null,
                'scale' => isset($item['scale']) ? (int) $item['scale'] : null,
                'schemaName' => isset($item['schemaName']) ? (string) $item['schemaName'] : null,
                'tableName' => isset($item['tableName']) ? (string) $item['tableName'] : null,
                'type' => isset($item['type']) ? (int) $item['type'] : null,
                'typeName' => isset($item['typeName']) ? (string) $item['typeName'] : null,
            ]);
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
            $a = empty($item) ? [] : $this->populateResultFieldList($item);
            if (null !== $a) {
                $items[] = $a;
            }
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
