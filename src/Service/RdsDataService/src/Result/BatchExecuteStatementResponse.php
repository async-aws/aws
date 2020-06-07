<?php

namespace AsyncAws\RdsDataService\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\RdsDataService\ValueObject\ArrayValue;
use AsyncAws\RdsDataService\ValueObject\Field;
use AsyncAws\RdsDataService\ValueObject\UpdateResult;

class BatchExecuteStatementResponse extends Result
{
    /**
     * The execution results of each batch entry.
     */
    private $updateResults = [];

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
        $fn = [];
        $fn['list-UpdateResults'] = static function (array $json) use (&$fn): array {
            $items = [];
            foreach ($json as $item) {
                $items[] = new UpdateResult([
                    'generatedFields' => empty($item['generatedFields']) ? [] : $fn['list-FieldList']($item['generatedFields']),
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
        $this->updateResults = empty($data['updateResults']) ? [] : $fn['list-UpdateResults']($data['updateResults']);
    }
}
