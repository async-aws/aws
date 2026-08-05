<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\DynamoDb\Enum\SearchSchemaElementType;

/**
 * An element in the search schema of a vector index.
 */
final class SearchSchemaElement
{
    /**
     * The name of the attribute.
     *
     * @var string
     */
    private $attributeName;

    /**
     * The role of the attribute in the search schema. Valid values:
     *
     * - `HASH` - A partition key that partitions the vector index for independent scaling. When specified, you must provide
     *   this attribute's value in the `SearchConditionExpression`.
     * - `INLINE_FILTER` - An attribute projected into the vector index for filtering at the storage layer during search.
     *   Inline filters are optional in the `SearchConditionExpression`.
     *
     * @var SearchSchemaElementType::*
     */
    private $searchSchemaElementType;

    /**
     * @param array{
     *   AttributeName: string,
     *   SearchSchemaElementType: SearchSchemaElementType::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->attributeName = $input['AttributeName'] ?? $this->throwException(new InvalidArgument('Missing required field "AttributeName".'));
        $this->searchSchemaElementType = $input['SearchSchemaElementType'] ?? $this->throwException(new InvalidArgument('Missing required field "SearchSchemaElementType".'));
    }

    /**
     * @param array{
     *   AttributeName: string,
     *   SearchSchemaElementType: SearchSchemaElementType::*,
     * }|SearchSchemaElement $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAttributeName(): string
    {
        return $this->attributeName;
    }

    /**
     * @return SearchSchemaElementType::*
     */
    public function getSearchSchemaElementType(): string
    {
        return $this->searchSchemaElementType;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->attributeName;
        $payload['AttributeName'] = $v;
        $v = $this->searchSchemaElementType;
        if (!SearchSchemaElementType::exists($v)) {
            /** @psalm-suppress NoValue */
            throw new InvalidArgument(\sprintf('Invalid parameter "SearchSchemaElementType" for "%s". The value "%s" is not a valid "SearchSchemaElementType".', __CLASS__, $v));
        }
        $payload['SearchSchemaElementType'] = $v;

        return $payload;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
