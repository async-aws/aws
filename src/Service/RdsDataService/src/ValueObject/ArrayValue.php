<?php

namespace AsyncAws\RdsDataService\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Contains an array.
 */
abstract class ArrayValue
{
    /**
     * @param array{
     *   booleanValues: bool[],
     * }|array{
     *   longValues: int[],
     * }|array{
     *   doubleValues: float[],
     * }|array{
     *   stringValues: string[],
     * }|array{
     *   arrayValues: array<ArrayValue|array>,
     * }|ArrayValue $input
     */
    public static function create($input): self
    {
        if ($input instanceof self) {
            return $input;
        }
        if (isset($input['booleanValues'])) {
            return new ArrayValueMemberBooleanValues(['booleanValues' => $input['booleanValues']]);
        }
        if (isset($input['longValues'])) {
            return new ArrayValueMemberLongValues(['longValues' => $input['longValues']]);
        }
        if (isset($input['doubleValues'])) {
            return new ArrayValueMemberDoubleValues(['doubleValues' => $input['doubleValues']]);
        }
        if (isset($input['stringValues'])) {
            return new ArrayValueMemberStringValues(['stringValues' => $input['stringValues']]);
        }
        if (isset($input['arrayValues'])) {
            return new ArrayValueMemberArrayValues(['arrayValues' => $input['arrayValues']]);
        }

        throw new InvalidArgument('Invalid union input');
    }

    /**
     * @return ArrayValue[]|null
     */
    public function getArrayValues(): ?array
    {
        return null;
    }

    /**
     * @return bool[]|null
     */
    public function getBooleanValues(): ?array
    {
        return null;
    }

    /**
     * @return float[]|null
     */
    public function getDoubleValues(): ?array
    {
        return null;
    }

    /**
     * @return int[]|null
     */
    public function getLongValues(): ?array
    {
        return null;
    }

    /**
     * @return string[]|null
     */
    public function getStringValues(): ?array
    {
        return null;
    }

    /**
     * @internal
     */
    abstract public function requestBody(): array;
}
