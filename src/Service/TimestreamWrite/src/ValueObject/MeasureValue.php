<?php

namespace AsyncAws\TimestreamWrite\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\TimestreamWrite\Enum\MeasureValueType;

/**
 * Represents the data attribute of the time series. For example, the CPU utilization of an EC2 instance or the RPM of a
 * wind turbine are measures. MeasureValue has both name and value.
 *
 * MeasureValue is only allowed for type `MULTI`. Using `MULTI` type, you can pass multiple data attributes associated
 * with the same time series in a single record
 */
final class MeasureValue
{
    /**
     * The name of the MeasureValue.
     *
     * For constraints on MeasureValue names, see  Naming Constraints [^1] in the Amazon Timestream Developer Guide.
     *
     * [^1]: https://docs.aws.amazon.com/timestream/latest/developerguide/ts-limits.html#limits.naming
     */
    private $name;

    /**
     * The value for the MeasureValue. For information, see Data types [^1].
     *
     * [^1]: https://docs.aws.amazon.com/timestream/latest/developerguide/writes.html#writes.data-types
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
        $this->name = $input['Name'] ?? $this->throwException(new InvalidArgument('Missing required field "Name".'));
        $this->value = $input['Value'] ?? $this->throwException(new InvalidArgument('Missing required field "Value".'));
        $this->type = $input['Type'] ?? $this->throwException(new InvalidArgument('Missing required field "Type".'));
    }

    /**
     * @param array{
     *   Name: string,
     *   Value: string,
     *   Type: MeasureValueType::*,
     * }|MeasureValue $input
     */
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
        $v = $this->name;
        $payload['Name'] = $v;
        $v = $this->value;
        $payload['Value'] = $v;
        $v = $this->type;
        if (!MeasureValueType::exists($v)) {
            throw new InvalidArgument(sprintf('Invalid parameter "Type" for "%s". The value "%s" is not a valid "MeasureValueType".', __CLASS__, $v));
        }
        $payload['Type'] = $v;

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
