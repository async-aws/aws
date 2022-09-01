<?php

namespace AsyncAws\RdsDataService\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\RdsDataService\ValueObject\ArrayValue;
use AsyncAws\RdsDataService\ValueObject\Field;
use AsyncAws\RdsDataService\ValueObject\UpdateResult;

/**
 * The response elements represent the output of a SQL statement over an array of data.
 */
class BatchExecuteStatementResponse extends Result
{
    /**
     * The execution results of each batch entry.
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
