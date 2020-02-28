<?php

namespace AsyncAws\CloudFormation\Result;

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
     *   StackDriftStatus: string,
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

    public function getStackDriftStatus(): string
    {
        return $this->StackDriftStatus;
    }
}
