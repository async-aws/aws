<?php

namespace AsyncAws\Athena\ValueObject;

/**
 * Contains information about the data processing unit (DPU) execution time and progress. This field is populated only
 * when statistics are available.
 */
final class CalculationStatistics
{
    /**
     * The data processing unit execution time in milliseconds for the calculation.
     */
    private $dpuExecutionInMillis;

    /**
     * The progress of the calculation.
     */
    private $progress;

    /**
     * @param array{
     *   DpuExecutionInMillis?: null|string,
     *   Progress?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->dpuExecutionInMillis = $input['DpuExecutionInMillis'] ?? null;
        $this->progress = $input['Progress'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDpuExecutionInMillis(): ?string
    {
        return $this->dpuExecutionInMillis;
    }

    public function getProgress(): ?string
    {
        return $this->progress;
    }
}
