<?php

namespace AsyncAws\Ssm\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Metadata that you assign to your AWS resources. Tags enable you to categorize your resources in different ways, for
 * example, by purpose, owner, or environment. In Systems Manager, you can apply tags to documents, managed instances,
 * maintenance windows, Parameter Store parameters, and patch baselines.
 */
final class Tag
{
    /**
     * The name of the tag.
     */
    private $Key;

    /**
     * The value of the tag.
     */
    private $Value;

    /**
     * @param array{
     *   Key: string,
     *   Value: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Key = $input['Key'] ?? null;
        $this->Value = $input['Value'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getKey(): string
    {
        return $this->Key;
    }

    public function getValue(): string
    {
        return $this->Value;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->Key) {
            throw new InvalidArgument(sprintf('Missing parameter "Key" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Key'] = $v;
        if (null === $v = $this->Value) {
            throw new InvalidArgument(sprintf('Missing parameter "Value" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Value'] = $v;

        return $payload;
    }
}
