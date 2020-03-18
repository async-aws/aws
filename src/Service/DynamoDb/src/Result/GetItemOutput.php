<?php

namespace AsyncAws\DynamoDb\Result;

use AsyncAws\Core\Result;
use AsyncAws\DynamoDb\ValueObject\AttributeValue;
use AsyncAws\DynamoDb\ValueObject\Capacity;
use AsyncAws\DynamoDb\ValueObject\ConsumedCapacity;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class GetItemOutput extends Result
{
    /**
     * A map of attribute names to `AttributeValue` objects, as specified by `ProjectionExpression`.
     */
    private $Item = [];

    /**
     * The capacity units consumed by the `GetItem` operation. The data returned includes the total provisioned throughput
     * consumed, along with statistics for the table and any indexes involved in the operation. `ConsumedCapacity` is only
     * returned if the `ReturnConsumedCapacity` parameter was specified. For more information, see Read/Write Capacity Mode
     * in the *Amazon DynamoDB Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/ProvisionedThroughputIntro.html
     */
    private $ConsumedCapacity;

    public function getConsumedCapacity(): ?ConsumedCapacity
    {
        $this->initialize();

        return $this->ConsumedCapacity;
    }

    /**
     * @return AttributeValue[]
     */
    public function getItem(): array
    {
        $this->initialize();

        return $this->Item;
    }

    protected function populateResult(ResponseInterface $response, HttpClientInterface $httpClient): void
    {
        $data = $response->toArray(false);

        $this->Item = empty($data['Item']) ? [] : (function (array $json): array {
            $items = [];
            foreach ($json as $name => $value) {
                $items[$name] = AttributeValue::create($value);
            }

            return $items;
        })($data['Item']);
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
                    $items[$name] = Capacity::create($value);
                }

                return $items;
            })($data['ConsumedCapacity']['LocalSecondaryIndexes']),
            'GlobalSecondaryIndexes' => empty($data['ConsumedCapacity']['GlobalSecondaryIndexes']) ? [] : (function (array $json): array {
                $items = [];
                foreach ($json as $name => $value) {
                    $items[$name] = Capacity::create($value);
                }

                return $items;
            })($data['ConsumedCapacity']['GlobalSecondaryIndexes']),
        ]);
    }
}
