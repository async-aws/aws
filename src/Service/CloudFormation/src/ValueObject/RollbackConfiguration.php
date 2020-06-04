<?php

namespace AsyncAws\CloudFormation\ValueObject;

final class RollbackConfiguration
{
    /**
     * The triggers to monitor during stack creation or update actions.
     */
    private $RollbackTriggers;

    /**
     * The amount of time, in minutes, during which CloudFormation should monitor all the rollback triggers after the stack
     * creation or update operation deploys all necessary resources.
     */
    private $MonitoringTimeInMinutes;

    /**
     * @param array{
     *   RollbackTriggers?: null|\AsyncAws\CloudFormation\ValueObject\RollbackTrigger[],
     *   MonitoringTimeInMinutes?: null|int,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->RollbackTriggers = isset($input['RollbackTriggers']) ? array_map([RollbackTrigger::class, 'create'], $input['RollbackTriggers']) : null;
        $this->MonitoringTimeInMinutes = $input['MonitoringTimeInMinutes'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getMonitoringTimeInMinutes(): ?int
    {
        return $this->MonitoringTimeInMinutes;
    }

    /**
     * @return RollbackTrigger[]
     */
    public function getRollbackTriggers(): array
    {
        return $this->RollbackTriggers ?? [];
    }
}
