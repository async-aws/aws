<?php

namespace AsyncAws\CloudFormation\Result;

use AsyncAws\CloudFormation\Enum\StackDriftStatus;

class StackDriftInformation
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
     *   StackDriftStatus: \AsyncAws\CloudFormation\Enum\StackDriftStatus::DRIFTED|\AsyncAws\CloudFormation\Enum\StackDriftStatus::IN_SYNC|\AsyncAws\CloudFormation\Enum\StackDriftStatus::UNKNOWN|\AsyncAws\CloudFormation\Enum\StackDriftStatus::NOT_CHECKED,
     *   LastCheckTimestamp: ?\DateTimeInterface,
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

    public function getLastCheckTimestamp(): ?\DateTimeInterface
    {
        return $this->LastCheckTimestamp;
    }

    /**
     * @return StackDriftStatus::DRIFTED|StackDriftStatus::IN_SYNC|StackDriftStatus::UNKNOWN|StackDriftStatus::NOT_CHECKED
     */
    public function getStackDriftStatus(): string
    {
        return $this->StackDriftStatus;
    }
}
