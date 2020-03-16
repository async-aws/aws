<?php

namespace AsyncAws\CloudFormation\ValueObject;

use AsyncAws\CloudFormation\Enum\StackDriftStatus;
use AsyncAws\Core\Exception\InvalidArgument;

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
     *   StackDriftStatus: \AsyncAws\CloudFormation\Enum\StackDriftStatus::*,
     *   LastCheckTimestamp?: null|\DateTimeInterface,
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

    public function getLastCheckTimestamp(): ?\DateTimeInterface
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

    public function validate(): void
    {
        if (null === $this->StackDriftStatus) {
            throw new InvalidArgument(sprintf('Missing parameter "StackDriftStatus" when validating the "%s". The value cannot be null.', __CLASS__));
        }
        if (!StackDriftStatus::exists($this->StackDriftStatus)) {
            throw new InvalidArgument(sprintf('Invalid parameter "StackDriftStatus" when validating the "%s". The value "%s" is not a valid "StackDriftStatus".', __CLASS__, $this->StackDriftStatus));
        }
    }
}
