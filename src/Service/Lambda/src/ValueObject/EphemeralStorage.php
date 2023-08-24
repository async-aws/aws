<?php

namespace AsyncAws\Lambda\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * The size of the function's `/tmp` directory in MB. The default value is 512, but it can be any whole number between
 * 512 and 10,240 MB.
 */
final class EphemeralStorage
{
    /**
     * The size of the function's `/tmp` directory.
     *
     * @var int
     */
    private $size;

    /**
     * @param array{
     *   Size: int,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->size = $input['Size'] ?? $this->throwException(new InvalidArgument('Missing required field "Size".'));
    }

    /**
     * @param array{
     *   Size: int,
     * }|EphemeralStorage $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->size;
        $payload['Size'] = $v;

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
