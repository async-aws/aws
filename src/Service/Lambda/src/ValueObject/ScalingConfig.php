<?php

namespace AsyncAws\Lambda\ValueObject;

/**
 * (Amazon SQS only) The scaling configuration for the event source. To remove the configuration, pass an empty value.
 */
final class ScalingConfig
{
    /**
     * Limits the number of concurrent instances that the Amazon SQS event source can invoke.
     *
     * @var int|null
     */
    private $maximumConcurrency;

    /**
     * @param array{
     *   MaximumConcurrency?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->maximumConcurrency = $input['MaximumConcurrency'] ?? null;
    }

    /**
     * @param array{
     *   MaximumConcurrency?: int|null,
     * }|ScalingConfig $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getMaximumConcurrency(): ?int
    {
        return $this->maximumConcurrency;
    }
}
