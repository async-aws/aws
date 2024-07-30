<?php

namespace AsyncAws\TimestreamWrite\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\TimestreamWrite\Enum\DimensionValueType;

/**
 * Represents the metadata attributes of the time series. For example, the name and Availability Zone of an EC2 instance
 * or the name of the manufacturer of a wind turbine are dimensions.
 */
final class Dimension
{
    /**
     * Dimension represents the metadata attributes of the time series. For example, the name and Availability Zone of an
     * EC2 instance or the name of the manufacturer of a wind turbine are dimensions.
     *
     * For constraints on dimension names, see Naming Constraints [^1].
     *
     * [^1]: https://docs.aws.amazon.com/timestream/latest/developerguide/ts-limits.html#limits.naming
     *
     * @var string
     */
    private $name;

    /**
     * The value of the dimension.
     *
     * @var string
     */
    private $value;

    /**
     * The data type of the dimension for the time-series data point.
     *
     * @var DimensionValueType::*|null
     */
    private $dimensionValueType;

    /**
     * @param array{
     *   Name: string,
     *   Value: string,
     *   DimensionValueType?: null|DimensionValueType::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['Name'] ?? $this->throwException(new InvalidArgument('Missing required field "Name".'));
        $this->value = $input['Value'] ?? $this->throwException(new InvalidArgument('Missing required field "Value".'));
        $this->dimensionValueType = $input['DimensionValueType'] ?? null;
    }

    /**
     * @param array{
     *   Name: string,
     *   Value: string,
     *   DimensionValueType?: null|DimensionValueType::*,
     * }|Dimension $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return DimensionValueType::*|null
     */
    public function getDimensionValueType(): ?string
    {
        return $this->dimensionValueType;
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
        if (null !== $v = $this->dimensionValueType) {
            if (!DimensionValueType::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "DimensionValueType" for "%s". The value "%s" is not a valid "DimensionValueType".', __CLASS__, $v));
            }
            $payload['DimensionValueType'] = $v;
        }

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
