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

        $this->UnprocessedItems = empty($data['UnprocessedItems']) ? [] : $this->populateResultBatchWriteItemRequestMap($data['UnprocessedItems']);
        $this->ItemCollectionMetrics = empty($data['ItemCollectionMetrics']) ? [] : $this->populateResultItemCollectionMetricsPerTable($data['ItemCollectionMetrics']);
        $this->ConsumedCapacity = empty($data['ConsumedCapacity']) ? [] : $this->populateResultConsumedCapacityMultiple($data['ConsumedCapacity']);
    }

    /**
     * @return array<string, array>
     */
    private function populateResultBatchWriteItemRequestMap(array $json): array
    {
        $items = [];
        foreach ($json as $name => $value) {
            $items[(string) $name] = $this->populateResultWriteRequests($value);
        }

        return $items;
    }

    /**
     * @return ConsumedCapacity[]
     */
    private function populateResultConsumedCapacityMultiple(array $json): array
    {
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
                'LocalSecondaryIndexes' => empty($item['LocalSecondaryIndexes']) ? [] : $this->populateResultSecondaryIndexesCapacityMap($item['LocalSecondaryIndexes']),
                'GlobalSecondaryIndexes' => empty($item['GlobalSecondaryIndexes']) ? [] : $this->populateResultSecondaryIndexesCapacityMap($item['GlobalSecondaryIndexes']),
            ]);
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

    /**
     * @return ItemCollectionMetrics[]
     */
    private function populateResultItemCollectionMetricsMultiple(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new ItemCollectionMetrics([
                'ItemCollectionKey' => empty($item['ItemCollectionKey']) ? [] : $this->populateResultItemCollectionKeyAttributeMap($item['ItemCollectionKey']),
                'SizeEstimateRangeGB' => empty($item['SizeEstimateRangeGB']) ? [] : $this->populateResultItemCollectionSizeEstimateRange($item['SizeEstimateRangeGB']),
            ]);
        }

        return $items;
    }

    /**
     * @return array<string, array>
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

    /**
     * @return WriteRequest[]
     */
    private function populateResultWriteRequests(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new WriteRequest([
                'PutRequest' => empty($item['PutRequest']) ? null : new PutRequest([
                    'Item' => $this->populateResultPutItemInputAttributeMap($item['PutRequest']['Item']),
                ]),
                'DeleteRequest' => empty($item['DeleteRequest']) ? null : new DeleteRequest([
                    'Key' => $this->populateResultKey($item['DeleteRequest']['Key']),
                ]),
            ]);
        }

        return $items;
    }
}
