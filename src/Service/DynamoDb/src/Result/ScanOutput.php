<?php

namespace AsyncAws\DynamoDb\Result;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\DynamoDb\DynamoDbClient;
use AsyncAws\DynamoDb\Input\ScanInput;
use AsyncAws\DynamoDb\ValueObject\AttributeValue;
use AsyncAws\DynamoDb\ValueObject\Capacity;
use AsyncAws\DynamoDb\ValueObject\ConsumedCapacity;

/**
 * Represents the output of a `Scan` operation.
 *
 * @implements \IteratorAggregate<array<string, AttributeValue>>
 */
class ScanOutput extends Result implements \IteratorAggregate
{
    /**
     * An array of item attributes that match the scan criteria. Each element in this array consists of an attribute name
     * and the value for that attribute.
     *
     * @var array<string, AttributeValue>[]
     */
    private $items;

    /**
     * The number of items in the response.
     *
     * If you set `ScanFilter` in the request, then `Count` is the number of items returned after the filter was applied,
     * and `ScannedCount` is the number of matching items before the filter was applied.
     *
     * If you did not use a filter in the request, then `Count` is the same as `ScannedCount`.
     *
     * @var int|null
     */
    private $count;

    /**
     * The number of items evaluated, before any `ScanFilter` is applied. A high `ScannedCount` value with few, or no,
     * `Count` results indicates an inefficient `Scan` operation. For more information, see Count and ScannedCount [^1] in
     * the *Amazon DynamoDB Developer Guide*.
     *
     * If you did not use a filter in the request, then `ScannedCount` is the same as `Count`.
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/QueryAndScan.html#Count
     *
     * @var int|null
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
     *
     * @var array<string, AttributeValue>
     */
    private $lastEvaluatedKey;

    /**
     * The capacity units consumed by the `Scan` operation. The data returned includes the total provisioned throughput
     * consumed, along with statistics for the table and any indexes involved in the operation. `ConsumedCapacity` is only
     * returned if the `ReturnConsumedCapacity` parameter was specified. For more information, see Capacity unit consumption
     * for read operations [^1] in the *Amazon DynamoDB Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/read-write-operations.html#read-operation-consumption
     *
     * @var ConsumedCapacity|null
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
        if (!$this->input instanceof ScanInput) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if (null !== $page->lastEvaluatedKey) {
                $input->setExclusiveStartKey($page->lastEvaluatedKey);

                $this->registerPrefetch($nextPage = $client->scan($input));
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
            $items[(string) $name] = $this->populateResultAttributeValue($value);
        }

        return $items;
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
}
