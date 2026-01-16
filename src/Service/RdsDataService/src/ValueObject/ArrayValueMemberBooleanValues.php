<?php

namespace AsyncAws\RdsDataService\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

final class ArrayValueMemberBooleanValues extends ArrayValue
{
    /**
     * An array of Boolean values.
     *
     * @var bool[]
     */
    private $booleanValues;

    /**
     * @param array{
     *   booleanValues: bool[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->booleanValues = $input['booleanValues'] ?? $this->throwException(new InvalidArgument('Missing required field "booleanValues".'));
    }

    /**
     * @return bool[]
     */
    public function getBooleanValues(): array
    {
        return $this->booleanValues;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->booleanValues;

        $index = -1;
        $payload['booleanValues'] = [];
        foreach ($v as $listValue) {
            ++$index;
            $payload['booleanValues'][$index] = (bool) $listValue;
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
