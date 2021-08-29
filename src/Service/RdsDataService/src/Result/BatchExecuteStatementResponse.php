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

    /**
     * @return UpdateResult[]
     */
    private function populateResultUpdateResults(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new UpdateResult([
                'generatedFields' => empty($item['generatedFields']) ? [] : $this->populateResultFieldList($item['generatedFields']),
            ]);
        }

        return $items;
    }
}
