<?php

namespace AsyncAws\DynamoDb\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\DynamoDb\ValueObject\AttributeValue;
use AsyncAws\DynamoDb\ValueObject\Capacity;
use AsyncAws\DynamoDb\ValueObject\ConsumedCapacity;
use AsyncAws\DynamoDb\ValueObject\DeleteRequest;
use AsyncAws\DynamoDb\ValueObject\ItemCollectionMetrics;
use AsyncAws\DynamoDb\ValueObject\PutRequest;
use AsyncAws\DynamoDb\ValueObject\WriteRequest;

class BatchWriteItemOutput extends Result
{
    /**
     * A map of tables and requests against those tables that were not processed. The `UnprocessedItems` value is in the
     * same form as `RequestItems`, so you can provide this value directly to a subsequent `BatchGetItem` operation. For
     * more information, see `RequestItems` in the Request Parameters section.
     */
    private $UnprocessedItems = [];

    /**
     * A list of tables that were processed by `BatchWriteItem` and, for each table, information about any item collections
     * that were affected by individual `DeleteItem` or `PutItem` operations.
     */
    private $ItemCollectionMetrics = [];

    /**
     * The capacity units consumed by the entire `BatchWriteItem` operation.
     */
    private $ConsumedCapacity = [];

    /**
     * @return ConsumedCapacity[]
     */
    public function getConsumedCapacity(): array
    {
        $this->initialize();

        return $this->ConsumedCapacity;
    }

    /**
     * @return array<string, array>
     */
    public function getItemCollectionMetrics(): array
    {
        $this->initialize();

        return $this->ItemCollectionMetrics;
    }

    /**
     * @return array<string, array>
     */
    public function getUnprocessedItems(): array
    {
        $this->initialize();

        return $this->UnprocessedItems;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();
        $fn = [];
        $fn['list-WriteRequests'] = static function (array $json) use (&$fn): array {
            $items = [];
            foreach ($json as $item) {
                $items[] = new WriteRequest([
                    'PutRequest' => empty($item['PutRequest']) ? null : new PutRequest([
                        'Item' => $fn['map-PutItemInputAttributeMap']($item['PutRequest']['Item']),
                    ]),
                    'DeleteRequest' => empty($item['DeleteRequest']) ? null : new DeleteRequest([
                        'Key' => $fn['map-Key']($item['DeleteRequest']['Key']),
                    ]),
                ]);
            }

            return $items;
        };

        /** @return array<string, \AsyncAws\DynamoDb\ValueObject\AttributeValue> */
        $fn['map-PutItemInputAttributeMap'] = static function (array $json): array {
            $items = [];
            foreach ($json as $name => $value) {
                $items[(string) $name] = AttributeValue::create($value);
            }

            return $items;
        };

        /** @return array<string, \AsyncAws\DynamoDb\ValueObject\AttributeValue> */
        $fn['map-Key'] = static function (array $json): array {
            $items = [];
            foreach ($json as $name => $value) {
                $items[(string) $name] = AttributeValue::create($value);
            }

            return $items;
        };
        $fn['list-ItemCollectionMetricsMultiple'] = static function (array $json) use (&$fn): array {
            $items = [];
            foreach ($json as $item) {
                $items[] = new ItemCollectionMetrics([
                    'ItemCollectionKey' => empty($item['ItemCollectionKey']) ? [] : $fn['map-ItemCollectionKeyAttributeMap']($item['ItemCollectionKey']),
                    'SizeEstimateRangeGB' => empty($item['SizeEstimateRangeGB']) ? [] : $fn['list-ItemCollectionSizeEstimateRange']($item['SizeEstimateRangeGB']),
                ]);
            }

            return $items;
        };

        /** @return array<string, \AsyncAws\DynamoDb\ValueObject\AttributeValue> */
        $fn['map-ItemCollectionKeyAttributeMap'] = static function (array $json): array {
            $items = [];
            foreach ($json as $name => $value) {
                $items[(string) $name] = AttributeValue::create($value);
            }

            return $items;
        };
        $fn['list-ItemCollectionSizeEstimateRange'] = static function (array $json) use (&$fn): array {
            $items = [];
            foreach ($json as $item) {
                $a = isset($item) ? (float) $item : null;
                if (null !== $a) {
                    $items[] = $a;
                }
            }

            return $items;
        };
        $fn['list-ConsumedCapacityMultiple'] = static function (array $json) use (&$fn): array {
            $items = [];
            foreach ($json as $item) {
                $items[] = new ConsumedCapacity([
                    'TableName' => isset($item['TableName']) ? (string) $item['TableName'] : null,
                    'CapacityUnits' => isset($item['CapacityUnits']) ? (float) $item['CapacityUnits'] : null,
                    'ReadCapacityUnits' => isset($item['ReadCapacityUnits']) ? (float) $item['ReadCapacityUnits'] : null,
                    'WriteCapacityUnits' => isset($item['WriteCapacityUnits']) ? (float) $item['WriteCapacityUnits'] : null,
                    'Table' => empty($item['Table']) ? null : new Capacity([
                        'ReadCapacityUnits' => isset($item['Table']['ReadCapacityUnits']) ? (float) $item['Table']['ReadCapacityUnits'] : null,
                        'WriteCapacityUnits' => isset($item['Table']['WriteCapacityUnits']) ? (float) $item['Table']['WriteCapacityUnits'] : null,
                        'CapacityUnits' => isset($item['Table']['CapacityUnits']) ? (float) $item['Table']['CapacityUnits'] : null,
                    ]),
                    'LocalSecondaryIndexes' => empty($item['LocalSecondaryIndexes']) ? [] : $fn['map-SecondaryIndexesCapacityMap']($item['LocalSecondaryIndexes']),
                    'GlobalSecondaryIndexes' => empty($item['GlobalSecondaryIndexes']) ? [] : $fn['map-SecondaryIndexesCapacityMap']($item['GlobalSecondaryIndexes']),
                ]);
            }

            return $items;
        };

        /** @return array<string, \AsyncAws\DynamoDb\ValueObject\Capacity> */
        $fn['map-SecondaryIndexesCapacityMap'] = static function (array $json): array {
            $items = [];
            foreach ($json as $name => $value) {
                $items[(string) $name] = Capacity::create($value);
            }

            return $items;
        };
        $this->UnprocessedItems = empty($data['UnprocessedItems']) ? [] : (function (array $json) use (&$fn): array {
            $items = [];
            foreach ($json as $name => $value) {
                $items[(string) $name] = $fn['list-WriteRequests']($value);
            }

            return $items;
        })($data['UnprocessedItems']);
        $this->ItemCollectionMetrics = empty($data['ItemCollectionMetrics']) ? [] : (function (array $json) use (&$fn): array {
            $items = [];
            foreach ($json as $name => $value) {
                $items[(string) $name] = $fn['list-ItemCollectionMetricsMultiple']($value);
            }

            return $items;
        })($data['ItemCollectionMetrics']);
        $this->ConsumedCapacity = empty($data['ConsumedCapacity']) ? [] : $fn['list-ConsumedCapacityMultiple']($data['ConsumedCapacity']);
    }
}
