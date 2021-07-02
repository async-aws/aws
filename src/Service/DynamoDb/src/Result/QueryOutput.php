<?php

namespace AsyncAws\DynamoDb\Result;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\DynamoDb\DynamoDbClient;
use AsyncAws\DynamoDb\Input\QueryInput;
use AsyncAws\DynamoDb\ValueObject\AttributeValue;
use AsyncAws\DynamoDb\ValueObject\Capacity;
use AsyncAws\DynamoDb\ValueObject\ConsumedCapacity;

/**
 * Represents the output of a `Query` operation.
 *
 * @implements \IteratorAggregate<array<string, AttributeValue>>
 */
class QueryOutput extends Result implements \IteratorAggregate
{
    /**
     * An array of item attributes that match the query criteria. Each element in this array consists of an attribute name
     * and the value for that attribute.
     */
    private $items = [];

    /**
     * The number of items in the response.
     */
    private $count;

    /**
     * The number of items evaluated, before any `QueryFilter` is applied. A high `ScannedCount` value with few, or no,
     * `Count` results indicates an inefficient `Query` operation. For more information, see Count and ScannedCount in the
     * *Amazon DynamoDB Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/QueryAndScan.html#Count
     */
    private $scannedCount;

    /**
     * The primary key of the item where the operation stopped, inclusive of the previous result set. Use this value to
     * start a new operation, excluding this value in the new request.
     */
    private $lastEvaluatedKey = [];

    /**
     * The capacity units consumed by the `Query` operation. The data returned includes the total provisioned throughput
     * consumed, along with statistics for the table and any indexes involved in the operation. `ConsumedCapacity` is only
     * returned if the `ReturnConsumedCapacity` parameter was specified. For more information, see Provisioned Throughput in
     * the *Amazon DynamoDB Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/ProvisionedThroughputIntro.html
     */
    private $consumedCapacity;

    public function getConsumedCapacity(): ?ConsumedCapacity
    {
        $this->initialize();

        return $this->consumedCapacity;
    }

    public function getCount(): ?int
    {
        $this->initialize();

        return $this->count;
    }

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<array<string, AttributeValue>>
     */
    public function getItems(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->items;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof DynamoDbClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof QueryInput) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if ($page->lastEvaluatedKey) {
                $input->setExclusiveStartKey($page->lastEvaluatedKey);

                $this->registerPrefetch($nextPage = $client->query($input));
            } else {
                $nextPage = null;
            }

            yield from $page->items;

            if (null === $nextPage) {
                break;
            }

            $this->unregisterPrefetch($nextPage);
            $page = $nextPage;
        }
    }

    /**
     * Iterates over Items.
     *
     * @return \Traversable<array<string, AttributeValue>>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getItems();
    }

    /**
     * @return array<string, AttributeValue>
     */
    public function getLastEvaluatedKey(): array
    {
        $this->initialize();

        return $this->lastEvaluatedKey;
    }

    public function getScannedCount(): ?int
    {
        $this->initialize();

        return $this->scannedCount;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->items = empty($data['Items']) ? [] : $this->populateResultItemList($data['Items']);
        $this->count = isset($data['Count']) ? (int) $data['Count'] : null;
        $this->scannedCount = isset($data['ScannedCount']) ? (int) $data['ScannedCount'] : null;
        $this->lastEvaluatedKey = empty($data['LastEvaluatedKey']) ? [] : $this->populateResultKey($data['LastEvaluatedKey']);
        $this->consumedCapacity = empty($data['ConsumedCapacity']) ? null : new ConsumedCapacity([
            'TableName' => isset($data['ConsumedCapacity']['TableName']) ? (string) $data['ConsumedCapacity']['TableName'] : null,
            'CapacityUnits' => isset($data['ConsumedCapacity']['CapacityUnits']) ? (float) $data['ConsumedCapacity']['CapacityUnits'] : null,
            'ReadCapacityUnits' => isset($data['ConsumedCapacity']['ReadCapacityUnits']) ? (float) $data['ConsumedCapacity']['ReadCapacityUnits'] : null,
            'WriteCapacityUnits' => isset($data['ConsumedCapacity']['WriteCapacityUnits']) ? (float) $data['ConsumedCapacity']['WriteCapacityUnits'] : null,
            'Table' => empty($data['ConsumedCapacity']['Table']) ? null : new Capacity([
                'ReadCapacityUnits' => isset($data['ConsumedCapacity']['Table']['ReadCapacityUnits']) ? (float) $data['ConsumedCapacity']['Table']['ReadCapacityUnits'] : null,
                'WriteCapacityUnits' => isset($data['ConsumedCapacity']['Table']['WriteCapacityUnits']) ? (float) $data['ConsumedCapacity']['Table']['WriteCapacityUnits'] : null,
                'CapacityUnits' => isset($data['ConsumedCapacity']['Table']['CapacityUnits']) ? (float) $data['ConsumedCapacity']['Table']['CapacityUnits'] : null,
            ]),
            'LocalSecondaryIndexes' => empty($data['ConsumedCapacity']['LocalSecondaryIndexes']) ? [] : $this->populateResultSecondaryIndexesCapacityMap($data['ConsumedCapacity']['LocalSecondaryIndexes']),
            'GlobalSecondaryIndexes' => empty($data['ConsumedCapacity']['GlobalSecondaryIndexes']) ? [] : $this->populateResultSecondaryIndexesCapacityMap($data['ConsumedCapacity']['GlobalSecondaryIndexes']),
        ]);
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

    /**
     * @return array<string, AttributeValue>[]
     */
    private function populateResultItemList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = empty($item) ? [] : $this->populateResultAttributeMap($item);
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
