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
use AsyncAws\RdsDataService\ValueObject\Field;
use AsyncAws\RdsDataService\ValueObject\FieldMemberArrayValue;
use AsyncAws\RdsDataService\ValueObject\FieldMemberBlobValue;
use AsyncAws\RdsDataService\ValueObject\FieldMemberBooleanValue;
use AsyncAws\RdsDataService\ValueObject\FieldMemberDoubleValue;
use AsyncAws\RdsDataService\ValueObject\FieldMemberIsNull;
use AsyncAws\RdsDataService\ValueObject\FieldMemberLongValue;
use AsyncAws\RdsDataService\ValueObject\FieldMemberStringValue;
use AsyncAws\RdsDataService\ValueObject\FieldMemberUnknownToSdk;
use AsyncAws\RdsDataService\ValueObject\UpdateResult;

/**
 * The response elements represent the output of a SQL statement over an array of data.
 */
class BatchExecuteStatementResponse extends Result
{
    /**
     * The execution results of each batch entry.
     *
     * @var UpdateResult[]
     */
    private $updateResults;

    /**
     * @return UpdateResult[]
     */
    public function getUpdateResults(): array
    {
        $this->initialize();

        return $this->updateResults;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->updateResults = empty($data['updateResults']) ? [] : $this->populateResultUpdateResults($data['updateResults']);
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

    private function populateResultUpdateResult(array $json): UpdateResult
    {
        return new UpdateResult([
            'generatedFields' => !isset($json['generatedFields']) ? null : $this->populateResultFieldList($json['generatedFields']),
        ]);
    }

    /**
     * @return UpdateResult[]
     */
    private function populateResultUpdateResults(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultUpdateResult($item);
        }

        return $items;
    }
}
