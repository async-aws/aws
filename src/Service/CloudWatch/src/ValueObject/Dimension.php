<?php

namespace AsyncAws\CloudWatch\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * A dimension is a name/value pair that is part of the identity of a metric. You can assign up to 10 dimensions to a
 * metric. Because dimensions are part of the unique identifier for a metric, whenever you add a unique name/value pair
 * to one of your metrics, you are creating a new variation of that metric.
 */
final class Dimension
{
    /**
     * The name of the dimension. Dimension names must contain only ASCII characters and must include at least one
     * non-whitespace character.
     */
    private $name;

    /**
     * The value of the dimension. Dimension values must contain only ASCII characters and must include at least one
     * non-whitespace character.
     */
    private $value;

    /**
     * @param array{
     *   Name: string,
     *   Value: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['Name'] ?? null;
        $this->value = $input['Value'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getName(): string
    {
        return $this->name;
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
        if (null === $v = $this->name) {
            throw new InvalidArgument(sprintf('Missing parameter "Name" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Name'] = $v;
        if (null === $v = $this->value) {
            throw new InvalidArgument(sprintf('Missing parameter "Value" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Value'] = $v;

        return $payload;
    }
}
