<?php

namespace AsyncAws\RdsDataService\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

final class ArrayValueMemberDoubleValues extends ArrayValue
{
    /**
     * An array of floating-point numbers.
     *
     * @var float[]
     */
    private $doubleValues;

    /**
     * @param array{
     *   doubleValues: float[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->doubleValues = $input['doubleValues'] ?? $this->throwException(new InvalidArgument('Missing required field "doubleValues".'));
    }

    /**
     * @return float[]
     */
    public function getDoubleValues(): array
    {
        return $this->doubleValues;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->doubleValues;

        $index = -1;
        $payload['doubleValues'] = [];
        foreach ($v as $listValue) {
            ++$index;
            $payload['doubleValues'][$index] = $listValue;
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
