<?php

namespace AsyncAws\Ses\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Contains the name and value of a tag that you apply to an email. You can use message tags when you publish email
 * sending events.
 */
final class MessageTag
{
    /**
     * The name of the message tag. The message tag name has to meet the following criteria:.
     */
    private $Name;

    /**
     * The value of the message tag. The message tag value has to meet the following criteria:.
     */
    private $Value;

    /**
     * @param array{
     *   Name: string,
     *   Value: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Name = $input['Name'] ?? null;
        $this->Value = $input['Value'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getName(): string
    {
        return $this->Name;
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
        if (null === $v = $this->Name) {
            throw new InvalidArgument(sprintf('Missing parameter "Name" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Name'] = $v;
        if (null === $v = $this->Value) {
            throw new InvalidArgument(sprintf('Missing parameter "Value" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Value'] = $v;

        return $payload;
    }
}
