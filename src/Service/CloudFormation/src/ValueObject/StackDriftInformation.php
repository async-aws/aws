<?php

namespace AsyncAws\CloudFormation\ValueObject;

use AsyncAws\CloudFormation\Enum\StackDriftStatus;

final class StackDriftInformation
{
    /**
     * Status of the stack's actual configuration compared to its expected template configuration.
     */
    private $StackDriftStatus;

    /**
     * Most recent time when a drift detection operation was initiated on the stack, or any of its individual resources that
     * support drift detection.
     */
    private $LastCheckTimestamp;

    /**
     * @param array{
     *   StackDriftStatus: \AsyncAws\CloudFormation\Enum\StackDriftStatus::*,
     *   LastCheckTimestamp?: null|\DateTimeImmutable,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->StackDriftStatus = $input['StackDriftStatus'] ?? null;
        $this->LastCheckTimestamp = $input['LastCheckTimestamp'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getLastCheckTimestamp(): ?\DateTimeImmutable
    {
        return $this->LastCheckTimestamp;
    }

    /**
     * @return StackDriftStatus::*
     */
    public function getStackDriftStatus(): string
    {
        return $this->StackDriftStatus;
    }
}
