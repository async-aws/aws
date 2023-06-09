<?php

namespace AsyncAws\TimestreamWrite\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\TimestreamWrite\Enum\MeasureValueType;

/**
 * Represents the data attribute of the time series. For example, the CPU utilization of an EC2 instance or the RPM of a
 * wind turbine are measures. MeasureValue has both name and value.
 * MeasureValue is only allowed for type `MULTI`. Using `MULTI` type, you can pass multiple data attributes associated
 * with the same time series in a single record.
 */
final class MeasureValue
{
    /**
     * The name of the MeasureValue.
     */
    private $name;

    /**
     * The value for the MeasureValue. For information, see Data types.
     *
     * @see https://docs.aws.amazon.com/timestream/latest/developerguide/writes.html#writes.data-types
     */
    private $value;

    /**
     * Contains the data type of the MeasureValue for the time-series data point.
     */
    private $type;

    /**
     * @param array{
     *   Name: string,
     *   Value: string,
     *   Type: MeasureValueType::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['Name'] ?? null;
        $this->value = $input['Value'] ?? null;
        $this->type = $input['Type'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return MeasureValueType::*
     */
    public function getType(): string
    {
        return $this->type;
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
        if (null === $v = $this->type) {
            throw new InvalidArgument(sprintf('Missing parameter "Type" for "%s". The value cannot be null.', __CLASS__));
        }
        if (!MeasureValueType::exists($v)) {
            throw new InvalidArgument(sprintf('Invalid parameter "Type" for "%s". The value "%s" is not a valid "MeasureValueType".', __CLASS__, $v));
        }
        $payload['Type'] = $v;

        return $payload;
    }
}
