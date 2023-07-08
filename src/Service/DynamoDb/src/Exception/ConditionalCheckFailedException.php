<?php

namespace AsyncAws\DynamoDb\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use AsyncAws\DynamoDb\ValueObject\AttributeValue;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * A condition specified in the operation could not be evaluated.
 */
final class ConditionalCheckFailedException extends ClientException
{
    /**
     * Item which caused the `ConditionalCheckFailedException`.
     *
     * @var array<string, AttributeValue>
     */
    private $item;

    /**
     * @return array<string, AttributeValue>
     */
    public function getItem(): array
    {
        return $this->item;
    }

    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        $this->item = empty($data['Item']) ? [] : $this->populateResultAttributeMap($data['Item']);
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
}
