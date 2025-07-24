<?php

namespace AsyncAws\CloudFormation\ValueObject;

use AsyncAws\CloudFormation\Enum\StackDriftStatus;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Contains information about whether the stack's actual configuration differs, or has *drifted*, from its expected
 * configuration, as defined in the stack template and any values specified as template parameters. A stack is
 * considered to have drifted if one or more of its resources have drifted.
 */
final class StackDriftInformation
{
    /**
     * Status of the stack's actual configuration compared to its expected template configuration.
     *
     * - `DRIFTED`: The stack differs from its expected template configuration. A stack is considered to have drifted if one
     *   or more of its resources have drifted.
     * - `NOT_CHECKED`: CloudFormation hasn't checked if the stack differs from its expected template configuration.
     * - `IN_SYNC`: The stack's actual configuration matches its expected template configuration.
     * - `UNKNOWN`: CloudFormation could not run drift detection for a resource in the stack.
     *
     * @var StackDriftStatus::*|string
     */
    private $stackDriftStatus;

    /**
     * Most recent time when a drift detection operation was initiated on the stack, or any of its individual resources that
     * support drift detection.
     *
     * @var \DateTimeImmutable|null
     */
    private $lastCheckTimestamp;

    /**
     * @param array{
     *   StackDriftStatus: StackDriftStatus::*|string,
     *   LastCheckTimestamp?: null|\DateTimeImmutable,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->stackDriftStatus = $input['StackDriftStatus'] ?? $this->throwException(new InvalidArgument('Missing required field "StackDriftStatus".'));
        $this->lastCheckTimestamp = $input['LastCheckTimestamp'] ?? null;
    }

    /**
     * @param array{
     *   StackDriftStatus: StackDriftStatus::*|string,
     *   LastCheckTimestamp?: null|\DateTimeImmutable,
     * }|StackDriftInformation $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getLastCheckTimestamp(): ?\DateTimeImmutable
    {
        return $this->lastCheckTimestamp;
    }

    /**
     * @return StackDriftStatus::*|string
     */
    public function getStackDriftStatus(): string
    {
        return $this->stackDriftStatus;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
