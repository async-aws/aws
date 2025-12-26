<?php

namespace AsyncAws\RdsDataService\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Contains an array.
 *
 * @psalm-inheritors ArrayValueMemberBooleanValues|ArrayValueMemberLongValues|ArrayValueMemberDoubleValues|ArrayValueMemberStringValues|ArrayValueMemberArrayValues|ArrayValueMemberUnknownToSdk
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
     * @internal
     */
    abstract public function requestBody(): array;
}
