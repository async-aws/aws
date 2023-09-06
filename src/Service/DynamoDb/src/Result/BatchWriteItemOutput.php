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
     *
     * Each `UnprocessedItems` entry consists of a table name and, for that table, a list of operations to perform
     * (`DeleteRequest` or `PutRequest`).
     *
     * - `DeleteRequest` - Perform a `DeleteItem` operation on the specified item. The item to be deleted is identified by a
     *   `Key` subelement:
     *
     *   - `Key` - A map of primary key attribute values that uniquely identify the item. Each entry in this map consists of
     *     an attribute name and an attribute value.
     *
     * - `PutRequest` - Perform a `PutItem` operation on the specified item. The item to be put is identified by an `Item`
     *   subelement:
     *
     *   - `Item` - A map of attributes and their values. Each entry in this map consists of an attribute name and an
     *     attribute value. Attribute values must not be null; string and binary type attributes must have lengths greater
     *     than zero; and set type attributes must not be empty. Requests that contain empty values will be rejected with a
     *     `ValidationException` exception.
     *
     *     If you specify any attributes that are part of an index key, then the data types for those attributes must match
     *     those of the schema in the table's attribute definition.
     *
     *
     * If there are no unprocessed items remaining, the response contains an empty `UnprocessedItems` map.
     *
     * @var array<string, WriteRequest[]>
     */
    private $unprocessedItems;

    /**
     * A list of tables that were processed by `BatchWriteItem` and, for each table, information about any item collections
     * that were affected by individual `DeleteItem` or `PutItem` operations.
     *
     * Each entry consists of the following subelements:
     *
     * - `ItemCollectionKey` - The partition key value of the item collection. This is the same as the partition key value
     *   of the item.
     * - `SizeEstimateRangeGB` - An estimate of item collection size, expressed in GB. This is a two-element array
     *   containing a lower bound and an upper bound for the estimate. The estimate includes the size of all the items in
     *   the table, plus the size of all attributes projected into all of the local secondary indexes on the table. Use this
     *   estimate to measure whether a local secondary index is approaching its size limit.
     *
     *   The estimate is subject to change over time; therefore, do not rely on the precision or accuracy of the estimate.
     *
     * @var array<string, ItemCollectionMetrics[]>
     */
    private $itemCollectionMetrics;

    /**
     * The capacity units consumed by the entire `BatchWriteItem` operation.
     *
     * Each element consists of:
     *
     * - `TableName` - The table that consumed the provisioned throughput.
     * - `CapacityUnits` - The total number of capacity units consumed.
     *
     * @var ConsumedCapacity[]
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

    private function populateResultAttributeValue(array $json): AttributeValue
    {
        return new AttributeValue([
            'S' => isset($json['S']) ? (string) $json['S'] : null,
            'N' => isset($json['N']) ? (string) $json['N'] : null,
            'B' => isset($json['B']) ? base64_decode((string) $json['B']) : null,
            'SS' => !isset($json['SS']) ? null : $this->populateResultStringSetAttributeValue($json['SS']),
            'NS' => !isset($json['NS']) ? null : $this->populateResultNumberSetAttributeValue($json['NS']),
            'BS' => !isset($json['BS']) ? null : $this->populateResultBinarySetAttributeValue($json['BS']),
            'M' => !isset($json['M']) ? null : $this->populateResultMapAttributeValue($json['M']),
            'L' => !isset($json['L']) ? null : $this->populateResultListAttributeValue($json['L']),
            'NULL' => isset($json['NULL']) ? filter_var($json['NULL'], \FILTER_VALIDATE_BOOLEAN) : null,
            'BOOL' => isset($json['BOOL']) ? filter_var($json['BOOL'], \FILTER_VALIDATE_BOOLEAN) : null,
        ]);
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

    /**
     * @return string[]
     */
    private function populateResultBinarySetAttributeValue(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? base64_decode((string) $item) : null;
            if (null !== $a) {
                $items[] = $a;
            }
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
            $items[(string) $name] = $this->populateResultAttributeValue($value);
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
            $items[(string) $name] = $this->populateResultAttributeValue($value);
        }

        return $items;
    }

    /**
     * @return AttributeValue[]
     */
    private function populateResultListAttributeValue(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultAttributeValue($item);
        }

        return $items;
    }

    /**
     * @return array<string, AttributeValue>
     */
    private function populateResultMapAttributeValue(array $json): array
    {
        $items = [];
        foreach ($json as $name => $value) {
            $items[(string) $name] = $this->populateResultAttributeValue($value);
        }

        return $items;
    }

    /**
     * @return string[]
     */
    private function populateResultNumberSetAttributeValue(array $json): array
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
     * @return array<string, AttributeValue>
     */
    private function populateResultPutItemInputAttributeMap(array $json): array
    {
        $items = [];
        foreach ($json as $name => $value) {
            $items[(string) $name] = $this->populateResultAttributeValue($value);
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
            $items[(string) $name] = $this->populateResultCapacity($value);
        }

        return $items;
    }

    /**
     * @return string[]
     */
    private function populateResultStringSetAttributeValue(array $json): array
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
