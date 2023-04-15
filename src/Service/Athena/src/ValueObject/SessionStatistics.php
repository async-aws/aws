<?php

namespace AsyncAws\Athena\ValueObject;

/**
 * Contains the DPU execution time.
 */
final class SessionStatistics
{
    /**
     * The data processing unit execution time for a session in milliseconds.
     */
    private $dpuExecutionInMillis;

    /**
     * @param array{
     *   DpuExecutionInMillis?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->dpuExecutionInMillis = $input['DpuExecutionInMillis'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDpuExecutionInMillis(): ?string
    {
        return $this->dpuExecutionInMillis;
    }
}
