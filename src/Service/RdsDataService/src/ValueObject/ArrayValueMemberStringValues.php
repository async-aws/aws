<?php

namespace AsyncAws\RdsDataService\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

final class ArrayValueMemberStringValues extends ArrayValue
{
    /**
     * An array of strings.
     *
     * @var string[]
     */
    private $stringValues;

    /**
     * @param array{
     *   stringValues: string[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->stringValues = $input['stringValues'] ?? $this->throwException(new InvalidArgument('Missing required field "stringValues".'));
    }

    /**
     * @return string[]
     */
    public function getStringValues(): array
    {
        return $this->stringValues;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->stringValues;

        $index = -1;
        $payload['stringValues'] = [];
        foreach ($v as $listValue) {
            ++$index;
            $payload['stringValues'][$index] = $listValue;
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
