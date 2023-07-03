<?php

namespace AsyncAws\S3\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * A container of a key value name pair.
 */
final class Tag
{
    /**
     * Name of the object key.
     *
     * @var string
     */
    private $key;

    /**
     * Value of the tag.
     *
     * @var string
     */
    private $value;

    /**
     * @param array{
     *   Key: string,
     *   Value: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->key = $input['Key'] ?? $this->throwException(new InvalidArgument('Missing required field "Key".'));
        $this->value = $input['Value'] ?? $this->throwException(new InvalidArgument('Missing required field "Value".'));
    }

    /**
     * @param array{
     *   Key: string,
     *   Value: string,
     * }|Tag $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @internal
     */
    public function requestBody(\DOMElement $node, \DOMDocument $document): void
    {
        $v = $this->key;
        $node->appendChild($document->createElement('Key', $v));
        $v = $this->value;
        $node->appendChild($document->createElement('Value', $v));
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
