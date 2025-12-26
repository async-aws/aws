<?php

namespace AsyncAws\RdsDataService\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Contains a value.
 *
 * @psalm-inheritors FieldMemberIsNull|FieldMemberBooleanValue|FieldMemberLongValue|FieldMemberDoubleValue|FieldMemberStringValue|FieldMemberBlobValue|FieldMemberArrayValue|FieldMemberUnknownToSdk
 */
abstract class Field
{
    /**
     * @param array{
     *   isNull: bool,
     * }|array{
     *   booleanValue: bool,
     * }|array{
     *   longValue: int,
     * }|array{
     *   doubleValue: float,
     * }|array{
     *   stringValue: string,
     * }|array{
     *   blobValue: string,
     * }|array{
     *   arrayValue: ArrayValue|array,
     * }|Field $input
     */
    public static function create($input): self
    {
        if ($input instanceof self) {
            return $input;
        }
        if (isset($input['isNull'])) {
            return new FieldMemberIsNull(['isNull' => $input['isNull']]);
        }
        if (isset($input['booleanValue'])) {
            return new FieldMemberBooleanValue(['booleanValue' => $input['booleanValue']]);
        }
        if (isset($input['longValue'])) {
            return new FieldMemberLongValue(['longValue' => $input['longValue']]);
        }
        if (isset($input['doubleValue'])) {
            return new FieldMemberDoubleValue(['doubleValue' => $input['doubleValue']]);
        }
        if (isset($input['stringValue'])) {
            return new FieldMemberStringValue(['stringValue' => $input['stringValue']]);
        }
        if (isset($input['blobValue'])) {
            return new FieldMemberBlobValue(['blobValue' => $input['blobValue']]);
        }
        if (isset($input['arrayValue'])) {
            return new FieldMemberArrayValue(['arrayValue' => $input['arrayValue']]);
        }

        throw new InvalidArgument('Invalid union input');
    }

    /**
     * @internal
     */
    abstract public function requestBody(): array;
}
