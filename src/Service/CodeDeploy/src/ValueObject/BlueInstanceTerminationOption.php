<?php

namespace AsyncAws\CodeDeploy\ValueObject;

use AsyncAws\CodeDeploy\Enum\InstanceAction;

/**
 * Information about whether to terminate instances in the original fleet during a blue/green deployment.
 */
final class BlueInstanceTerminationOption
{
    /**
     * The action to take on instances in the original environment after a successful blue/green deployment.
     */
    private $action;

    /**
     * For an Amazon EC2 deployment, the number of minutes to wait after a successful blue/green deployment before
     * terminating instances from the original environment.
     */
    private $terminationWaitTimeInMinutes;

    /**
     * @param array{
     *   action?: null|InstanceAction::*,
     *   terminationWaitTimeInMinutes?: null|int,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->action = $input['action'] ?? null;
        $this->terminationWaitTimeInMinutes = $input['terminationWaitTimeInMinutes'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return InstanceAction::*|null
     */
    public function getAction(): ?string
    {
        return $this->action;
    }

    public function getTerminationWaitTimeInMinutes(): ?int
    {
        return $this->terminationWaitTimeInMinutes;
    }
}
