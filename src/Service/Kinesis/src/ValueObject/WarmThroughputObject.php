<?php

namespace AsyncAws\Kinesis\ValueObject;

/**
 * Represents the warm throughput configuration on the stream. This is only present for On-Demand Kinesis Data Streams
 * in accounts that have `MinimumThroughputBillingCommitment` enabled.
 */
final class WarmThroughputObject
{
    /**
     * The target warm throughput value on the stream. This indicates that the stream is currently scaling towards this
     * target value.
     *
     * @var int|null
     */
    private $targetMiBps;

    /**
     * The current warm throughput value on the stream. This is the write throughput in MiBps that the stream is currently
     * scaled to handle.
     *
     * @var int|null
     */
    private $currentMiBps;

    /**
     * @param array{
     *   TargetMiBps?: int|null,
     *   CurrentMiBps?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->targetMiBps = $input['TargetMiBps'] ?? null;
        $this->currentMiBps = $input['CurrentMiBps'] ?? null;
    }

    /**
     * @param array{
     *   TargetMiBps?: int|null,
     *   CurrentMiBps?: int|null,
     * }|WarmThroughputObject $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCurrentMiBps(): ?int
    {
        return $this->currentMiBps;
    }

    public function getTargetMiBps(): ?int
    {
        return $this->targetMiBps;
    }
}
