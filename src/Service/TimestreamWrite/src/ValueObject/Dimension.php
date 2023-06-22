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
     */
    private $name;

    /**
     * The value of the dimension.
     */
    private $value;

    /**
     * The data type of the dimension for the time-series data point.
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
        $this->name = $input['Name'] ?? null;
        $this->value = $input['Value'] ?? null;
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
        if (null === $v = $this->name) {
            throw new InvalidArgument(sprintf('Missing parameter "Name" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Name'] = $v;
        if (null === $v = $this->value) {
            throw new InvalidArgument(sprintf('Missing parameter "Value" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Value'] = $v;
        if (null !== $v = $this->dimensionValueType) {
            if (!DimensionValueType::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "DimensionValueType" for "%s". The value "%s" is not a valid "DimensionValueType".', __CLASS__, $v));
            }
            $payload['DimensionValueType'] = $v;
        }

        return $payload;
    }
}
