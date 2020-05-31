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
        $fn = [];
        $fn['list-Metadata'] = static function (array $json) use (&$fn): array {
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
        };
        $fn['list-FieldList'] = static function (array $json) use (&$fn): array {
            $items = [];
            foreach ($json as $item) {
                $items[] = new Field([
                    'arrayValue' => empty($item['arrayValue']) ? null : new ArrayValue([
                        'arrayValues' => empty($item['arrayValue']['arrayValues']) ? [] : $fn['list-ArrayOfArray']($item['arrayValue']['arrayValues']),
                        'booleanValues' => empty($item['arrayValue']['booleanValues']) ? [] : $fn['list-BooleanArray']($item['arrayValue']['booleanValues']),
                        'doubleValues' => empty($item['arrayValue']['doubleValues']) ? [] : $fn['list-DoubleArray']($item['arrayValue']['doubleValues']),
                        'longValues' => empty($item['arrayValue']['longValues']) ? [] : $fn['list-LongArray']($item['arrayValue']['longValues']),
                        'stringValues' => empty($item['arrayValue']['stringValues']) ? [] : $fn['list-StringArray']($item['arrayValue']['stringValues']),
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
        };
        $fn['list-ArrayOfArray'] = static function (array $json) use (&$fn): array {
            $items = [];
            foreach ($json as $item) {
                $items[] = new ArrayValue([
                    'arrayValues' => empty($item['arrayValues']) ? [] : $fn['list-ArrayOfArray']($item['arrayValues']),
                    'booleanValues' => empty($item['booleanValues']) ? [] : $fn['list-BooleanArray']($item['booleanValues']),
                    'doubleValues' => empty($item['doubleValues']) ? [] : $fn['list-DoubleArray']($item['doubleValues']),
                    'longValues' => empty($item['longValues']) ? [] : $fn['list-LongArray']($item['longValues']),
                    'stringValues' => empty($item['stringValues']) ? [] : $fn['list-StringArray']($item['stringValues']),
                ]);
            }

            return $items;
        };
        $fn['list-BooleanArray'] = static function (array $json) use (&$fn): array {
            $items = [];
            foreach ($json as $item) {
                $a = isset($item) ? filter_var($item, \FILTER_VALIDATE_BOOLEAN) : null;
                if (null !== $a) {
                    $items[] = $a;
                }
            }

            return $items;
        };
        $fn['list-DoubleArray'] = static function (array $json) use (&$fn): array {
            $items = [];
            foreach ($json as $item) {
                $a = isset($item) ? (float) $item : null;
                if (null !== $a) {
                    $items[] = $a;
                }
            }

            return $items;
        };
        $fn['list-LongArray'] = static function (array $json) use (&$fn): array {
            $items = [];
            foreach ($json as $item) {
                $a = isset($item) ? (string) $item : null;
                if (null !== $a) {
                    $items[] = $a;
                }
            }

            return $items;
        };
        $fn['list-StringArray'] = static function (array $json) use (&$fn): array {
            $items = [];
            foreach ($json as $item) {
                $a = isset($item) ? (string) $item : null;
                if (null !== $a) {
                    $items[] = $a;
                }
            }

            return $items;
        };
        $fn['list-SqlRecords'] = static function (array $json) use (&$fn): array {
            $items = [];
            foreach ($json as $item) {
                $a = empty($item) ? [] : $fn['list-FieldList']($item);
                if (null !== $a) {
                    $items[] = $a;
                }
            }

            return $items;
        };
        $this->columnMetadata = empty($data['columnMetadata']) ? [] : $fn['list-Metadata']($data['columnMetadata']);
        $this->generatedFields = empty($data['generatedFields']) ? [] : $fn['list-FieldList']($data['generatedFields']);
        $this->numberOfRecordsUpdated = isset($data['numberOfRecordsUpdated']) ? (string) $data['numberOfRecordsUpdated'] : null;
        $this->records = empty($data['records']) ? [] : $fn['list-SqlRecords']($data['records']);
    }
}
