<?php

namespace AsyncAws\CognitoIdentityProvider\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Specifies whether the attribute is standard or custom.
 */
final class AttributeType
{
    /**
     * The name of the attribute.
     */
    private $Name;

    /**
     * The value of the attribute.
     */
    private $Value;

    /**
     * @param array{
     *   Name: string,
     *   Value?: null|string,
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

    public function getValue(): ?string
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
        if (null !== $v = $this->Value) {
            $payload['Value'] = $v;
        }

        return $payload;
    }
}
