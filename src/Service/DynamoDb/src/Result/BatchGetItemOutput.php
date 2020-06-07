<?php

namespace AsyncAws\DynamoDb\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\DynamoDb\DynamoDbClient;
use AsyncAws\DynamoDb\Input\BatchGetItemInput;
use AsyncAws\DynamoDb\ValueObject\AttributeValue;
use AsyncAws\DynamoDb\ValueObject\Capacity;
use AsyncAws\DynamoDb\ValueObject\ConsumedCapacity;
use AsyncAws\DynamoDb\ValueObject\KeysAndAttributes;

/**
 * @implements \IteratorAggregate<ConsumedCapacity>
 */
class BatchGetItemOutput extends Result implements \IteratorAggregate
{
    /**
     * A map of table name to a list of items. Each object in `Responses` consists of a table name, along with a map of
     * attribute data consisting of the data type and attribute value.
     */
    private $Responses = [];

    /**
     * A map of tables and their respective keys that were not processed with the current response. The `UnprocessedKeys`
     * value is in the same form as `RequestItems`, so the value can be provided directly to a subsequent `BatchGetItem`
     * operation. For more information, see `RequestItems` in the Request Parameters section.
     */
    private $UnprocessedKeys = [];

    /**
     * The read capacity units consumed by the entire `BatchGetItem` operation.
     */
    private $ConsumedCapacity = [];

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<ConsumedCapacity>
     */
    public function getConsumedCapacity(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->ConsumedCapacity;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof DynamoDbClient) {
            throw new \InvalidArgumentException('missing client injected in paginated result');
        }
        if (!$this->input instanceof BatchGetItemInput) {
            throw new \InvalidArgumentException('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            if ($page->getUnprocessedKeys()) {
                $input->setRequestItems($page->getUnprocessedKeys());

                $this->registerPrefetch($nextPage = $client->BatchGetItem($input));
            } else {
                $nextPage = null;
            }

            yield from $page->getConsumedCapacity(true);

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
        $client = $this->awsClient;
        if (!$client instanceof DynamoDbClient) {
            throw new \InvalidArgumentException('missing client injected in paginated result');
        }
        if (!$this->input instanceof BatchGetItemInput) {
            throw new \InvalidArgumentException('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            if ($page->getUnprocessedKeys()) {
                $input->setRequestItems($page->getUnprocessedKeys());

                $this->registerPrefetch($nextPage = $client->BatchGetItem($input));
            } else {
                $nextPage = null;
            }

            yield from $page->getConsumedCapacity(true);

            if (null === $nextPage) {
                break;
            }

            $this->unregisterPrefetch($nextPage);
            $page = $nextPage;
        }
    }

    /**
     * @return array<string, array>
     */
    public function getResponses(): array
    {
        $this->initialize();

        return $this->Responses;
    }

    /**
     * @return array<string, KeysAndAttributes>
     */
    public function getUnprocessedKeys(): array
    {
        $this->initialize();

        return $this->UnprocessedKeys;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();
        $fn = [];
        $fn['list-ItemList'] = static function (array $json) use (&$fn): array {
            $items = [];
            foreach ($json as $item) {
                $a = empty($item) ? [] : $fn['map-AttributeMap']($item);
                if (null !== $a) {
                    $items[] = $a;
                }
            }

            return $items;
        };

        /** @return array<string, \AsyncAws\DynamoDb\ValueObject\AttributeValue> */
        $fn['map-AttributeMap'] = static function (array $json): array {
            $items = [];
            foreach ($json as $name => $value) {
                $items[(string) $name] = AttributeValue::create($value);
            }

            return $items;
        };

        /** @return array<string, \AsyncAws\DynamoDb\ValueObject\KeysAndAttributes> */
        $fn['map-BatchGetRequestMap'] = static function (array $json): array {
            $items = [];
            foreach ($json as $name => $value) {
                $items[(string) $name] = KeysAndAttributes::create($value);
            }

            return $items;
        };
        $fn['list-ConsumedCapacityMultiple'] = static function (array $json) use (&$fn): array {
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
                    'LocalSecondaryIndexes' => empty($item['LocalSecondaryIndexes']) ? [] : $fn['map-SecondaryIndexesCapacityMap']($item['LocalSecondaryIndexes']),
                    'GlobalSecondaryIndexes' => empty($item['GlobalSecondaryIndexes']) ? [] : $fn['map-SecondaryIndexesCapacityMap']($item['GlobalSecondaryIndexes']),
                ]);
            }

            return $items;
        };

        /** @return array<string, \AsyncAws\DynamoDb\ValueObject\Capacity> */
        $fn['map-SecondaryIndexesCapacityMap'] = static function (array $json): array {
            $items = [];
            foreach ($json as $name => $value) {
                $items[(string) $name] = Capacity::create($value);
            }

            return $items;
        };
        $this->Responses = empty($data['Responses']) ? [] : (function (array $json) use (&$fn): array {
            $items = [];
            foreach ($json as $name => $value) {
                $items[(string) $name] = $fn['list-ItemList']($value);
            }

            return $items;
        })($data['Responses']);
        $this->UnprocessedKeys = empty($data['UnprocessedKeys']) ? [] : $fn['map-BatchGetRequestMap']($data['UnprocessedKeys']);
        $this->ConsumedCapacity = empty($data['ConsumedCapacity']) ? [] : $fn['list-ConsumedCapacityMultiple']($data['ConsumedCapacity']);
    }
}
