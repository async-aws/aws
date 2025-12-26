<?php

namespace AsyncAws\S3Vectors\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

final class VectorDataMemberFloat32 extends VectorData
{
    /**
     * The vector data as 32-bit floating point numbers. The number of elements in this array must exactly match the
     * dimension of the vector index where the operation is being performed.
     *
     * @var float[]
     */
    private $float32;

    /**
     * @param array{
     *   float32: float[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->float32 = $input['float32'] ?? $this->throwException(new InvalidArgument('Missing required field "float32".'));
    }

    /**
     * @return float[]
     */
    public function getFloat32(): array
    {
        return $this->float32;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->float32;

        $index = -1;
        $payload['float32'] = [];
        foreach ($v as $listValue) {
            ++$index;
            $payload['float32'][$index] = $listValue;
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
