<?php

namespace AsyncAws\Athena\ValueObject;

/**
 * Contains statistics for a session.
 */
final class SessionStatistics
{
    /**
     * The data processing unit execution time for a session in milliseconds.
     *
     * @var int|null
     */
    private $dpuExecutionInMillis;

    /**
     * @param array{
     *   DpuExecutionInMillis?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->dpuExecutionInMillis = $input['DpuExecutionInMillis'] ?? null;
    }

    /**
     * @param array{
     *   DpuExecutionInMillis?: int|null,
     * }|SessionStatistics $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDpuExecutionInMillis(): ?int
    {
        return $this->dpuExecutionInMillis;
    }
}
