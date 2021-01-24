<?php

namespace AsyncAws\RdsDataService\ValueObject;

/**
 * An array of values.
 */
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
     *   arrayValues?: null|ArrayValue[],
     *   booleanValues?: null|bool[],
     *   doubleValues?: null|float[],
     *   longValues?: null|string[],
     *   stringValues?: null|string[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->arrayValues = isset($input['arrayValues']) ? array_map([ArrayValue::class, 'create'], $input['arrayValues']) : null;
        $this->booleanValues = $input['booleanValues'] ?? null;
        $this->doubleValues = $input['doubleValues'] ?? null;
        $this->longValues = $input['longValues'] ?? null;
        $this->stringValues = $input['stringValues'] ?? null;
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
        return $this->arrayValues ?? [];
    }

    /**
     * @return bool[]
     */
    public function getBooleanValues(): array
    {
        return $this->booleanValues ?? [];
    }

    /**
     * @return float[]
     */
    public function getDoubleValues(): array
    {
        return $this->doubleValues ?? [];
    }

    /**
     * @return string[]
     */
    public function getLongValues(): array
    {
        return $this->longValues ?? [];
    }

    /**
     * @return string[]
     */
    public function getStringValues(): array
    {
        return $this->stringValues ?? [];
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->arrayValues) {
            $index = -1;
            $payload['arrayValues'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['arrayValues'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->booleanValues) {
            $index = -1;
            $payload['booleanValues'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['booleanValues'][$index] = (bool) $listValue;
            }
        }
        if (null !== $v = $this->doubleValues) {
            $index = -1;
            $payload['doubleValues'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['doubleValues'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->longValues) {
            $index = -1;
            $payload['longValues'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['longValues'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->stringValues) {
            $index = -1;
            $payload['stringValues'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['stringValues'][$index] = $listValue;
            }
        }

        return $payload;
    }
}
