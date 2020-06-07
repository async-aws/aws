<?php

namespace AsyncAws\RdsDataService\ValueObject;

final class ArrayValue
{
    /**
     * An array of arrays.
     */
    private $arrayValues;

    /**
     * An array of Boolean values.
     */
    private $booleanValues;

    /**
     * An array of integers.
     */
    private $doubleValues;

    /**
     * An array of floating point numbers.
     */
    private $longValues;

    /**
     * An array of strings.
     */
    private $stringValues;

    /**
     * @param array{
     *   arrayValues?: null|\AsyncAws\RdsDataService\ValueObject\ArrayValue[],
     *   booleanValues?: null|bool[],
     *   doubleValues?: null|float[],
     *   longValues?: null|string[],
     *   stringValues?: null|string[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->arrayValues = array_map([ArrayValue::class, 'create'], $input['arrayValues'] ?? []);
        $this->booleanValues = $input['booleanValues'] ?? [];
        $this->doubleValues = $input['doubleValues'] ?? [];
        $this->longValues = $input['longValues'] ?? [];
        $this->stringValues = $input['stringValues'] ?? [];
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return ArrayValue[]
     */
    public function getArrayValues(): array
    {
        return $this->arrayValues;
    }

    /**
     * @return bool[]
     */
    public function getBooleanValues(): array
    {
        return $this->booleanValues;
    }

    /**
     * @return float[]
     */
    public function getDoubleValues(): array
    {
        return $this->doubleValues;
    }

    /**
     * @return string[]
     */
    public function getLongValues(): array
    {
        return $this->longValues;
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

        $index = -1;
        foreach ($this->arrayValues as $listValue) {
            ++$index;
            $payload['arrayValues'][$index] = $listValue->requestBody();
        }

        $index = -1;
        foreach ($this->booleanValues as $listValue) {
            ++$index;
            $payload['booleanValues'][$index] = (bool) $listValue;
        }

        $index = -1;
        foreach ($this->doubleValues as $listValue) {
            ++$index;
            $payload['doubleValues'][$index] = $listValue;
        }

        $index = -1;
        foreach ($this->longValues as $listValue) {
            ++$index;
            $payload['longValues'][$index] = $listValue;
        }

        $index = -1;
        foreach ($this->stringValues as $listValue) {
            ++$index;
            $payload['stringValues'][$index] = $listValue;
        }

        return $payload;
    }
}
