<?php

namespace AsyncAws\CloudWatch\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * A dimension is a name/value pair that is part of the identity of a metric. Because dimensions are part of the unique
 * identifier for a metric, whenever you add a unique name/value pair to one of your metrics, you are creating a new
 * variation of that metric. For example, many Amazon EC2 metrics publish `InstanceId` as a dimension name, and the
 * actual instance ID as the value for that dimension.
 *
 * You can assign up to 30 dimensions to a metric.
 */
final class Dimension
{
    /**
     * The name of the dimension. Dimension names must contain only ASCII characters, must include at least one
     * non-whitespace character, and cannot start with a colon (`:`). ASCII control characters are not supported as part of
     * dimension names.
     */
    private $name;

    /**
     * The value of the dimension. Dimension values must contain only ASCII characters and must include at least one
     * non-whitespace character. ASCII control characters are not supported as part of dimension values.
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
        $this->name = $input['Name'] ?? $this->throwException(new InvalidArgument('Missing required field "Name".'));
        $this->value = $input['Value'] ?? $this->throwException(new InvalidArgument('Missing required field "Value".'));
    }

    /**
     * @param array{
     *   Name: string,
     *   Value: string,
     * }|Dimension $input
     */
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
        $v = $this->name;
        $payload['Name'] = $v;
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
