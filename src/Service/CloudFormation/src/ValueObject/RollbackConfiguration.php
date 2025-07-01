<?php

namespace AsyncAws\CloudFormation\ValueObject;

/**
 * Structure that contains the rollback triggers for CloudFormation to monitor during stack creation and updating
 * operations, and for the specified monitoring period afterwards.
 *
 * Rollback triggers enable you to have CloudFormation monitor the state of your application during stack creation and
 * updating, and to roll back that operation if the application breaches the threshold of any of the alarms you've
 * specified. For more information, see Roll back your CloudFormation stack on alarm breach with rollback triggers [^1].
 *
 * [^1]: https://docs.aws.amazon.com/AWSCloudFormation/latest/UserGuide/using-cfn-rollback-triggers.html
 */
final class RollbackConfiguration
{
    /**
     * The triggers to monitor during stack creation or update actions.
     *
     * By default, CloudFormation saves the rollback triggers specified for a stack and applies them to any subsequent
     * update operations for the stack, unless you specify otherwise. If you do specify rollback triggers for this
     * parameter, those triggers replace any list of triggers previously specified for the stack. This means:
     *
     * - To use the rollback triggers previously specified for this stack, if any, don't specify this parameter.
     * - To specify new or updated rollback triggers, you must specify *all* the triggers that you want used for this stack,
     *   even triggers you've specified before (for example, when creating the stack or during a previous stack update). Any
     *   triggers that you don't include in the updated list of triggers are no longer applied to the stack.
     * - To remove all currently specified triggers, specify an empty list for this parameter.
     *
     * If a specified trigger is missing, the entire stack operation fails and is rolled back.
     *
     * @var RollbackTrigger[]|null
     */
    private $rollbackTriggers;

    /**
     * The amount of time, in minutes, during which CloudFormation should monitor all the rollback triggers after the stack
     * creation or update operation deploys all necessary resources.
     *
     * The default is 0 minutes.
     *
     * If you specify a monitoring period but don't specify any rollback triggers, CloudFormation still waits the specified
     * period of time before cleaning up old resources after update operations. You can use this monitoring period to
     * perform any manual stack validation desired, and manually cancel the stack creation or update (using
     * CancelUpdateStack [^1], for example) as necessary.
     *
     * If you specify 0 for this parameter, CloudFormation still monitors the specified rollback triggers during stack
     * creation and update operations. Then, for update operations, it begins disposing of old resources immediately once
     * the operation completes.
     *
     * [^1]: https://docs.aws.amazon.com/AWSCloudFormation/latest/APIReference/API_CancelUpdateStack.html
     *
     * @var int|null
     */
    private $monitoringTimeInMinutes;

    /**
     * @param array{
     *   RollbackTriggers?: null|array<RollbackTrigger|array>,
     *   MonitoringTimeInMinutes?: null|int,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->rollbackTriggers = isset($input['RollbackTriggers']) ? array_map([RollbackTrigger::class, 'create'], $input['RollbackTriggers']) : null;
        $this->monitoringTimeInMinutes = $input['MonitoringTimeInMinutes'] ?? null;
    }

    /**
     * @param array{
     *   RollbackTriggers?: null|array<RollbackTrigger|array>,
     *   MonitoringTimeInMinutes?: null|int,
     * }|RollbackConfiguration $input
     */
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
