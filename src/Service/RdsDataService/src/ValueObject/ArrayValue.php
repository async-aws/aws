<?php

namespace AsyncAws\RdsDataService\ValueObject;

/**
 * Contains an array.
 */
final class ArrayValue
{
    /**
     * An array of Boolean values.
     *
     * @var bool[]|null
     */
    private $booleanValues;

    /**
     * An array of integers.
     *
     * @var int[]|null
     */
    private $longValues;

    /**
     * An array of floating-point numbers.
     *
     * @var float[]|null
     */
    private $doubleValues;

    /**
     * An array of strings.
     *
     * @var string[]|null
     */
    private $stringValues;

    /**
     * An array of arrays.
     *
     * @var ArrayValue[]|null
     */
    private $arrayValues;

    /**
     * @param array{
     *   booleanValues?: bool[]|null,
     *   longValues?: int[]|null,
     *   doubleValues?: float[]|null,
     *   stringValues?: string[]|null,
     *   arrayValues?: array<ArrayValue|array>|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->booleanValues = $input['booleanValues'] ?? null;
        $this->longValues = $input['longValues'] ?? null;
        $this->doubleValues = $input['doubleValues'] ?? null;
        $this->stringValues = $input['stringValues'] ?? null;
        $this->arrayValues = isset($input['arrayValues']) ? array_map([ArrayValue::class, 'create'], $input['arrayValues']) : null;
    }

    /**
     * @param array{
     *   booleanValues?: bool[]|null,
     *   longValues?: int[]|null,
     *   doubleValues?: float[]|null,
     *   stringValues?: string[]|null,
     *   arrayValues?: array<ArrayValue|array>|null,
     * }|ArrayValue $input
     */
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
     * @return int[]
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
        if (null !== $v = $this->booleanValues) {
            $index = -1;
            $payload['booleanValues'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['booleanValues'][$index] = (bool) $listValue;
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
        if (null !== $v = $this->doubleValues) {
            $index = -1;
            $payload['doubleValues'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['doubleValues'][$index] = $listValue;
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
        if (null !== $v = $this->arrayValues) {
            $index = -1;
            $payload['arrayValues'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['arrayValues'][$index] = $listValue->requestBody();
            }
        }

        return $payload;
    }
}
