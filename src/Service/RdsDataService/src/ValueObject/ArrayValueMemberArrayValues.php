<?php

namespace AsyncAws\RdsDataService\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

final class ArrayValueMemberArrayValues extends ArrayValue
{
    /**
     * An array of arrays.
     *
     * @var ArrayValue[]
     */
    private $arrayValues;

    /**
     * @param array{
     *   arrayValues: array<ArrayValue|array>,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->arrayValues = isset($input['arrayValues']) ? array_map([ArrayValue::class, 'create'], $input['arrayValues']) : $this->throwException(new InvalidArgument('Missing required field "arrayValues".'));
    }

    /**
     * @return ArrayValue[]
     */
    public function getArrayValues(): array
    {
        return $this->arrayValues;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->arrayValues;

        $index = -1;
        $payload['arrayValues'] = [];
        foreach ($v as $listValue) {
            ++$index;
            $payload['arrayValues'][$index] = $listValue->requestBody();
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
