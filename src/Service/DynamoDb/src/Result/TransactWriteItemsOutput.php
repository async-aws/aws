<?php

namespace AsyncAws\DynamoDb\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\DynamoDb\ValueObject\AttributeValue;
use AsyncAws\DynamoDb\ValueObject\Capacity;
use AsyncAws\DynamoDb\ValueObject\ConsumedCapacity;
use AsyncAws\DynamoDb\ValueObject\ItemCollectionMetrics;

class TransactWriteItemsOutput extends Result
{
    /**
     * The capacity units consumed by the entire `TransactWriteItems` operation. The values of the list are ordered
     * according to the ordering of the `TransactItems` request parameter.
     *
     * @var ConsumedCapacity[]
     */
    private $consumedCapacity;

    /**
     * A list of tables that were processed by `TransactWriteItems` and, for each table, information about any item
     * collections that were affected by individual `UpdateItem`, `PutItem`, or `DeleteItem` operations.
     *
     * @var array<string, ItemCollectionMetrics[]>
     */
    private $itemCollectionMetrics;

    /**
     * @return ConsumedCapacity[]
     */
    public function getConsumedCapacity(): array
    {
        $this->initialize();

        return $this->consumedCapacity;
    }

    /**
     * @return array<string, ItemCollectionMetrics[]>
     */
    public function getItemCollectionMetrics(): array
    {
        $this->initialize();

        return $this->itemCollectionMetrics;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->consumedCapacity = empty($data['ConsumedCapacity']) ? [] : $this->populateResultConsumedCapacityMultiple($data['ConsumedCapacity']);
        $this->itemCollectionMetrics = empty($data['ItemCollectionMetrics']) ? [] : $this->populateResultItemCollectionMetricsPerTable($data['ItemCollectionMetrics']);
    }

    private function populateResultCapacity(array $json): Capacity
    {
        return new Capacity([
            'ReadCapacityUnits' => isset($json['ReadCapacityUnits']) ? (float) $json['ReadCapacityUnits'] : null,
            'WriteCapacityUnits' => isset($json['WriteCapacityUnits']) ? (float) $json['WriteCapacityUnits'] : null,
            'CapacityUnits' => isset($json['CapacityUnits']) ? (float) $json['CapacityUnits'] : null,
        ]);
    }

    private function populateResultConsumedCapacity(array $json): ConsumedCapacity
    {
        return new ConsumedCapacity([
            'TableName' => isset($json['TableName']) ? (string) $json['TableName'] : null,
            'CapacityUnits' => isset($json['CapacityUnits']) ? (float) $json['CapacityUnits'] : null,
            'ReadCapacityUnits' => isset($json['ReadCapacityUnits']) ? (float) $json['ReadCapacityUnits'] : null,
            'WriteCapacityUnits' => isset($json['WriteCapacityUnits']) ? (float) $json['WriteCapacityUnits'] : null,
            'Table' => empty($json['Table']) ? null : $this->populateResultCapacity($json['Table']),
            'LocalSecondaryIndexes' => !isset($json['LocalSecondaryIndexes']) ? null : $this->populateResultSecondaryIndexesCapacityMap($json['LocalSecondaryIndexes']),
            'GlobalSecondaryIndexes' => !isset($json['GlobalSecondaryIndexes']) ? null : $this->populateResultSecondaryIndexesCapacityMap($json['GlobalSecondaryIndexes']),
        ]);
    }

    /**
     * @return ConsumedCapacity[]
     */
    private function populateResultConsumedCapacityMultiple(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultConsumedCapacity($item);
        }

        return $items;
    }

    /**
     * @return array<string, AttributeValue>
     */
    private function populateResultItemCollectionKeyAttributeMap(array $json): array
    {
        $items = [];
        foreach ($json as $name => $value) {
            $items[(string) $name] = AttributeValue::create($value);
        }

        return $items;
    }

    private function populateResultItemCollectionMetrics(array $json): ItemCollectionMetrics
    {
        return new ItemCollectionMetrics([
            'ItemCollectionKey' => !isset($json['ItemCollectionKey']) ? null : $this->populateResultItemCollectionKeyAttributeMap($json['ItemCollectionKey']),
            'SizeEstimateRangeGB' => !isset($json['SizeEstimateRangeGB']) ? null : $this->populateResultItemCollectionSizeEstimateRange($json['SizeEstimateRangeGB']),
        ]);
    }

    /**
     * @return ItemCollectionMetrics[]
     */
    private function populateResultItemCollectionMetricsMultiple(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultItemCollectionMetrics($item);
        }

        return $items;
    }

    /**
     * @return array<string, ItemCollectionMetrics[]>
     */
    private function populateResultItemCollectionMetricsPerTable(array $json): array
    {
        $items = [];
        foreach ($json as $name => $value) {
            $items[(string) $name] = $this->populateResultItemCollectionMetricsMultiple($value);
        }

        return $items;
    }

    /**
     * @return float[]
     */
    private function populateResultItemCollectionSizeEstimateRange(array $json): array
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
     * @return array<string, Capacity>
     */
    private function populateResultSecondaryIndexesCapacityMap(array $json): array
    {
        $items = [];
        foreach ($json as $name => $value) {
            $items[(string) $name] = Capacity::create($value);
        }

        return $items;
    }
}
