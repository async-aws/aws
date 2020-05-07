<?php

namespace AsyncAws\DynamoDb\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\DynamoDb\ValueObject\AttributeValue;
use AsyncAws\DynamoDb\ValueObject\Capacity;
use AsyncAws\DynamoDb\ValueObject\ConsumedCapacity;
use AsyncAws\DynamoDb\ValueObject\ItemCollectionMetrics;

class PutItemOutput extends Result
{
    /**
     * The attribute values as they appeared before the `PutItem` operation, but only if `ReturnValues` is specified as
     * `ALL_OLD` in the request. Each element consists of an attribute name and an attribute value.
     */
    private $Attributes = [];

    /**
     * The capacity units consumed by the `PutItem` operation. The data returned includes the total provisioned throughput
     * consumed, along with statistics for the table and any indexes involved in the operation. `ConsumedCapacity` is only
     * returned if the `ReturnConsumedCapacity` parameter was specified. For more information, see Read/Write Capacity Mode
     * in the *Amazon DynamoDB Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/ProvisionedThroughputIntro.html
     */
    private $ConsumedCapacity;

    /**
     * Information about item collections, if any, that were affected by the `PutItem` operation. `ItemCollectionMetrics` is
     * only returned if the `ReturnItemCollectionMetrics` parameter was specified. If the table does not have any local
     * secondary indexes, this information is not returned in the response.
     */
    private $ItemCollectionMetrics;

    /**
     * @return array<string, AttributeValue>
     */
    public function getAttributes(): array
    {
        $this->initialize();

        return $this->Attributes;
    }

    public function getConsumedCapacity(): ?ConsumedCapacity
    {
        $this->initialize();

        return $this->ConsumedCapacity;
    }

    public function getItemCollectionMetrics(): ?ItemCollectionMetrics
    {
        $this->initialize();

        return $this->ItemCollectionMetrics;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->Attributes = empty($data['Attributes']) ? [] : (function (array $json): array {
            $items = [];
            foreach ($json as $name => $value) {
                $items[(string) $name] = AttributeValue::create($value);
            }

            return $items;
        })($data['Attributes']);
        $this->ConsumedCapacity = empty($data['ConsumedCapacity']) ? null : new ConsumedCapacity([
            'TableName' => isset($data['ConsumedCapacity']['TableName']) ? (string) $data['ConsumedCapacity']['TableName'] : null,
            'CapacityUnits' => isset($data['ConsumedCapacity']['CapacityUnits']) ? (float) $data['ConsumedCapacity']['CapacityUnits'] : null,
            'ReadCapacityUnits' => isset($data['ConsumedCapacity']['ReadCapacityUnits']) ? (float) $data['ConsumedCapacity']['ReadCapacityUnits'] : null,
            'WriteCapacityUnits' => isset($data['ConsumedCapacity']['WriteCapacityUnits']) ? (float) $data['ConsumedCapacity']['WriteCapacityUnits'] : null,
            'Table' => empty($data['ConsumedCapacity']['Table']) ? null : new Capacity([
                'ReadCapacityUnits' => isset($data['ConsumedCapacity']['Table']['ReadCapacityUnits']) ? (float) $data['ConsumedCapacity']['Table']['ReadCapacityUnits'] : null,
                'WriteCapacityUnits' => isset($data['ConsumedCapacity']['Table']['WriteCapacityUnits']) ? (float) $data['ConsumedCapacity']['Table']['WriteCapacityUnits'] : null,
                'CapacityUnits' => isset($data['ConsumedCapacity']['Table']['CapacityUnits']) ? (float) $data['ConsumedCapacity']['Table']['CapacityUnits'] : null,
            ]),
            'LocalSecondaryIndexes' => empty($data['ConsumedCapacity']['LocalSecondaryIndexes']) ? [] : (function (array $json): array {
                $items = [];
                foreach ($json as $name => $value) {
                    $items[(string) $name] = Capacity::create($value);
                }

                return $items;
            })($data['ConsumedCapacity']['LocalSecondaryIndexes']),
            'GlobalSecondaryIndexes' => empty($data['ConsumedCapacity']['GlobalSecondaryIndexes']) ? [] : (function (array $json): array {
                $items = [];
                foreach ($json as $name => $value) {
                    $items[(string) $name] = Capacity::create($value);
                }

                return $items;
            })($data['ConsumedCapacity']['GlobalSecondaryIndexes']),
        ]);
        $this->ItemCollectionMetrics = empty($data['ItemCollectionMetrics']) ? null : new ItemCollectionMetrics([
            'ItemCollectionKey' => empty($data['ItemCollectionMetrics']['ItemCollectionKey']) ? [] : (function (array $json): array {
                $items = [];
                foreach ($json as $name => $value) {
                    $items[(string) $name] = AttributeValue::create($value);
                }

                return $items;
            })($data['ItemCollectionMetrics']['ItemCollectionKey']),
            'SizeEstimateRangeGB' => empty($data['ItemCollectionMetrics']['SizeEstimateRangeGB']) ? [] : (function (array $json): array {
                $items = [];
                foreach ($json as $item) {
                    $a = isset($item) ? (float) $item : null;
                    if (null !== $a) {
                        $items[] = $a;
                    }
                }

                return $items;
            })($data['ItemCollectionMetrics']['SizeEstimateRangeGB']),
        ]);
    }
}
