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

/**
 * Represents the output of a `BatchWriteItem` operation.
 */
class BatchWriteItemOutput extends Result
{
    /**
     * A map of tables and requests against those tables that were not processed. The `UnprocessedItems` value is in the
     * same form as `RequestItems`, so you can provide this value directly to a subsequent `BatchWriteItem` operation. For
     * more information, see `RequestItems` in the Request Parameters section.
     */
    private $unprocessedItems;

    /**
     * A list of tables that were processed by `BatchWriteItem` and, for each table, information about any item collections
     * that were affected by individual `DeleteItem` or `PutItem` operations.
     */
    private $itemCollectionMetrics;

    /**
     * The capacity units consumed by the entire `BatchWriteItem` operation.
     */
    private $consumedCapacity;

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

    /**
     * @return array<string, WriteRequest[]>
     */
    public function getUnprocessedItems(): array
    {
        $this->initialize();

        return $this->unprocessedItems;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->unprocessedItems = empty($data['UnprocessedItems']) ? [] : $this->populateResultBatchWriteItemRequestMap($data['UnprocessedItems']);
        $this->itemCollectionMetrics = empty($data['ItemCollectionMetrics']) ? [] : $this->populateResultItemCollectionMetricsPerTable($data['ItemCollectionMetrics']);
        $this->consumedCapacity = empty($data['ConsumedCapacity']) ? [] : $this->populateResultConsumedCapacityMultiple($data['ConsumedCapacity']);
    }

    /**
     * @return array<string, WriteRequest[]>
     */
    private function populateResultBatchWriteItemRequestMap(array $json): array
    {
        $items = [];
        foreach ($json as $name => $value) {
            $items[(string) $name] = $this->populateResultWriteRequests($value);
        }

        return $items;
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

    private function populateResultDeleteRequest(array $json): DeleteRequest
    {
        return new DeleteRequest([
            'Key' => $this->populateResultKey($json['Key']),
        ]);
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
     * @return array<string, AttributeValue>
     */
    private function populateResultKey(array $json): array
    {
        $items = [];
        foreach ($json as $name => $value) {
            $items[(string) $name] = AttributeValue::create($value);
        }

        return $items;
    }

    /**
     * @return array<string, AttributeValue>
     */
    private function populateResultPutItemInputAttributeMap(array $json): array
    {
        $items = [];
        foreach ($json as $name => $value) {
            $items[(string) $name] = AttributeValue::create($value);
        }

        return $items;
    }

    private function populateResultPutRequest(array $json): PutRequest
    {
        return new PutRequest([
            'Item' => $this->populateResultPutItemInputAttributeMap($json['Item']),
        ]);
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

    private function populateResultWriteRequest(array $json): WriteRequest
    {
        return new WriteRequest([
            'PutRequest' => empty($json['PutRequest']) ? null : $this->populateResultPutRequest($json['PutRequest']),
            'DeleteRequest' => empty($json['DeleteRequest']) ? null : $this->populateResultDeleteRequest($json['DeleteRequest']),
        ]);
    }

    /**
     * @return WriteRequest[]
     */
    private function populateResultWriteRequests(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultWriteRequest($item);
        }

        return $items;
    }
}
