<?php

namespace AsyncAws\S3Vectors\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * The vector data in different formats.
 *
 * @psalm-inheritors VectorDataMemberFloat32|VectorDataMemberUnknownToSdk
 */
abstract class VectorData
{
    /**
     * @param array{
     *   float32: float[],
     * }|VectorData $input
     */
    public static function create($input): self
    {
        if ($input instanceof self) {
            return $input;
        }
        if (isset($input['float32'])) {
            return new VectorDataMemberFloat32(['float32' => $input['float32']]);
        }

        throw new InvalidArgument('Invalid union input');
    }

    /**
     * @internal
     */
    abstract public function requestBody(): array;
}
