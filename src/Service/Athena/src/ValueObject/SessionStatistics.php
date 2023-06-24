<?php

namespace AsyncAws\Athena\ValueObject;

/**
 * Contains statistics for a session.
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

    /**
     * @param array{
     *   DpuExecutionInMillis?: null|string,
     * }|SessionStatistics $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDpuExecutionInMillis(): ?string
    {
        return $this->dpuExecutionInMillis;
    }
}
