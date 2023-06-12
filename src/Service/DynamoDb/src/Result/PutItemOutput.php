<?php

namespace AsyncAws\DynamoDb\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\DynamoDb\ValueObject\AttributeValue;
use AsyncAws\DynamoDb\ValueObject\Capacity;
use AsyncAws\DynamoDb\ValueObject\ConsumedCapacity;
use AsyncAws\DynamoDb\ValueObject\ItemCollectionMetrics;

/**
 * Represents the output of a `PutItem` operation.
 */
class PutItemOutput extends Result
{
    /**
     * The attribute values as they appeared before the `PutItem` operation, but only if `ReturnValues` is specified as
     * `ALL_OLD` in the request. Each element consists of an attribute name and an attribute value.
     */
    private $attributes;

    /**
     * The capacity units consumed by the `PutItem` operation. The data returned includes the total provisioned throughput
     * consumed, along with statistics for the table and any indexes involved in the operation. `ConsumedCapacity` is only
     * returned if the `ReturnConsumedCapacity` parameter was specified. For more information, see Provisioned Throughput
     * [^1] in the *Amazon DynamoDB Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/ProvisionedThroughputIntro.html
     */
    private $consumedCapacity;

    /**
     * Information about item collections, if any, that were affected by the `PutItem` operation. `ItemCollectionMetrics` is
     * only returned if the `ReturnItemCollectionMetrics` parameter was specified. If the table does not have any local
     * secondary indexes, this information is not returned in the response.
     *
     * Each `ItemCollectionMetrics` element consists of:
     *
     * - `ItemCollectionKey` - The partition key value of the item collection. This is the same as the partition key value
     *   of the item itself.
     * - `SizeEstimateRangeGB` - An estimate of item collection size, in gigabytes. This value is a two-element array
     *   containing a lower bound and an upper bound for the estimate. The estimate includes the size of all the items in
     *   the table, plus the size of all attributes projected into all of the local secondary indexes on that table. Use
     *   this estimate to measure whether a local secondary index is approaching its size limit.
     *
     *   The estimate is subject to change over time; therefore, do not rely on the precision or accuracy of the estimate.
     */
    private $itemCollectionMetrics;

    /**
     * @return array<string, AttributeValue>
     */
    public function getAttributes(): array
    {
        $this->initialize();

        return $this->attributes;
    }

    public function getConsumedCapacity(): ?ConsumedCapacity
    {
        $this->initialize();

        return $this->consumedCapacity;
    }

    public function getItemCollectionMetrics(): ?ItemCollectionMetrics
    {
        $this->initialize();

        return $this->itemCollectionMetrics;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->attributes = empty($data['Attributes']) ? [] : $this->populateResultAttributeMap($data['Attributes']);
        $this->consumedCapacity = empty($data['ConsumedCapacity']) ? null : $this->populateResultConsumedCapacity($data['ConsumedCapacity']);
        $this->itemCollectionMetrics = empty($data['ItemCollectionMetrics']) ? null : $this->populateResultItemCollectionMetrics($data['ItemCollectionMetrics']);
    }

    /**
     * @return array<string, AttributeValue>
     */
    private function populateResultAttributeMap(array $json): array
    {
        $items = [];
        foreach ($json as $name => $value) {
            $items[(string) $name] = AttributeValue::create($value);
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
