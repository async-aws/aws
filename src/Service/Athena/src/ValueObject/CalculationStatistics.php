<?php

namespace AsyncAws\Athena\ValueObject;

/**
 * Contains statistics for a notebook calculation.
 */
final class CalculationStatistics
{
    /**
     * The data processing unit execution time in milliseconds for the calculation.
     *
     * @var int|null
     */
    private $dpuExecutionInMillis;

    /**
     * The progress of the calculation.
     *
     * @var string|null
     */
    private $progress;

    /**
     * @param array{
     *   DpuExecutionInMillis?: int|null,
     *   Progress?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->dpuExecutionInMillis = $input['DpuExecutionInMillis'] ?? null;
        $this->progress = $input['Progress'] ?? null;
    }

    /**
     * @param array{
     *   DpuExecutionInMillis?: int|null,
     *   Progress?: string|null,
     * }|CalculationStatistics $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDpuExecutionInMillis(): ?int
    {
        return $this->dpuExecutionInMillis;
    }

    public function getProgress(): ?string
    {
        return $this->progress;
    }
}
