<?php

namespace AsyncAws\Lambda\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * The accelerator resource settings for a Lambda execution environment, including the amount of accelerator memory to
 * allocate.
 */
final class AcceleratorConfig
{
    /**
     * The minimum amount of memory (in GB) of each accelerator device exposed to the execution environment. Lambda might
     * allocate more, depending on the accelerator instance types available.
     *
     * @var int
     */
    private $acceleratorMemorySize;

    /**
     * @param array{
     *   AcceleratorMemorySize: int,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->acceleratorMemorySize = $input['AcceleratorMemorySize'] ?? $this->throwException(new InvalidArgument('Missing required field "AcceleratorMemorySize".'));
    }

    /**
     * @param array{
     *   AcceleratorMemorySize: int,
     * }|AcceleratorConfig $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAcceleratorMemorySize(): int
    {
        return $this->acceleratorMemorySize;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->acceleratorMemorySize;
        $payload['AcceleratorMemorySize'] = $v;

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
