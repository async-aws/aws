<?php

namespace AsyncAws\DynamoDb\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\DynamoDb\ValueObject\AttributeValue;
use AsyncAws\DynamoDb\ValueObject\Capacity;
use AsyncAws\DynamoDb\ValueObject\ConsumedCapacity;

class ExecuteStatementOutput extends Result
{
    /**
     * If a read operation was used, this property will contain the result of the read operation; a map of attribute names
     * and their values. For the write operations this value will be empty.
     */
    private $items;

    /**
     * If the response of a read request exceeds the response payload limit DynamoDB will set this value in the response. If
     * set, you can use that this value in the subsequent request to get the remaining results.
     */
    private $nextToken;

    private $consumedCapacity;

    /**
     * The primary key of the item where the operation stopped, inclusive of the previous result set. Use this value to
     * start a new operation, excluding this value in the new request. If `LastEvaluatedKey` is empty, then the "last page"
     * of results has been processed and there is no more data to be retrieved. If `LastEvaluatedKey` is not empty, it does
     * not necessarily mean that there is more data in the result set. The only way to know when you have reached the end of
     * the result set is when `LastEvaluatedKey` is empty.
     */
    private $lastEvaluatedKey;

    public function getConsumedCapacity(): ?ConsumedCapacity
    {
        $this->initialize();

        return $this->consumedCapacity;
    }

    /**
     * @return array<string, AttributeValue>[]
     */
    public function getItems(): array
    {
        $this->initialize();

        return $this->items;
    }

    /**
     * @return array<string, AttributeValue>
     */
    public function getLastEvaluatedKey(): array
    {
        $this->initialize();

        return $this->lastEvaluatedKey;
    }

    public function getNextToken(): ?string
    {
        $this->initialize();

        return $this->nextToken;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->items = empty($data['Items']) ? [] : $this->populateResultItemList($data['Items']);
        $this->nextToken = isset($data['NextToken']) ? (string) $data['NextToken'] : null;
        $this->consumedCapacity = empty($data['ConsumedCapacity']) ? null : $this->populateResultConsumedCapacity($data['ConsumedCapacity']);
        $this->lastEvaluatedKey = empty($data['LastEvaluatedKey']) ? [] : $this->populateResultKey($data['LastEvaluatedKey']);
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
