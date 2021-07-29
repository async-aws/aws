<?php

namespace AsyncAws\CloudFormation\ValueObject;

/**
 * The rollback triggers for CloudFormation to monitor during stack creation and updating operations, and for the
 * specified monitoring period afterwards.
 */
final class RollbackConfiguration
{
    /**
     * The triggers to monitor during stack creation or update actions.
     */
    private $rollbackTriggers;

    /**
     * The amount of time, in minutes, during which CloudFormation should monitor all the rollback triggers after the stack
     * creation or update operation deploys all necessary resources.
     */
    private $monitoringTimeInMinutes;

    /**
     * @param array{
     *   RollbackTriggers?: null|RollbackTrigger[],
     *   MonitoringTimeInMinutes?: null|int,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->rollbackTriggers = isset($input['RollbackTriggers']) ? array_map([RollbackTrigger::class, 'create'], $input['RollbackTriggers']) : null;
        $this->monitoringTimeInMinutes = $input['MonitoringTimeInMinutes'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getMonitoringTimeInMinutes(): ?int
    {
        return $this->monitoringTimeInMinutes;
    }

    /**
     * @return RollbackTrigger[]
     */
    public function getRollbackTriggers(): array
    {
        return $this->rollbackTriggers ?? [];
    }
}
