<?php

namespace AsyncAws\Ssm\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Metadata that you assign to your Amazon Web Services resources. Tags enable you to categorize your resources in
 * different ways, for example, by purpose, owner, or environment. In Amazon Web Services Systems Manager, you can apply
 * tags to Systems Manager documents (SSM documents), managed nodes, maintenance windows, parameters, patch baselines,
 * OpsItems, and OpsMetadata.
 */
final class Tag
{
    /**
     * The name of the tag.
     *
     * @var string
     */
    private $key;

    /**
     * The value of the tag.
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
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->key;
        $payload['Key'] = $v;
        $v = $this->value;
        $payload['Value'] = $v;

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
