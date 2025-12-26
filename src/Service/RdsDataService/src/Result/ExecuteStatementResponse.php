<?php

namespace AsyncAws\RdsDataService\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\RdsDataService\ValueObject\ArrayValue;
use AsyncAws\RdsDataService\ValueObject\ArrayValueMemberArrayValues;
use AsyncAws\RdsDataService\ValueObject\ArrayValueMemberBooleanValues;
use AsyncAws\RdsDataService\ValueObject\ArrayValueMemberDoubleValues;
use AsyncAws\RdsDataService\ValueObject\ArrayValueMemberLongValues;
use AsyncAws\RdsDataService\ValueObject\ArrayValueMemberStringValues;
use AsyncAws\RdsDataService\ValueObject\ArrayValueMemberUnknownToSdk;
use AsyncAws\RdsDataService\ValueObject\ColumnMetadata;
use AsyncAws\RdsDataService\ValueObject\Field;
use AsyncAws\RdsDataService\ValueObject\FieldMemberArrayValue;
use AsyncAws\RdsDataService\ValueObject\FieldMemberBlobValue;
use AsyncAws\RdsDataService\ValueObject\FieldMemberBooleanValue;
use AsyncAws\RdsDataService\ValueObject\FieldMemberDoubleValue;
use AsyncAws\RdsDataService\ValueObject\FieldMemberIsNull;
use AsyncAws\RdsDataService\ValueObject\FieldMemberLongValue;
use AsyncAws\RdsDataService\ValueObject\FieldMemberStringValue;
use AsyncAws\RdsDataService\ValueObject\FieldMemberUnknownToSdk;

/**
 * The response elements represent the output of a request to run a SQL statement against a database.
 */
class ExecuteStatementResponse extends Result
{
    /**
     * The records returned by the SQL statement. This field is blank if the `formatRecordsAs` parameter is set to `JSON`.
     *
     * @var Field[][]
     */
    private $records;

    /**
     * Metadata for the columns included in the results. This field is blank if the `formatRecordsAs` parameter is set to
     * `JSON`.
     *
     * @var ColumnMetadata[]
     */
    private $columnMetadata;

    /**
     * The number of records updated by the request.
     *
     * @var int|null
     */
    private $numberOfRecordsUpdated;

    /**
     * Values for fields generated during a DML request.
     *
     * > The `generatedFields` data isn't supported by Aurora PostgreSQL. To get the values of generated fields, use the
     * > `RETURNING` clause. For more information, see Returning Data From Modified Rows [^1] in the PostgreSQL
     * > documentation.
     *
     * [^1]: https://www.postgresql.org/docs/10/dml-returning.html
     *
     * @var Field[]
     */
    private $generatedFields;

