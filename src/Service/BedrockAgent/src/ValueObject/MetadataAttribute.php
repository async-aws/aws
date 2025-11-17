<?php

namespace AsyncAws\BedrockAgent\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Contains information about a metadata attribute.
 */
final class MetadataAttribute
{
    /**
     * The key of the metadata attribute.
     *
     * @var string
     */
    private $key;

    /**
     * Contains the value of the metadata attribute.
     *
     * @var MetadataAttributeValue
     */
    private $value;

    /**
     * @param array{
     *   key: string,
     *   value: MetadataAttributeValue|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->key = $input['key'] ?? $this->throwException(new InvalidArgument('Missing required field "key".'));
        $this->value = isset($input['value']) ? MetadataAttributeValue::create($input['value']) : $this->throwException(new InvalidArgument('Missing required field "value".'));
    }

    /**
     * @param array{
     *   key: string,
     *   value: MetadataAttributeValue|array,
     * }|MetadataAttribute $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getValue(): MetadataAttributeValue
    {
        return $this->value;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->key;
        $payload['key'] = $v;
        $v = $this->value;
        $payload['value'] = $v->requestBody();

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
