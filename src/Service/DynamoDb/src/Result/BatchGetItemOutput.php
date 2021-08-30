<?php

namespace AsyncAws\DynamoDb\Result;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\DynamoDb\DynamoDbClient;
use AsyncAws\DynamoDb\Input\BatchGetItemInput;
use AsyncAws\DynamoDb\ValueObject\AttributeValue;
use AsyncAws\DynamoDb\ValueObject\Capacity;
use AsyncAws\DynamoDb\ValueObject\ConsumedCapacity;
use AsyncAws\DynamoDb\ValueObject\KeysAndAttributes;

/**
 * Represents the output of a `BatchGetItem` operation.
 *
 * @implements \IteratorAggregate<ConsumedCapacity>
 */
class BatchGetItemOutput extends Result implements \IteratorAggregate
{
    /**
     * A map of table name to a list of items. Each object in `Responses` consists of a table name, along with a map of
     * attribute data consisting of the data type and attribute value.
     */
    private $responses;

    /**
     * A map of tables and their respective keys that were not processed with the current response. The `UnprocessedKeys`
     * value is in the same form as `RequestItems`, so the value can be provided directly to a subsequent `BatchGetItem`
     * operation. For more information, see `RequestItems` in the Request Parameters section.
     */
    private $unprocessedKeys;

    /**
     * The read capacity units consumed by the entire `BatchGetItem` operation.
     */
    private $consumedCapacity;

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<ConsumedCapacity>
     */
    public function getConsumedCapacity(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->consumedCapacity;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof DynamoDbClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof BatchGetItemInput) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if ($page->unprocessedKeys) {
                $input->setRequestItems($page->unprocessedKeys);

                $this->registerPrefetch($nextPage = $client->batchGetItem($input));
            } else {
                $nextPage = null;
            }

            yield from $page->consumedCapacity;

            if (null === $nextPage) {
                break;
            }

            $this->unregisterPrefetch($nextPage);
            $page = $nextPage;
        }
    }

    /**
     * Iterates over ConsumedCapacity.
     *
     * @return \Traversable<ConsumedCapacity>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getConsumedCapacity();
    }

    /**
     * @return array<string, array<string, AttributeValue>[]>
     */
    public function getResponses(): array
    {
        $this->initialize();

        return $this->responses;
    }

    /**
     * @return array<string, KeysAndAttributes>
     */
    public function getUnprocessedKeys(): array
    {
        $this->initialize();

        return $this->unprocessedKeys;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->responses = empty($data['Responses']) ? [] : $this->populateResultBatchGetResponseMap($data['Responses']);
        $this->unprocessedKeys = empty($data['UnprocessedKeys']) ? [] : $this->populateResultBatchGetRequestMap($data['UnprocessedKeys']);
        $this->consumedCapacity = empty($data['ConsumedCapacity']) ? [] : $this->populateResultConsumedCapacityMultiple($data['ConsumedCapacity']);
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
     * @return array<string, KeysAndAttributes>
     */
    private function populateResultBatchGetRequestMap(array $json): array
    {
        $items = [];
        foreach ($json as $name => $value) {
            $items[(string) $name] = KeysAndAttributes::create($value);
        }

        return $items;
    }

    /**
     * @return array<string, array<string, AttributeValue>[]>
     */
    private function populateResultBatchGetResponseMap(array $json): array
    {
        $items = [];
        foreach ($json as $name => $value) {
            $items[(string) $name] = $this->populateResultItemList($value);
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
