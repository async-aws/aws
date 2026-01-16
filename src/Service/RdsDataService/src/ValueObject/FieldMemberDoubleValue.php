<?php

namespace AsyncAws\RdsDataService\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

final class FieldMemberDoubleValue extends Field
{
    /**
     * A value of double data type.
     *
     * @var float
     */
    private $doubleValue;

    /**
     * @param array{
     *   doubleValue: float,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->doubleValue = $input['doubleValue'] ?? $this->throwException(new InvalidArgument('Missing required field "doubleValue".'));
    }

    public function getDoubleValue(): float
    {
        return $this->doubleValue;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->doubleValue;
        $payload['doubleValue'] = $v;

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
