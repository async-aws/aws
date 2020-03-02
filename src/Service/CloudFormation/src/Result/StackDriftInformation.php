<?php

namespace AsyncAws\CloudFormation\Result;

use AsyncAws\CloudFormation\Enum\StackDriftStatus;

class StackDriftInformation
{
    private $StackDriftStatus;

    private $LastCheckTimestamp;

    /**
     * @param array{
     *   StackDriftStatus: \AsyncAws\CloudFormation\Enum\StackDriftStatus::*,
     *   LastCheckTimestamp: null|\DateTimeInterface,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->StackDriftStatus = $input['StackDriftStatus'];
        $this->LastCheckTimestamp = $input['LastCheckTimestamp'];
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * Most recent time when a drift detection operation was initiated on the stack, or any of its individual resources that
     * support drift detection.
     */
    public function getLastCheckTimestamp(): ?\DateTimeInterface
    {
        return $this->LastCheckTimestamp;
    }

    /**
     * Status of the stack's actual configuration compared to its expected template configuration.
     *
     * @return StackDriftStatus::*
     */
    public function getStackDriftStatus(): string
    {
        return $this->StackDriftStatus;
    }
}
