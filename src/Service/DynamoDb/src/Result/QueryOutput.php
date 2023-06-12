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
    private $items;

    /**
     * The number of items in the response.
     *
     * If you used a `QueryFilter` in the request, then `Count` is the number of items returned after the filter was
     * applied, and `ScannedCount` is the number of matching items before the filter was applied.
     *
     * If you did not use a filter in the request, then `Count` and `ScannedCount` are the same.
     */
    private $count;

    /**
     * The number of items evaluated, before any `QueryFilter` is applied. A high `ScannedCount` value with few, or no,
     * `Count` results indicates an inefficient `Query` operation. For more information, see Count and ScannedCount [^1] in
     * the *Amazon DynamoDB Developer Guide*.
     *
     * If you did not use a filter in the request, then `ScannedCount` is the same as `Count`.
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/QueryAndScan.html#Count
     */
    private $scannedCount;

    /**
     * The primary key of the item where the operation stopped, inclusive of the previous result set. Use this value to
     * start a new operation, excluding this value in the new request.
     *
     * If `LastEvaluatedKey` is empty, then the "last page" of results has been processed and there is no more data to be
     * retrieved.
     *
     * If `LastEvaluatedKey` is not empty, it does not necessarily mean that there is more data in the result set. The only
     * way to know when you have reached the end of the result set is when `LastEvaluatedKey` is empty.
     */
    private $lastEvaluatedKey;

    /**
     * The capacity units consumed by the `Query` operation. The data returned includes the total provisioned throughput
     * consumed, along with statistics for the table and any indexes involved in the operation. `ConsumedCapacity` is only
     * returned if the `ReturnConsumedCapacity` parameter was specified. For more information, see Provisioned Throughput
     * [^1] in the *Amazon DynamoDB Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/ProvisionedThroughputIntro.html
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
        $this->consumedCapacity = empty($data['ConsumedCapacity']) ? null : $this->populateResultConsumedCapacity($data['ConsumedCapacity']);
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
     * @return array<string, AttributeValue>[]
     */
    private function populateResultItemList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultAttributeMap($item);
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