    /**
     * A string value that represents the result set of a `SELECT` statement in JSON format. This value is only present when
     * the `formatRecordsAs` parameter is set to `JSON`.
     *
     * The size limit for this field is currently 10 MB. If the JSON-formatted string representing the result set requires
     * more than 10 MB, the call returns an error.
     *
     * @var string|null
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

    public function getNumberOfRecordsUpdated(): ?int
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
        $this->numberOfRecordsUpdated = isset($data['numberOfRecordsUpdated']) ? (int) $data['numberOfRecordsUpdated'] : null;
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
        if (isset($json['booleanValues'])) {
            return $this->populateResultArrayValueMemberBooleanValues($json);
        }
        if (isset($json['longValues'])) {
            return $this->populateResultArrayValueMemberLongValues($json);
        }
        if (isset($json['doubleValues'])) {
            return $this->populateResultArrayValueMemberDoubleValues($json);
        }
        if (isset($json['stringValues'])) {
            return $this->populateResultArrayValueMemberStringValues($json);
        }
        if (isset($json['arrayValues'])) {
            return $this->populateResultArrayValueMemberArrayValues($json);
        }

        return $this->populateResultArrayValueMemberUnknownToSdk($json);
    }

    private function populateResultArrayValueMemberArrayValues(array $json): ArrayValueMemberArrayValues
    {
        return new ArrayValueMemberArrayValues([
            'arrayValues' => $this->populateResultArrayOfArray($json['arrayValues']),
        ]);
    }

    private function populateResultArrayValueMemberBooleanValues(array $json): ArrayValueMemberBooleanValues
    {
        return new ArrayValueMemberBooleanValues([
            'booleanValues' => $this->populateResultBooleanArray($json['booleanValues']),
        ]);
    }

    private function populateResultArrayValueMemberDoubleValues(array $json): ArrayValueMemberDoubleValues
    {
        return new ArrayValueMemberDoubleValues([
            'doubleValues' => $this->populateResultDoubleArray($json['doubleValues']),
        ]);
    }

    private function populateResultArrayValueMemberLongValues(array $json): ArrayValueMemberLongValues
    {
        return new ArrayValueMemberLongValues([
            'longValues' => $this->populateResultLongArray($json['longValues']),
        ]);
    }

    private function populateResultArrayValueMemberStringValues(array $json): ArrayValueMemberStringValues
    {
        return new ArrayValueMemberStringValues([
            'stringValues' => $this->populateResultStringArray($json['stringValues']),
        ]);
    }

    private function populateResultArrayValueMemberUnknownToSdk(array $json): ArrayValue
    {
        return new ArrayValueMemberUnknownToSdk($json);
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
        if (isset($json['isNull'])) {
            return $this->populateResultFieldMemberIsNull($json);
        }
        if (isset($json['booleanValue'])) {
            return $this->populateResultFieldMemberBooleanValue($json);
        }
        if (isset($json['longValue'])) {
            return $this->populateResultFieldMemberLongValue($json);
        }
        if (isset($json['doubleValue'])) {
            return $this->populateResultFieldMemberDoubleValue($json);
        }
        if (isset($json['stringValue'])) {
            return $this->populateResultFieldMemberStringValue($json);
        }
        if (isset($json['blobValue'])) {
            return $this->populateResultFieldMemberBlobValue($json);
        }
        if (isset($json['arrayValue'])) {
            return $this->populateResultFieldMemberArrayValue($json);
        }

        return $this->populateResultFieldMemberUnknownToSdk($json);
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

    private function populateResultFieldMemberArrayValue(array $json): FieldMemberArrayValue
    {
        return new FieldMemberArrayValue([
            'arrayValue' => $this->populateResultArrayValue($json['arrayValue']),
        ]);
    }

    private function populateResultFieldMemberBlobValue(array $json): FieldMemberBlobValue
    {
        return new FieldMemberBlobValue([
            'blobValue' => base64_decode((string) $json['blobValue']),
        ]);
    }

    private function populateResultFieldMemberBooleanValue(array $json): FieldMemberBooleanValue
    {
        return new FieldMemberBooleanValue([
            'booleanValue' => filter_var($json['booleanValue'], \FILTER_VALIDATE_BOOLEAN),
        ]);
    }

    private function populateResultFieldMemberDoubleValue(array $json): FieldMemberDoubleValue
    {
        return new FieldMemberDoubleValue([
            'doubleValue' => (float) $json['doubleValue'],
        ]);
    }

    private function populateResultFieldMemberIsNull(array $json): FieldMemberIsNull
    {
        return new FieldMemberIsNull([
            'isNull' => filter_var($json['isNull'], \FILTER_VALIDATE_BOOLEAN),
        ]);
    }

    private function populateResultFieldMemberLongValue(array $json): FieldMemberLongValue
    {
        return new FieldMemberLongValue([
            'longValue' => (int) $json['longValue'],
        ]);
    }

    private function populateResultFieldMemberStringValue(array $json): FieldMemberStringValue
    {
        return new FieldMemberStringValue([
            'stringValue' => (string) $json['stringValue'],
        ]);
    }

    private function populateResultFieldMemberUnknownToSdk(array $json): Field
    {
        return new FieldMemberUnknownToSdk($json);
    }

    /**
     * @return int[]
     */
    private function populateResultLongArray(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (int) $item : null;
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
            $items[] = $this->populateResultFieldList($item ?? []);
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
