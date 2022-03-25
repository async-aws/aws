<?php

namespace AsyncAws\Lambda\ValueObject;

/**
 * The size of the function’s /tmp directory in MB. The default value is 512, but can be any whole number between 512
 * and 10240 MB.
 */
final class EphemeralStorage
{
    /**
     * The size of the function’s /tmp directory.
     */
    private $size;

    /**
     * @param array{
     *   Size: int,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->size = $input['Size'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getSize(): int
    {
        return $this->size;
    }
}
