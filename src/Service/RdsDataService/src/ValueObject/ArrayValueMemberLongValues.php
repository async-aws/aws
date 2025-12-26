<?php

namespace AsyncAws\RdsDataService\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

final class ArrayValueMemberLongValues extends ArrayValue
{
    /**
     * An array of integers.
     *
     * @var int[]
     */
    private $longValues;

    /**
     * @param array{
     *   longValues: int[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->longValues = $input['longValues'] ?? $this->throwException(new InvalidArgument('Missing required field "longValues".'));
    }

    /**
     * @return int[]
     */
    public function getLongValues(): array
    {
        return $this->longValues;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->longValues;

        $index = -1;
        $payload['longValues'] = [];
        foreach ($v as $listValue) {
            ++$index;
            $payload['longValues'][$index] = $listValue;
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
