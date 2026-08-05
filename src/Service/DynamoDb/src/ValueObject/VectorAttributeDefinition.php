<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * The definition of a vector attribute for a vector index.
 */
final class VectorAttributeDefinition
{
    /**
     * The name of the vector attribute.
     *
     * @var string
     */
    private $attributeName;

    /**
     * @param array{
     *   AttributeName: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->attributeName = $input['AttributeName'] ?? $this->throwException(new InvalidArgument('Missing required field "AttributeName".'));
    }

    /**
     * @param array{
     *   AttributeName: string,
     * }|VectorAttributeDefinition $input
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
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->attributeName;
        $payload['AttributeName'] = $v;

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
