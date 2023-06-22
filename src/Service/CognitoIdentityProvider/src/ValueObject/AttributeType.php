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
    private $name;

    /**
     * The value of the attribute.
     */
    private $value;

    /**
     * @param array{
     *   Name: string,
     *   Value?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['Name'] ?? null;
        $this->value = $input['Value'] ?? null;
    }

    /**
     * @param array{
     *   Name: string,
     *   Value?: null|string,
     * }|AttributeType $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->name) {
            throw new InvalidArgument(sprintf('Missing parameter "Name" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Name'] = $v;
        if (null !== $v = $this->value) {
            $payload['Value'] = $v;
        }

        return $payload;
    }
}
