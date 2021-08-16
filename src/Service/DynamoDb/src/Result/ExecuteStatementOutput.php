<?php

namespace AsyncAws\DynamoDb\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\DynamoDb\ValueObject\AttributeValue;

class ExecuteStatementOutput extends Result
{
    /**
     * If a read operation was used, this property will contain the result of the reade operation; a map of attribute names
     * and their values. For the write operations this value will be empty.
     */
    private $items = [];

    /**
     * If the response of a read request exceeds the response payload limit DynamoDB will set this value in the response. If
     * set, you can use that this value in the subsequent request to get the remaining results.
     */
    private $nextToken;

    /**
     * @return array<string, AttributeValue>[]
     */
    public function getItems(): array
    {
        $this->initialize();

        return $this->items;
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
}
